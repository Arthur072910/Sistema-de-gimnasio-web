<?php
session_start();
// Desactivamos la visualización de errores para que no rompan el formato JSON de respuesta
error_reporting(0); 

require_once "../../config/Database.php";
require_once "../../model/Membresia.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../phpmailer/Exception.php';
require '../../phpmailer/PHPMailer.php';
require '../../phpmailer/SMTP.php';

// 1. Verificar sesión activa (usando el nombre de variable de tu sistema)
if (!isset($_SESSION['usuario_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'Sesion no encontrada']));
}

try {
    $database = new Database();
    $db = $database->getConnection();
    $membresiaModel = new Membresia($db);

    // 2. Obtener datos de la membresía activa del usuario logueado
    $datos = $membresiaModel->obtenerDatosParaAlerta($_SESSION['usuario_id']);

    if (!$datos) {
        die(json_encode(['status' => 'info', 'message' => 'Sin membresia activa para notificar']));
    }

    $id_cliente = $datos['id_cliente'];
    $id_membresia = $datos['id_membresia'];
    $dias = (int)$datos['dias_restantes'];
    $email_cliente = $datos['email'];
    $nombre_cliente = $datos['nombre_usuario'];
    $tipo_plan = $datos['tipo_membresia'];
    $fecha_vence = $datos['fecha_vencimiento'];

    // 3. Definir en qué días se enviará el correo (5, 2 y 1 día antes)
    if ($dias == 5 || $dias == 2 || $dias == 1) {
        
        // 4. VALIDACIÓN PROFESIONAL: ¿Ya se notificó sobre ESTA membresía específica hoy?
        // Esto permite que si compra una nueva membresía el mismo día, sí reciba el correo.
        if ($membresiaModel->yaSeEnvioAlertaHoy($id_cliente, $id_membresia)) {
            die(json_encode(['status' => 'info', 'message' => 'Ya se envio un recordatorio para esta membresia el dia de hoy.']));
        }

        // 5. Configuración de PHPMailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'deluxgym1@gmail.com';
        $mail->Password   = 'vmpgeqbhbvmvmlqf'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('deluxgym1@gmail.com', 'Delux Gym');
        $mail->addAddress($email_cliente); 

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = "⚠️ ¡Atención! Tu membresía en Delux Gym vence pronto";

        // 6. DISEÑO PROFESIONAL (Paleta: Black, Gold, White)
        $mail->Body = "
        <div style='background-color: #111111; padding: 40px; font-family: Arial, sans-serif; color: #fff8f8; text-align: center;'>
            <div style='max-width: 550px; margin: 0 auto; background-color: #1a1a1a; padding: 30px; border: 2px solid #ffd700; border-radius: 15px;'>
                
                <h1 style='color: #ffd700; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 2px;'>Delux Gym</h1>
                <p style='color: #ffd700; font-size: 12px; margin-top: 0;'>TU FUERZA, NUESTRO COMPROMISO</p>
                
                <hr style='border: 0; border-top: 1px solid #333; margin: 25px 0;'>
                
                <p style='font-size: 18px;'>Hola, <span style='color: #ffd700; font-weight: bold;'>$nombre_cliente</span></p>
                
                <p style='font-size: 16px; line-height: 1.6; color: #fff8f8;'>
                    Queremos recordarte que tu membresía está llegando a su fin. 
                    ¡No detengas tu entrenamiento y mantén tus resultados!
                </p>

                <div style='background-color: #ffd700; color: #111111; padding: 20px; margin: 30px 0; border-radius: 10px;'>
                    <span style='display: block; font-size: 14px; text-transform: uppercase; font-weight: bold;'>Tu plan $tipo_plan</span>
                    <span style='display: block; font-size: 28px; font-weight: 900;'>VENCE EN $dias " . ($dias == 1 ? 'DÍA' : 'DÍAS') . "</span>
                </div>

                <p style='font-size: 14px; color: #aaaaaa;'>
                    Fecha exacta de vencimiento: <b style='color: #fff8f8;'>$fecha_vence</b>
                </p>

                <p style='margin-top: 35px;'>
                    <a href='http://localhost/Sistema-de-gimnasio-web/' 
                       style='background-color: #ffd700; color: #111111; padding: 15px 30px; text-decoration: none; font-weight: bold; border-radius: 5px; display: inline-block; text-transform: uppercase; font-size: 14px;'>
                       Renovar Membresía Ahora
                    </a>
                </p>

                <p style='font-size: 11px; margin-top: 45px; color: #555555; line-height: 1.4;'>
                    Este es un recordatorio automático. Si ya realizaste tu renovación en las últimas horas, por favor ignora este mensaje.<br>
                    © " . date('Y') . " Delux Gym. Todos los derechos reservados.
                </p>
            </div>
        </div>";

        // 7. Enviar y registrar en Base de Datos
        if ($mail->send()) {
            $membresiaModel->registrarNotificacion($id_cliente, $id_membresia, $dias);
            echo json_encode(['status' => 'success', 'message' => 'Correo enviado exitosamente a ' . $email_cliente]);
        }

    } else {
        echo json_encode(['status' => 'info', 'message' => "Faltan $dias dias. El sistema notificara cuando falten 5, 2 o 1 dia."]);
    }

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Error al enviar: " . $mail->ErrorInfo]);
}