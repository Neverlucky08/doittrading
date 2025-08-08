<?php

require_once DOIT_GPT_SECURE_CONFIG;

function doit_ajax_email() {
  check_ajax_referer( 'doit_chat', 'nonce' );

  $raw   = trim( $_POST['email'] ?? '' );
  $email = filter_var( $raw, FILTER_SANITIZE_EMAIL );
  $ip    = $_SERVER['REMOTE_ADDR'];

  /* A) ¿La IP ya tiene un e-mail guardado?  -> desbloquea y sal */
  if ( doit_user_has_email( $ip ) ) {
    set_transient( 'doit_' . doit_hash_day( $ip ), 0, DAY_IN_SECONDS );
    wp_send_json_success();
  }

  /* B) Validaciones básicas */
  if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
      wp_send_json_error( [ 'reason' => 'bad_format' ] );
  }
  $domain = substr( strrchr( $email, '@' ), 1 );
  if ( ! checkdnsrr( $domain, 'MX' ) ) {
      wp_send_json_error( [ 'reason' => 'bad_domain' ] );
  }

  /* C) Guarda en tu tabla interna (UNIQUE email evita duplicados) */
  global $wpdb;
  $wpdb->insert(
      $wpdb->prefix . 'doitchat_users',
      [ 'ip' => $ip, 'email' => $email, 'created' => current_time('mysql') ],
      [ '%s','%s','%s' ]
  );

  /* D) Añade a MailPoet SOLO si existe la clase */
  if ( class_exists( '\\MailPoet\\API\\API' ) ) {
      $mailpoet = \MailPoet\API\API::MP( 'v1' );
      $list_id  = MAILPOET_LIST_ID;

      try {
        // ✅ VERIFICAR si el suscriptor ya existe
        $existing_subscriber = null;
        try {
            $existing_subscriber = $mailpoet->getSubscriber($email);
        } catch (\MailPoet\API\MP\v1\APIException $e) {
            // Suscriptor no existe, será null
        }
        
        if ($existing_subscriber) {
            // ✅ SUSCRIPTOR YA EXISTE - verificar estado antes de añadir a lista 6
            
            // Si ya está confirmado (status = 'subscribed'), activar chat inmediatamente
            if ($existing_subscriber['status'] === 'subscribed') {
                // ✅ AÑADIR a lista 6 SIN confirmación automática
                try {
                    // Usar subscribeToLists en lugar de subscribeToList para más control
                    $mailpoet->subscribeToLists($existing_subscriber['id'], [$list_id], [
                        'send_confirmation_email' => false,  // ✅ NO enviar email
                        'schedule_welcome_email' => false    // ✅ NO enviar welcome (opcional)
                    ]);
                } catch (\Exception $e) {
                    // Si el método anterior no funciona, usar alternativo
                    $mailpoet->subscribeToList($existing_subscriber['id'], $list_id);
                }
                
                // Activar chat inmediatamente
                set_transient( 'doit_' . doit_hash_day( $ip ), 0, DAY_IN_SECONDS );
                wp_send_json_success([
                    'message' => 'Ya estás suscrito y confirmado. Chat activado.',
                    'requires_confirmation' => false
                ]);
                return;
                
            } else {
                // Suscriptor existe pero no está confirmado
                // ✅ AÑADIR a lista y ENVIAR confirmación solo si es necesario
                if (REQUIRE_EMAIL_CONFIRMATION) {
                    try {
                        $mailpoet->subscribeToLists($existing_subscriber['id'], [$list_id], [
                            'send_confirmation_email' => true,   // ✅ SÍ enviar email
                            'schedule_welcome_email' => false
                        ]);
                    } catch (\Exception $e) {
                        // Fallback
                        $mailpoet->subscribeToList($existing_subscriber['id'], $list_id);
                    }
                } else {
                    // No requiere confirmación, añadir directamente
                    try {
                        $mailpoet->subscribeToLists($existing_subscriber['id'], [$list_id], [
                            'send_confirmation_email' => false,
                            'schedule_welcome_email' => false
                        ]);
                    } catch (\Exception $e) {
                        $mailpoet->subscribeToList($existing_subscriber['id'], $list_id);
                    }
                }
            }
            
        } else {
            // ✅ SUSCRIPTOR NUEVO - crear y añadir a la lista
            $subscriber = $mailpoet->addSubscriber(
                [
                    'email' => $email,
                    'first_name' => '', // Opcional
                    'last_name' => ''   // Opcional
                ],
                [$list_id], // Array con IDs de listas
                [
                    'send_confirmation_email' => REQUIRE_EMAIL_CONFIRMATION,
                    'schedule_welcome_email' => true
                ]
            );
        }
        
    } catch ( \MailPoet\API\MP\v1\APIException $e ) {
        // ✅ MANEJO ESPECÍFICO de errores de MailPoet
        error_log('[GPT] MailPoet API error: ' . $e->getMessage());
        
        // Si el error es que ya está en la lista, continuar normalmente
        if (strpos($e->getMessage(), 'already subscribed') !== false) {
            // Continuar con la lógica normal
        } else {
            // Para otros errores, log pero continuar
            error_log('[GPT] MailPoet error no manejado: ' . $e->getMessage());
        }
        
    } catch ( \Throwable $e ) {
        error_log('[GPT] MailPoet error general: ' . $e->getMessage());
    }
  }

  /* E) Desbloqueo según configuración */
  if ( REQUIRE_EMAIL_CONFIRMATION ) {
      wp_send_json_success( [
          'message' => 'Email enviado. Confirma tu suscripción para activar el chat.',
          'requires_confirmation' => true
      ] );
  } else {
      set_transient( 'doit_' . doit_hash_day( $ip ), 0, DAY_IN_SECONDS );
      wp_send_json_success( [
          'message' => 'Chat activado.',
          'requires_confirmation' => false
      ] );
  }
}

/* Función AJAX para verificar confirmación manualmente */
function doit_ajax_check_confirmation() {
    check_ajax_referer( 'doit_chat', 'nonce' );
    
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Obtener email de la base de datos
    global $wpdb;
    $user_email = $wpdb->get_var( $wpdb->prepare(
        "SELECT email FROM {$wpdb->prefix}doitchat_users WHERE ip = %s ORDER BY created DESC LIMIT 1",
        $ip
    ));
    
    if ( $user_email && doit_is_subscriber_confirmed( $user_email ) ) {
        set_transient( 'doit_' . doit_hash_day( $ip ), 0, DAY_IN_SECONDS );
        wp_send_json_success([
            'message' => 'Email confirmado. Chat activado.',
            'confirmed' => true
        ]);
    } else {
        wp_send_json_error([
            'reason' => 'not_confirmed',
            'message' => 'Email not confirmed. Check your in Inbox and Spam.'
        ]);
    }
}

// Registrar el nuevo endpoint
add_action( 'wp_ajax_doit_check_confirmation', 'doit_ajax_check_confirmation' );
add_action( 'wp_ajax_nopriv_doit_check_confirmation', 'doit_ajax_check_confirmation' );

?>