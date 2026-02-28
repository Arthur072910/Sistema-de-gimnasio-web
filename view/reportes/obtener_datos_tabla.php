<?php
require_once __DIR__ . '/../../config/database.php';

// Obtener parámetros
$desde = $_GET['desde'] ?? date('Y-m-01');
$hasta = $_GET['hasta'] ?? date('Y-m-d');
$tipo_reporte = $_GET['tipo_reporte'] ?? 'pagos';

// Conectar a la base de datos
$database = new Database();
$db = $database->getConnection();

$datos = [];

switch ($tipo_reporte) {
    case 'pagos':
        $query = "SELECT 
                    p.id_pago as ID,
                    CONCAT(c.nombre, ' ', c.apellido) as Cliente,
                    p.monto_total as Monto,
                    p.metodo_pago as 'Método Pago',
                    p.tipo_transaccion as Tipo,
                    p.estado_pago as Estado,
                    DATE_FORMAT(p.fecha_pago, '%d/%m/%Y %H:%i') as Fecha
                FROM pagos p
                INNER JOIN clientes c ON p.id_cliente = c.id_cliente
                WHERE DATE(p.fecha_pago) BETWEEN :desde AND :hasta
                ORDER BY p.fecha_pago DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute(['desde' => $desde, 'hasta' => $hasta]);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        break;
        
    case 'miembros':
        $query = "SELECT 
                    c.id_cliente as ID,
                    CONCAT(c.nombre, ' ', c.apellido) as 'Nombre Completo',
                    u.email as Email,
                    COALESCE(tm.nombre, 'Sin membresía') as Membresía,
                    COALESCE(DATE_FORMAT(m.fecha_vencimiento, '%d/%m/%Y'), '-') as Vencimiento,
                    COALESCE(m.estado, 'sin membresía') as Estado,
                    COALESCE(c.telefono, '-') as Teléfono
                FROM clientes c
                INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
                LEFT JOIN membresias m ON c.id_cliente = m.id_cliente AND m.estado = 'activa'
                LEFT JOIN tipo_membresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
                WHERE u.estado = 'activo'
                ORDER BY c.nombre";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        break;
        
    case 'ingresos':
        $query = "SELECT 
                    DATE_FORMAT(p.fecha_pago, '%d/%m/%Y') as Fecha,
                    SUM(CASE WHEN p.estado_pago = 'completado' THEN p.monto_total ELSE 0 END) as 'Total Ingresos',
                    SUM(CASE WHEN p.tipo_transaccion = 'membresia' AND p.estado_pago = 'completado' THEN p.monto_total ELSE 0 END) as Membresías,
                    SUM(CASE WHEN p.tipo_transaccion = 'producto' AND p.estado_pago = 'completado' THEN p.monto_total ELSE 0 END) as Productos,
                    COUNT(*) as Transacciones
                FROM pagos p
                WHERE DATE(p.fecha_pago) BETWEEN :desde AND :hasta
                GROUP BY DATE(p.fecha_pago)
                ORDER BY p.fecha_pago DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute(['desde' => $desde, 'hasta' => $hasta]);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($datos)) {
            $totales = [
                'Fecha' => 'TOTALES',
                'Total Ingresos' => 0,
                'Membresías' => 0,
                'Productos' => 0,
                'Transacciones' => 0
            ];
            
            foreach ($datos as $row) {
                $totales['Total Ingresos'] += floatval(str_replace(',', '', $row['Total Ingresos']));
                $totales['Membresías'] += floatval(str_replace(',', '', $row['Membresías']));
                $totales['Productos'] += floatval(str_replace(',', '', $row['Productos']));
                $totales['Transacciones'] += intval($row['Transacciones']);
            }
            
            $datos[] = $totales;
        }
        break;
}

header('Content-Type: application/json');
echo json_encode($datos);
?>