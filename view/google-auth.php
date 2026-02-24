<?php
// view/google-auth.php
session_start();
require_once __DIR__ . '/../config/google_config.php';

// Obtener cliente de Google
$client = getGoogleClient();

// Generar URL de autenticación
$auth_url = $client->createAuthUrl();

// Redirigir a Google
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
exit();
?>