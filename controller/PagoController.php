<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/PagoModel.php';

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
            
            // Insertar pago
            $sql = "INSERT INTO pagos 
                    (id_cliente, monto_total, metodo_pago, estado_pago, tipo_transaccion, fecha_pago)
                    VALUES (?, ?, ?, 'completado', 'producto', NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_cliente, $monto, $metodo_pago]);
            $id_pago = $this->db->lastInsertId();
            
            // Insertar detalles
            foreach ($items as $item) {
                $sqlD = "INSERT INTO detalle_pago_productos 
                        (id_pago, id_producto, cantidad, precio_unitario)
                        VALUES (?, ?, ?, ?)";
                $stmtD = $this->db->prepare($sqlD);
                $stmtD->execute([$id_pago, $item['id'], $item['cantidad'], $item['precio']]);
            }
            
            // ============================================
            // REGISTRAR EN HISTORIAL
            // ============================================
            $sqlH = "INSERT INTO historial 
                    (id_cliente, tipo_accion, id_referencia, descripcion, fecha_accion)
                    VALUES (?, 'compra_producto', ?, ?, NOW())";
            
            $descripcion = "Compra de " . count($items) . " productos por $$monto con $metodo_pago";
            $stmtH = $this->db->prepare($sqlH);
            $stmtH->execute([$id_cliente, $id_pago, $descripcion]);
            
            $this->db->commit();
            return ['success' => true, 'id_pago' => $id_pago];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en PagoController->registrarPagoProductos: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al registrar pago'];
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
}
?>