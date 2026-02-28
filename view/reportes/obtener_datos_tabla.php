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
                    COUNT(*) as Transacciones,
                    SUM(CASE WHEN p.estado_pago = 'completado' THEN p.monto_total ELSE 0 END) as 'Total',
                    SUM(CASE WHEN p.tipo_transaccion = 'membresia' AND p.estado_pago = 'completado' THEN p.monto_total ELSE 0 END) as Membresías,
                    SUM(CASE WHEN p.tipo_transaccion = 'producto' AND p.estado_pago = 'completado' THEN p.monto_total ELSE 0 END) as Productos
                FROM pagos p
                WHERE DATE(p.fecha_pago) BETWEEN :desde AND :hasta
                GROUP BY DATE(p.fecha_pago)
                ORDER BY p.fecha_pago DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute(['desde' => $desde, 'hasta' => $hasta]);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        break;
        
    case 'entrenadores':
        $query = "SELECT 
                    id_entrenador as ID,
                    nombre as Nombre,
                    especialidad as Especialidad,
                    COALESCE(telefono, '-') as Teléfono,
                    email as Email,
                    DATE_FORMAT(fecha_registro, '%d/%m/%Y') as 'Fecha Registro',
                    estado as Estado
                FROM entrenadores
                WHERE estado = 'activo'
                ORDER BY nombre";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        break;
        
   case 'clases':
    $query = "SELECT 
                c.id_clase as ID,
                c.nombre as Nombre,
                COALESCE(c.descripcion, '-') as Descripción,
                c.cupo_maximo as Cupo,
                COALESCE(
                    (SELECT COUNT(*) FROM inscripciones_clases ic 
                     WHERE ic.id_clase = c.id_clase AND ic.estado = 'activa'), 
                    0
                ) as Inscritos,
                COALESCE(e.nombre, 'Sin asignar') as Entrenador,
                c.estado as Estado,
                DATE_FORMAT(c.fecha_creacion, '%d/%m/%Y') as 'Fecha Creación'
            FROM clases c
            LEFT JOIN entrenadores e ON c.id_entrenador = e.id_entrenador
            ORDER BY c.id_clase DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Si no hay datos, al menos mostrar un mensaje
    if (empty($datos)) {
        error_log("No hay clases en la base de datos");
    }
    break;
        
    case 'productos':
        $query = "SELECT 
                    id_producto as ID,
                    nombre as Nombre,
                    categoria as Categoría,
                    CONCAT('$', FORMAT(precio, 2)) as Precio,
                    stock as Stock,
                    COALESCE(descripcion, '-') as Descripción,
                    estado as Estado
                FROM productos
                WHERE estado = 'activo'
                ORDER BY nombre";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        break;
        
    case 'planes':
        $query = "SELECT 
                    tm.id_tipo_membresia as ID,
                    tm.nombre as Plan,
                    CONCAT(tm.duracion_dias, ' días') as Duración,
                    CONCAT('$', FORMAT(tm.precio, 2)) as Precio,
                    (SELECT COUNT(*) FROM membresias m WHERE m.id_tipo_membresia = tm.id_tipo_membresia AND m.estado = 'activa') as 'Miembros Activos',
                    COALESCE(tm.descripcion, '-') as Descripción,
                    tm.estado as Estado
                FROM tipo_membresia tm
                WHERE tm.estado = 'activo'
                ORDER BY tm.precio";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        break;
}

// Devolver datos como JSON
header('Content-Type: application/json');
echo json_encode($datos);
?>