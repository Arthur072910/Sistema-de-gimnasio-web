<?php
class Dashboard {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerEstadisticas() {
        $stats = [];

        // 1. Total Miembros (Clientes)
        $query = "SELECT COUNT(*) as total FROM clientes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['miembros'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // 2. Total Entrenadores
        $query = "SELECT COUNT(*) as total FROM entrenadores WHERE estado = 'activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['entrenadores'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // 3. Clases activas
        $query = "SELECT COUNT(*) as total FROM clases WHERE estado = 'activa'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['clases'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // 4. Ingresos Totales (Suma de pagos completados)
        $query = "SELECT SUM(monto_total) as total FROM pagos WHERE estado_pago = 'completado'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $totalIngresos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $stats['ingresos'] = number_format($totalIngresos ?? 0, 2);

        // 5. Total Productos
        $query = "SELECT COUNT(*) as total FROM productos WHERE estado = 'activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['productos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // 6. Planes (Tipos de membresía)
        $query = "SELECT COUNT(*) as total FROM tipo_membresia WHERE estado = 'activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['planes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        return $stats;
    }
}