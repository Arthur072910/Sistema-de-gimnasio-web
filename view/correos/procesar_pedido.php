<?php
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../phpmailer/Exception.php';
require '../../phpmailer/PHPMailer.php';
require '../../phpmailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodo = $_POST['metodo'] ?? 'No especificado';
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'deluxgym1@gmail.com';
        $mail->Password   = 'vmpgeqbhbvmvmlqf';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('deluxgym1@gmail.com', 'Delux Gym System');
        $mail->addAddress('diegocenteno288@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = 'Nueva Confirmacion de Pedido - Delux Gym';
        $mail->Body    = "<h3>¡Nuevo Pedido Recibido!</h3>
                          <p>Un usuario ha finalizado una compra.</p>
                          <p><b>Metodo de pago:</b> $metodo</p>";

        $mail->send();
        echo json_encode(['status' => 'success', 'message' => '¡Pedido confirmado y correo enviado!']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Error: {$mail->ErrorInfo}"]);
    }
}
?>