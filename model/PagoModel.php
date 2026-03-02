<?php
class PagoModel {
    private $conn;
    private $table = "pagos";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrarPago($id_cliente, $monto, $metodo, $tipo, $id_membresia = null) {
        $sql = "INSERT INTO " . $this->table . " 
                (id_cliente, monto_total, metodo_pago, estado_pago, tipo_transaccion, id_membresia, fecha_pago)
                VALUES (?, ?, ?, 'completado', ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id_cliente, $monto, $metodo, $tipo, $id_membresia]);
    }

    public function obtenerPagosCliente($id_cliente) {
        $sql = "SELECT * FROM " . $this->table . " 
                WHERE id_cliente = ? 
                ORDER BY fecha_pago DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>