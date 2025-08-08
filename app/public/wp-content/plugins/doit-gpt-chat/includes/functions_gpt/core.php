<?php
// límites + logging + tablas.

require_once DOIT_GPT_SECURE_CONFIG;

function doit_hash_day($ip){ return md5($ip . date('Y-m-d')); }

function doit_get_counter($ip){
    return (int) get_transient('doit_' . doit_hash_day($ip));
}

function doit_increment_counter($ip){
    set_transient('doit_' . doit_hash_day($ip),
                  doit_get_counter($ip)+1,
                  DAY_IN_SECONDS);
}

function doit_user_has_email($ip){
    global $wpdb;
    return (bool) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}doitchat_users WHERE ip=%s",
            $ip
        )
    );
}

/* Nueva función para obtener email por IP */
function doit_get_email_by_ip($ip){
    global $wpdb;
    return $wpdb->get_var(
        $wpdb->prepare(
            "SELECT email FROM {$wpdb->prefix}doitchat_users WHERE ip=%s ORDER BY created DESC LIMIT 1",
            $ip
        )
    );
}

/* Función para verificar si un suscriptor está confirmado */
function doit_is_subscriber_confirmed($email){
    if (!class_exists('\\MailPoet\\API\\API')) {
        return false;
    }
    
    try {
        $mailpoet = \MailPoet\API\API::MP('v1');
        $subscriber = $mailpoet->getSubscriber($email);
        
        // Verificar si el estado es 'subscribed' (confirmado)
        return isset($subscriber['status']) && $subscriber['status'] === 'subscribed';
        
    } catch (\Exception $e) {
        error_log('[GPT] Error verificando confirmación: ' . $e->getMessage());
        return false;
    }
}

function doit_is_waiting_confirmation($ip) {
    // Si no tiene email, no está esperando confirmación
    if (!doit_user_has_email($ip)) {
        return false;
    }
    
    // Si tiene email, verificar si está confirmado
    $email = doit_get_email_by_ip($ip);
    if (!$email) {
        return false;
    }
    
    // Si el email no está confirmado Y el usuario alcanzó el límite, está esperando confirmación
    $is_confirmed = doit_is_subscriber_confirmed($email);
    $current_count = doit_get_counter($ip);
    $limit = DOIT_MAX_MSGS_FREE;
    
    return !$is_confirmed && $current_count >= $limit;
}

function doit_log_interaction($ip,$q,$a){
    global $wpdb;
    $wpdb->insert(
        $wpdb->prefix.'doitchat_logs',
        ['ip'=>$ip,'question'=>$q,'answer'=>$a,'created'=>current_time('mysql')],
        ['%s','%s','%s','%s']
    );
}

function doit_create_tables(){
    global $wpdb; $c=$wpdb->get_charset_collate();
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}doitchat_logs(
        id BIGINT UNSIGNED AUTO_INCREMENT,
        ip VARCHAR(45),question TEXT,answer TEXT,created DATETIME,
        PRIMARY KEY(id)
    ) $c;");
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}doitchat_users(
        id BIGINT UNSIGNED AUTO_INCREMENT,
        ip VARCHAR(45),email VARCHAR(191),created DATETIME,
        PRIMARY KEY(id),UNIQUE(email)
    ) $c;");
}
?>