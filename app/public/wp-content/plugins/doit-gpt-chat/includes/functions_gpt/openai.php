<?php
// lógica de conversación.
require_once DOIT_GPT_SECURE_CONFIG;

function doit_call_assistant($question){
    if ( defined('DOIT_GPT_TEST_MODE') && DOIT_GPT_TEST_MODE ) {
        sleep(2);
        return '🔧 Modo test: IA desactivada.';
    }
    $key  = OPENAI_API_KEY;
    $aid  = OPENAI_ASSISTANT_ID;

    $headers = [
    'Authorization' => "Bearer $key",
    'Content-Type'  => 'application/json',
    'OpenAI-Beta'   => 'assistants=v2',
    ];

    /* 1) Crear thread con el mensaje del usuario */
    $tRes = wp_remote_post(
        'https://api.openai.com/v1/threads',
        [
            'headers' => $headers,
            'body'    => json_encode( [
                'messages' => [
                    [ 'role' => 'user', 'content' => $question ]
                ]
            ] ),
            'timeout' => 30,
        ]
    );

    if ( is_wp_error( $tRes ) ) {
        error_log( '[GPT] WP_Error al crear thread: ' . $tRes->get_error_message() );
        return 'Error interne (API).';
    }

    $code = wp_remote_retrieve_response_code( $tRes );
    $body = wp_remote_retrieve_body( $tRes );

    if ( $code !== 200 ) {
        error_log( "[GPT] HTTP $code al crear thread — body: $body" );
        return "Error $code OpenAI.";
    }

    $data   = json_decode( $body, true );
    $thread = $data['id'] ?? null;

    if ( ! $thread ) {
        error_log( '[GPT] No se obtuvo ID de thread. Body: ' . $body );
        return 'Error thread ID.';
    }

    /* 2) Lanzar run (sin volver a enviar mensajes) */
    $runRes = wp_remote_post(
        "https://api.openai.com/v1/threads/$thread/runs",
        [
            'headers' => $headers,
            'body'    => json_encode( [ 'assistant_id' => $aid ] ),
            'timeout' => 30,
        ]
    );

    $run = json_decode( wp_remote_retrieve_body( $runRes ), true )['id'] ?? null;
    if ( ! $run ) {
        error_log( '[GPT] No se obtuvo ID de run. Body: ' . wp_remote_retrieve_body( $runRes ) );
        return 'Error run ID.';
    }

    /* 3) Polling: espera hasta que el run esté "completed" */
    $attempts = 0;
    do {
        sleep( 1 );                                  // 1 s
        $statusRes = wp_remote_get(
            "https://api.openai.com/v1/threads/$thread/runs/$run",
            [ 'headers' => $headers, 'timeout' => 15 ]
        );
        $statusBody = json_decode( wp_remote_retrieve_body( $statusRes ), true );
        $status     = $statusBody['status'] ?? 'no_status';
        $attempts++;
    } while ( $status === 'in_progress' && $attempts < 8 );

    if ( $status !== 'completed' ) {
        error_log( "[GPT] Run no completado ($status)." );
        return 'Error: run ' . $status . '.';
    }

    /* 4) Obtener la respuesta */
    $msg = wp_remote_get(
        "https://api.openai.com/v1/threads/$thread/messages",
        [ 'headers' => $headers, 'timeout' => 25 ]
    );

    $answer = json_decode( wp_remote_retrieve_body( $msg ), true )['data'][0]['content'][0]['text']['value'] ?? 'No reply.';
    
    // ✅ FORMATEAR la respuesta antes de devolverla
    return doit_format_response($answer);
}

