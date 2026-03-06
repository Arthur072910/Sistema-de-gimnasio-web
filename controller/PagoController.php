<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/PagoModel.php';

require_once __DIR__ . '/../phpmailer/Exception.php';
require_once __DIR__ . '/../phpmailer/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/SMTP.php';

class PagoController {
    private $db;
    private $pago;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->pago = new PagoModel($this->db);
    }

    public function registrarPagoMembresia($id_cliente, $monto, $metodo_pago, $id_membresia) {
        try {
            // Registrar pago
            $sql = "INSERT INTO pagos 
                    (id_cliente, monto_total, metodo_pago, estado_pago, tipo_transaccion, id_membresia, fecha_pago)
                    VALUES (?, ?, ?, 'completado', 'membresia', ?, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_cliente, $monto, $metodo_pago, $id_membresia]);
            $id_pago = $this->db->lastInsertId();
            
            // ============================================
            // REGISTRAR EN HISTORIAL
            // ============================================
            $sqlH = "INSERT INTO historial 
                    (id_cliente, tipo_accion, id_referencia, descripcion, fecha_accion)
                    VALUES (?, 'pago_completado', ?, ?, NOW())";
            
            $descripcion = "Pago de membresía por $$monto con $metodo_pago";
            $stmtH = $this->db->prepare($sqlH);
            $stmtH->execute([$id_cliente, $id_pago, $descripcion]);
            
            return ['success' => true, 'id_pago' => $id_pago];
            
        } catch (Exception $e) {
            error_log("Error en PagoController->registrarPagoMembresia: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al registrar pago'];
        }
    }

    public function registrarPagoProductos($id_cliente, $monto, $metodo_pago, $items) {
        try {
            $this->db->beginTransaction();
            
            // 1. Insertar el registro de pago
            $sql = "INSERT INTO pagos 
                    (id_cliente, monto_total, metodo_pago, estado_pago, tipo_transaccion, fecha_pago)
                    VALUES (?, ?, ?, 'completado', 'producto', NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_cliente, $monto, $metodo_pago]);
            $id_pago = $this->db->lastInsertId();
            
            // 2. Insertar detalles y ACTUALIZAR STOCK
            foreach ($items as $item) {
                // Insertar en la tabla de detalles
                $sqlD = "INSERT INTO detalle_pago_productos 
                        (id_pago, id_producto, cantidad, precio_unitario)
                        VALUES (?, ?, ?, ?)";
                $stmtD = $this->db->prepare($sqlD);
                $stmtD->execute([$id_pago, $item['id'], $item['cantidad'], $item['precio']]);

                // --- NUEVA LÓGICA DE STOCK ---
                // Restar la cantidad comprada del stock actual del producto
                $sqlStock = "UPDATE productos SET stock = stock - ? WHERE id_producto = ?";
                $stmtS = $this->db->prepare($sqlStock);
                $stmtS->execute([$item['cantidad'], $item['id']]);
                // ------------------------------
            }
            
            // 3. Registrar en historial
            $sqlH = "INSERT INTO historial 
                    (id_cliente, tipo_accion, id_referencia, descripcion, fecha_accion)
                    VALUES (?, 'compra_producto', ?, ?, NOW())";
            
            $descripcion = "Compra de " . count($items) . " productos por $$monto con $metodo_pago";
            $stmtH = $this->db->prepare($sqlH);
            $stmtH->execute([$id_cliente, $id_pago, $descripcion]);
            
            $this->db->commit();

            try {
                // Obtenemos los datos del cliente que está comprando
                $sqlC = "SELECT c.nombre, u.email FROM clientes c 
                         JOIN usuarios u ON c.id_usuario = u.id_usuario 
                         WHERE c.id_cliente = ?";
                $stmtC = $this->db->prepare($sqlC);
                $stmtC->execute([$id_cliente]);
                $datosCliente = $stmtC->fetch(PDO::FETCH_ASSOC);

                if ($datosCliente) {
                    // Llamamos a la función que creamos arriba
                    $this->enviarFacturaEmail($datosCliente, $items, $monto, $id_pago);
                }
            } catch (Exception $eMail) {
                error_log("El pago fue exitoso pero el correo falló: " . $eMail->getMessage());
            }

            return ['success' => true, 'id_pago' => $id_pago];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en PagoController->registrarPagoProductos: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al registrar pago y actualizar stock'];
        }
    }

    public function obtenerHistorialPagos($id_cliente) {
        try {
            $sql = "SELECT p.*, 
                           CASE 
                               WHEN p.tipo_transaccion = 'membresia' THEN tm.nombre
                               ELSE 'Compra de productos'
                           END as concepto
                    FROM pagos p
                    LEFT JOIN membresias m ON p.id_membresia = m.id_membresia
                    LEFT JOIN tipo_membresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
                    WHERE p.id_cliente = ?
                    ORDER BY p.fecha_pago DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_cliente]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error en PagoController->obtenerHistorialPagos: " . $e->getMessage());
            return [];
        }
    }

    // Método para configurar y enviar el correo
    private function enviarFacturaEmail($cliente, $items, $total, $id_pago) {
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor con TUS DATOS
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'deluxgym1@gmail.com'; 
        $mail->Password   = 'vmpgeqbhbvmvmlqf'; // Tu contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Remitente (La empresa)
        $mail->setFrom('deluxgym1@gmail.com', 'Delux Gym - Facturación');
        
        // Destinatario (El cliente que inició sesión)
        $mail->addAddress($cliente['email'], $cliente['nombre']); 

        $mail->isHTML(true);
        $mail->Subject = "Tu Comprobante de Compra en Delux Gym - #$id_pago";
        
        // Diseño de la factura
        $filas = "";
        foreach ($items as $item) {
            $sub = number_format($item['precio'] * $item['cantidad'], 2);
            $filas .= "<tr>
                <td style='padding:10px; border-bottom:1px solid #eee;'>{$item['titulo']}</td>
                <td style='padding:10px; border-bottom:1px solid #eee; text-align:center;'>{$item['cantidad']}</td>
                <td style='padding:10px; border-bottom:1px solid #eee; text-align:right;'>\${$sub}</td>
            </tr>";
        }

        $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #d4af37; border-radius: 8px; overflow: hidden;'>
                <div style='background: #000; color: #ffd700; padding: 20px; text-align: center;'>
                    <h1 style='margin: 0;'>DELUX GYM</h1>
                    <p style='margin: 0;'>COMPROBANTE ELECTRÓNICO</p>
                </div>
                <div style='padding: 20px;'>
                    <p>Hola <strong>{$cliente['nombre']}</strong>,</p>
                    <p>Gracias por tu compra. Aquí tienes el detalle de tu pedido:</p>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <thead>
                            <tr style='background: #f8f8f8;'>
                                <th style='padding: 10px; text-align: left;'>Producto</th>
                                <th style='padding: 10px;'>Cant.</th>
                                <th style='padding: 10px; text-align: right;'>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>$filas</tbody>
                    </table>
                    <div style='text-align: right; margin-top: 20px;'>
                        <h2 style='color: #000;'>Total Pagado: <span style='color: #d4af37;'>\$$total</span></h2>
                    </div>
                    <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                    <p style='font-size: 12px; color: #777; text-align: center;'>Este es un recibo oficial de Delux Gym. Si tienes dudas, contáctanos a deluxgym1@gmail.com</p>
                </div>
            </div>
        ";

        $mail->send();
        } catch (Exception $e) {
            error_log("Error enviando factura: " . $mail->ErrorInfo);
        }
    }

    // Método para el diseño visual de la factura
    private function armarHTMLFactura($cliente, $items, $total, $id_pago) {
        $filas = "";
        foreach ($items as $item) {
            $sub = number_format($item['precio'] * $item['cantidad'], 2);
            $filas .= "<tr>
                <td style='padding:8px; border-bottom:1px solid #444;'>{$item['titulo']}</td>
                <td style='padding:8px; border-bottom:1px solid #444; text-align:center;'>{$item['cantidad']}</td>
                <td style='padding:8px; border-bottom:1px solid #444; text-align:right;'>\${$sub}</td>
            </tr>";
        }

        return "
        <div style='background:#111; color:#fff; padding:30px; font-family:sans-serif; border: 2px solid #d4af37;'>
            <h1 style='color:#d4af37; text-align:center;'>DELUXE GYM</h1>
            <p>Estimado/a <strong>{$cliente['nombre']}</strong>,</p>
            <p>Adjuntamos el detalle de tu compra realizada el " . date('d/m/Y') . ":</p>
            <table style='width:100%; border-collapse:collapse;'>
                <tr style='background:#d4af37; color:#000;'>
                    <th style='padding:8px;'>Producto</th>
                    <th style='padding:8px;'>Cant.</th>
                    <th style='padding:8px;'>Subtotal</th>
                </tr>
                $filas
            </table>
            <h2 style='text-align:right; color:#d4af37;'>Total Pagado: \${$total}</h2>
            <p style='text-align:center; font-size:12px; color:#aaa;'>Gracias por elegir la exclusividad de Deluxe Gym.</p>
        </div>";
    }
}
?>