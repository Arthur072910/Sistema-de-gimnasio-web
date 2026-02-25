<?php
// config/google_config.php

// Cargar la librería de Google
require_once __DIR__ . '/../vendor/autoload.php';

// TUS CREDENCIALES (YA LAS TIENES)
define('GOOGLE_CLIENT_ID', '11251599018-n4h6envco71efs0u8340h1r8cjje2rtu.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'http://localhost/Sistema-de-gimnasio-web/view/google-callback.php');

/**
 * Crea y configura el cliente de Google
 * @return Google_Client
 */
function getGoogleClient() {
    $client = new Google_Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);
    
    // Pedimos acceso al email y perfil del usuario
    $client->addScope('email');
    $client->addScope('profile');
    
    return $client;
}
?>