/* ---------- AJAX handler para obtener estado ---------- */
function doit_ajax_get_status(){
    check_ajax_referer('doit_chat','nonce');
    $ip = $_SERVER['REMOTE_ADDR'];

    $user_has_email = doit_user_has_email($ip);
    $email_confirmed = false;
    $waiting_confirmation = false;
    
    if($user_has_email) {
        $user_email = doit_get_email_by_ip($ip);
        if($user_email) {
            $email_confirmed = doit_is_subscriber_confirmed($user_email);
            // Si tiene email pero no está confirmado, está esperando confirmación
            $waiting_confirmation = !$email_confirmed;
        }
    }
    
    // ✅ LÓGICA CORREGIDA: Solo considerar que "tiene email" si está confirmado
    $effective_has_email = $user_has_email && $email_confirmed;
    
    $limit = $effective_has_email ? DOIT_MAX_MSGS_EMAIL_CONFIRMED : DOIT_MAX_MSGS_FREE;
    $current_count = doit_get_counter($ip);
    $remaining = max(0, $limit - $current_count);
    
    wp_send_json_success([
        'remaining' => $remaining,
        'current_count' => $current_count,
        'limit' => $limit,
        'user_has_email' => $effective_has_email, // ✅ Solo true si está confirmado
        'waiting_confirmation' => $waiting_confirmation
    ]);
}

/* ---------- AJAX handler para preguntas ---------- */
function doit_ajax_ask(){
    check_ajax_referer('doit_chat','nonce');
    $q  = sanitize_text_field($_POST['q']??'');
    $ip = $_SERVER['REMOTE_ADDR'];

    $user_has_email = doit_user_has_email($ip);
    $limit = $user_has_email ? DOIT_MAX_MSGS_EMAIL_CONFIRMED : DOIT_MAX_MSGS_FREE;
    $current_count = doit_get_counter($ip);
    
    // Verificar si el usuario tiene email confirmado (no solo registrado)
    $email_confirmed = false;
    if($user_has_email) {
        $user_email = doit_get_email_by_ip($ip);
        if($user_email) {
            $email_confirmed = doit_is_subscriber_confirmed($user_email);
        }
    }
    
    // Si el usuario ya tiene email confirmado, usar el límite mayor
    if($email_confirmed) {
        $limit = DOIT_MAX_MSGS_EMAIL_CONFIRMED;
    }
    
    // Enviar información sobre el estado del usuario cuando se alcanza el límite
    if($current_count >= $limit){
        wp_send_json_error([
            'reason' => 'limit',
            'remaining' => 0,
            'current_count' => $current_count,
            'limit' => $limit,
            'user_has_email' => $user_has_email || $email_confirmed
        ]);
    }

    $answer = doit_call_assistant($q);
    doit_log_interaction($ip,$q,$answer);
    doit_increment_counter($ip);
    
    // Calcular remaining después de incrementar
    $new_count = doit_get_counter($ip);
    $remaining = $limit - $new_count;

    wp_send_json_success([
        'answer' => $answer,
        'remaining' => $remaining,
        'user_has_email' => $user_has_email || $email_confirmed
    ]);
}

/**
 * Formatea respuestas largas para mejor legibilidad
 */
function doit_format_response($text) {
    if (empty($text) || strlen($text) < 100) {
        return $text;
    }
    
    $formatted = $text;
    
    // Separar después de puntos seguidos de espacio y mayúscula (MÁS ESPACIO)
    $formatted = preg_replace('/\. ([A-Z])/', ".\n\n\n\n$1", $formatted);
    
    // Separar listas que empiecen con guión
    $formatted = preg_replace('/( - )/', "\n\n$1", $formatted);
    
    // Separar después de dos puntos seguidos de espacio y mayúscula
    $formatted = preg_replace('/: ([A-Z][^.]*\.)/', ":\n\n\n$1", $formatted);
    
    // Separar frases largas en comas (más de 120 caracteres)
    $formatted = preg_replace('/,( [A-Z][^,.]{40,})/', ",\n\n\n$1", $formatted);

    // Separar preguntas al final
    $formatted = preg_replace('/\?( [¿?][^.]*\?)/', "?\n\n\n$1", $formatted);
   
    // Limpiar espacios múltiples al inicio de líneas
    $formatted = preg_replace('/\n\s+/', "\n", $formatted);
    
    // Normalizar múltiples saltos de línea (máximo 4)
    $formatted = preg_replace('/\n{5,}/', "\n\n\n\n", $formatted);
    
    return trim($formatted);
}
?>