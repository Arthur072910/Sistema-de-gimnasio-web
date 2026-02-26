<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../phpmailer/Exception.php';
require '../../phpmailer/PHPMailer.php';
require '../../phpmailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombre_remitente = $_POST['nombre'] ?? 'Usuario Anónimo';
    $correo_remitente = $_POST['email']  ?? 'No proporcionado';
    $mensaje_cuerpo   = $_POST['mensaje'] ?? 'Sin mensaje';
    
    $es_cliente = isset($_SESSION['id_usuario']) ? "CLIENTE REGISTRADO" : "VISITANTE WEB";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'deluxgym1@gmail.com'; 
        $mail->Password   = 'vmpgeqbhbvmvmlqf'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('deluxgym1@gmail.com', 'Web Delux Gym');
        
        // --- CAMBIO AQUÍ: Ahora llega a la empresa ---
        $mail->addAddress('deluxgym1@gmail.com'); 
        // ---------------------------------------------

        $mail->addReplyTo($correo_remitente, $nombre_remitente);

        $mail->isHTML(true);
        $mail->Subject = "NUEVA CONSULTA: $nombre_remitente";
        
        $mail->Body = "
            <div style='font-family: sans-serif; max-width: 600px; margin: auto; border: 1px solid #333; background: #fff;'>
                <div style='background: #000; color: #ffd700; padding: 20px; text-align: center;'>
                    <h1 style='margin: 0;'>DELUX GYM</h1>
                    <p style='margin: 0;'>Nueva Inquietud de un $es_cliente</p>
                </div>
                <div style='padding: 20px; color: #333;'>
                    <p><strong>De:</strong> $nombre_remitente</p>
                    <p><strong>Correo del cliente:</strong> $correo_remitente</p>
                    <hr>
                    <p><strong>Mensaje:</strong></p>
                    <p style='background: #f4f4f4; padding: 15px;'>$mensaje_cuerpo</p>
                </div>
            </div>
        ";

        $mail->send();
        echo json_encode(['status' => 'success', 'message' => '¡Mensaje enviado con éxito!']);

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Error: {$mail->ErrorInfo}"]);
    }
}