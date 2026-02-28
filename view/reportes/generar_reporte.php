<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/database.php';

use Mpdf\Mpdf;

// Obtener parámetros
$desde = $_GET['desde'] ?? date('Y-m-01');
$hasta = $_GET['hasta'] ?? date('Y-m-d');
$tipo_reporte = $_GET['tipo_reporte'] ?? 'pagos';

// Conectar a la base de datos
$database = new Database();
$db = $database->getConnection();

// Configurar mPDF - CORREGIDO
$mpdf = new Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_top' => 25,
    'margin_bottom' => 25,
    'margin_left' => 20,
    'margin_right' => 20,
    'tempDir' => __DIR__ . '/../../temp', // Directorio temporal
    'default_font' => 'dejavusans', // Fuente por defecto
    'default_font_size' => 10
]);

// Crear directorio temporal si no existe
$tempDir = __DIR__ . '/../../temp';
if (!file_exists($tempDir)) {
    mkdir($tempDir, 0777, true);
}

// Obtener datos según el tipo de reporte
$datos = [];
$titulo = '';
$columnas = [];

switch ($tipo_reporte) {
    case 'pagos':
        $titulo = 'REPORTE DE PAGOS';
        $columnas = ['ID', 'Cliente', 'Monto', 'Método Pago', 'Tipo', 'Estado', 'Fecha'];
        $datos = obtenerReportePagos($db, $desde, $hasta);
        break;
    
    case 'miembros':
        $titulo = 'REPORTE DE MIEMBROS';
        $columnas = ['ID', 'Nombre Completo', 'Email', 'Membresía', 'Vencimiento', 'Estado', 'Teléfono'];
        $datos = obtenerReporteMiembros($db);
        break;
    
    case 'ingresos':
        $titulo = 'REPORTE DE INGRESOS';
        $columnas = ['Fecha', 'Total Ingresos', 'Membresías', 'Productos', 'Transacciones'];
        $datos = obtenerReporteIngresos($db, $desde, $hasta);
        break;

    case 'entrenadores':
        $titulo = 'REPORTE DE ENTRENADORES';
        $columnas = ['ID', 'Nombre', 'Especialidad', 'Teléfono', 'Email', 'Fecha Registro', 'Estado'];
        $datos = obtenerReporteEntrenadores($db);
        break;
    
    case 'clases':
        $titulo = 'REPORTE DE CLASES';
        $columnas = ['ID', 'Nombre', 'Descripción', 'Cupo', 'Inscritos', 'Entrenador', 'Estado', 'Fecha Creación'];
        $datos = obtenerReporteClases($db);
        break;
    
    case 'productos':
        $titulo = 'REPORTE DE PRODUCTOS';
        $columnas = ['ID', 'Nombre', 'Categoría', 'Precio', 'Stock', 'Descripción', 'Estado'];
        $datos = obtenerReporteProductos($db);
        break;
    
    case 'planes':
        $titulo = 'REPORTE DE PLANES';
        $columnas = ['ID', 'Plan', 'Duración', 'Precio', 'Miembros Activos', 'Descripción', 'Estado'];
        $datos = obtenerReportePlanes($db);
        break;
}

// Generar HTML del reporte
$html = generarHTMLReporte($titulo, $columnas, $datos, $desde, $hasta, $tipo_reporte);

// CORRECCIÓN: Limpiar HTML antes de enviar a mPDF
$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');

// Escribir al PDF
$mpdf->WriteHTML($html);

// Nombre del archivo
$nombre_archivo = 'reporte_' . $tipo_reporte . '_' . date('Y-m-d') . '.pdf';

// Salida al navegador
$mpdf->Output($nombre_archivo, 'I');

/**
 * Funciones para obtener datos
 */
function obtenerReportePagos($db, $desde, $hasta) {
    $query = "SELECT 
                p.id_pago,
                CONCAT(c.nombre, ' ', c.apellido) as cliente,
                p.monto_total,
                p.metodo_pago,
                p.tipo_transaccion,
                p.estado_pago,
                DATE_FORMAT(p.fecha_pago, '%d/%m/%Y %H:%i') as fecha
            FROM pagos p
            INNER JOIN clientes c ON p.id_cliente = c.id_cliente
            WHERE DATE(p.fecha_pago) BETWEEN :desde AND :hasta
            ORDER BY p.fecha_pago DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute(['desde' => $desde, 'hasta' => $hasta]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerReporteMiembros($db) {
    $query = "SELECT 
                c.id_cliente,
                CONCAT(c.nombre, ' ', c.apellido) as nombre_completo,
                u.email,
                COALESCE(tm.nombre, 'Sin membresía') as membresia,
                COALESCE(DATE_FORMAT(m.fecha_vencimiento, '%d/%m/%Y'), '-') as vencimiento,
                COALESCE(m.estado, 'sin membresía') as estado,
                COALESCE(c.telefono, '-') as telefono
            FROM clientes c
            INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
            LEFT JOIN membresias m ON c.id_cliente = m.id_cliente AND m.estado = 'activa'
            LEFT JOIN tipo_membresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
            WHERE u.estado = 'activo'
            ORDER BY c.nombre";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerReporteIngresos($db, $desde, $hasta) {
    $query = "SELECT 
                DATE_FORMAT(p.fecha_pago, '%d/%m/%Y') as fecha,
                SUM(CASE WHEN p.estado_pago = 'completado' THEN p.monto_total ELSE 0 END) as total_ingresos,
                SUM(CASE WHEN p.tipo_transaccion = 'membresia' AND p.estado_pago = 'completado' THEN p.monto_total ELSE 0 END) as ingresos_membresias,
                SUM(CASE WHEN p.tipo_transaccion = 'producto' AND p.estado_pago = 'completado' THEN p.monto_total ELSE 0 END) as ingresos_productos,
                COUNT(*) as total_transacciones
            FROM pagos p
            WHERE DATE(p.fecha_pago) BETWEEN :desde AND :hasta
            GROUP BY DATE(p.fecha_pago)
            ORDER BY p.fecha_pago DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute(['desde' => $desde, 'hasta' => $hasta]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $datos = [];
    $totales = [
        'total_ingresos' => 0,
        'ingresos_membresias' => 0,
        'ingresos_productos' => 0,
        'total_transacciones' => 0
    ];
    
    foreach ($resultados as $row) {
        $datos[] = [
            'fecha' => $row['fecha'],
            'total_ingresos' => number_format($row['total_ingresos'], 2),
            'ingresos_membresias' => number_format($row['ingresos_membresias'], 2),
            'ingresos_productos' => number_format($row['ingresos_productos'], 2),
            'total_transacciones' => $row['total_transacciones']
        ];
        
        $totales['total_ingresos'] += $row['total_ingresos'];
        $totales['ingresos_membresias'] += $row['ingresos_membresias'];
        $totales['ingresos_productos'] += $row['ingresos_productos'];
        $totales['total_transacciones'] += $row['total_transacciones'];
    }
    
    // Agregar fila de totales si hay datos
    if (!empty($datos)) {
        $datos[] = [
            'fecha' => 'TOTALES',
            'total_ingresos' => number_format($totales['total_ingresos'], 2),
            'ingresos_membresias' => number_format($totales['ingresos_membresias'], 2),
            'ingresos_productos' => number_format($totales['ingresos_productos'], 2),
            'total_transacciones' => $totales['total_transacciones']
        ];
    }
    
    return $datos;
}

/**
 * REPORTE DE ENTRENADORES
 */
function obtenerReporteEntrenadores($db) {
    $query = "SELECT 
                id_entrenador,
                nombre,
                especialidad,
                COALESCE(telefono, '-') as telefono,
                email,
                DATE_FORMAT(fecha_registro, '%d/%m/%Y') as fecha_registro,
                estado
            FROM entrenadores
            WHERE estado = 'activo'
            ORDER BY nombre";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * REPORTE DE CLASES
 */
function obtenerReporteClases($db) {
    $query = "SELECT 
                c.id_clase,
                c.nombre,
                COALESCE(c.descripcion, '-') as descripcion,
                c.cupo_maximo,
                (SELECT COUNT(*) FROM inscripciones_clases ic WHERE ic.id_clase = c.id_clase AND ic.estado = 'activa') as inscritos,
                COALESCE(e.nombre, 'Sin asignar') as entrenador,
                c.estado,
                DATE_FORMAT(c.fecha_creacion, '%d/%m/%Y') as fecha_creacion
            FROM clases c
            LEFT JOIN entrenadores e ON c.id_entrenador = e.id_entrenador
            WHERE c.estado = 'activo'
            ORDER BY c.nombre";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * REPORTE DE PRODUCTOS
 */
function obtenerReporteProductos($db) {
    $query = "SELECT 
                id_producto,
                nombre,
                categoria,
                precio,
                stock,
                COALESCE(descripcion, '-') as descripcion,
                estado
            FROM productos
            WHERE estado = 'activo'
            ORDER BY nombre";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * REPORTE DE PLANES (MEMBRESÍAS)
 */
function obtenerReportePlanes($db) {
    $query = "SELECT 
                tm.id_tipo_membresia,
                tm.nombre,
                CONCAT(tm.duracion_dias, ' días') as duracion,
                tm.precio,
                (SELECT COUNT(*) FROM membresias m WHERE m.id_tipo_membresia = tm.id_tipo_membresia AND m.estado = 'activa') as miembros_activos,
                COALESCE(tm.descripcion, '-') as descripcion,
                tm.estado
            FROM tipo_membresia tm
            WHERE tm.estado = 'activo'
            ORDER BY tm.precio";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function generarHTMLReporte($titulo, $columnas, $datos, $desde, $hasta, $tipo_reporte) {
    $fecha_actual = date('d/m/Y');
    $desde_formateada = date('d/m/Y', strtotime($desde));
    $hasta_formateada = date('d/m/Y', strtotime($hasta));
    
    $css = '
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #111111;
            background-color: #fff8f8;
            margin: 0;
            padding: 20px;
        }
        
        /* HEADER CON FONDO NEGRO */
        .header {
            background-color: #111111;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-bottom: 3px solid #ffd700;
            display: flex;
            align-items: center;
            gap: 15px; /* Espacio entre logo y texto */
        }

        .logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid #ffd700;
            object-fit: cover;
        }

        .header-text {
            display: flex;
            flex-direction: column;
        }

        .header-title {
            color: #ffd700;
            font-size: 20pt;
            font-weight: bold;
            line-height: 1.2;
            margin: 0;
        }

        .header-subtitle {
            color: #ffffff;
            font-size: 11pt;
            margin: 2px 0 0 0;
        }
        
        /* INFO CARDS MEJORADAS */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .info-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f8f8 100%);
            border-radius: 10px;
            padding: 15px 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            border-left: 5px solid #ffd700;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .info-label {
            font-size: 9pt;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16pt;
            font-weight: 700;
            color: #111111;
        }
        
        .info-value small {
            font-size: 10pt;
            color: #999;
            font-weight: 400;
            margin-left: 5px;
        }
        
        /* TÍTULO SECCIÓN */
        .section-title {
            font-size: 18pt;
            font-weight: 800;
            color: #111111;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 3px solid #ffd700;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .section-title::before {
            content: "";
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100px;
            height: 3px;
            background: #ffd700;
        }
        
        /* TABLA MEJORADA */
        .table-container {
            background: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            margin: 20px 0 30px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }
        
        th {
            background: linear-gradient(135deg, #111111 0%, #1a1a1a 100%);
            color: #fff8f8;
            font-weight: 700;
            padding: 15px 12px;
            text-align: left;
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 3px solid #ffd700;
        }
        
        td {
            padding: 12px 12px;
            border-bottom: 1px solid #eaeaea;
            color: #333333;
        }
        
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        
        tr:hover {
            background-color: #fff2e6;
        }
        
        /* BADGES MEJORADOS */
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 9pt;
            text-align: center;
            min-width: 90px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .badge-completado {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border-bottom: 2px solid #1e8449;
        }
        
        .badge-pendiente {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            color: white;
            border-bottom: 2px solid #ba6b1c;
        }
        
        .badge-fallido {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            color: white;
            border-bottom: 2px solid #943126;
        }
        
        .badge-activa {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }
        
        .badge-vencida {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            color: white;
        }
        
        .badge-default {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }
        
        /* MEMBRESÍA TAG */
        .membresia-tag {
            background: linear-gradient(135deg, #ffd700 0%, #f1c40f 100%);
            color: #111111;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 9pt;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-bottom: 2px solid #d4ac0d;
        }
        
        /* MONTO */
        .monto {
            font-weight: 700;
            color: #111111;
            text-align: right;
            font-size: 10.5pt;
        }
        
        /* EMAIL Y TELÉFONO */
        .email {
            color: #2980b9;
            text-decoration: none;
            border-bottom: 1px dashed #2980b9;
        }
        
        .telefono {
            font-family: "Courier New", monospace;
            font-weight: 700;
            color: #111111;
        }
        
        /* FOOTER */
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px 0 10px 0;
            border-top: 3px solid #ffd700;
            background: linear-gradient(135deg, #f8f8f8 0%, #f0f0f0 100%);
            border-radius: 0 0 10px 10px;
        }
        
        .footer p {
            margin: 5px 0;
            color: #555;
            font-size: 9pt;
        }
        
        .footer .footer-bold {
            font-weight: 800;
            color: #111111;
            font-size: 11pt;
            letter-spacing: 0.5px;
        }
        
        /* NO DATA */
        .no-data {
            text-align: center;
            padding: 40px;
            background: linear-gradient(135deg, #fff8f8 0%, #f5f5f5 100%);
            border: 2px dashed #ffd700;
            border-radius: 15px;
            color: #666;
            font-size: 12pt;
            margin: 30px 0;
        }
    </style>';
    
    $html = '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>' . htmlspecialchars($titulo) . ' - DeluxGym</title>
        ' . $css . '
    </head>
    <body>';
    
    // HEADER CON LOGO
    $html .= '
        <div class="header">
            <img src="../../assets/img/logo_deluxGym.png" class="logo" alt="DeluxGym Logo">
            <div class="header-text">
                <div class="header-title">' . htmlspecialchars($titulo) . '</div>
                <div class="header-subtitle">DELUXGYM - Excelencia en Fitness</div>
            </div>
        </div>';
    
    // INFO CARDS
    $total_registros = !empty($datos) ? count($datos) : 0;
    if ($tipo_reporte == 'ingresos' && !empty($datos)) {
        $total_registros = $total_registros - 1;
    }
    
    $html .= '
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Fecha emisión</div>
                <div class="info-value">' . $fecha_actual . '</div>
            </div>
            <div class="info-card">
                <div class="info-label">Período</div>
                <div class="info-value">' . $desde_formateada . ' <small>hasta</small> ' . $hasta_formateada . '</div>
            </div>
            <div class="info-card">
                <div class="info-label">Total registros</div>
                <div class="info-value">' . $total_registros . ' <small>' . strtolower($titulo) . '</small></div>
            </div>
        </div>';
    
    // TÍTULO DE SECCIÓN
    $html .= '<div class="section-title">📋 DETALLE DEL REPORTE</div>';
    
    // TABLA
    if (empty($datos)) {
        $html .= '<div class="no-data">No hay datos disponibles para el período seleccionado</div>';
    } else {
        $html .= '<div class="table-container">';
        $html .= '<table>';
        $html .= '<thead><tr>';
        foreach ($columnas as $columna) {
            $html .= '<th>' . htmlspecialchars($columna) . '</th>';
        }
        $html .= '</tr></thead><tbody>';
        
        foreach ($datos as $fila) {
            $html .= '<tr>';
            foreach ($fila as $key => $valor) {
                $valor_original = $valor;
                $valor = htmlspecialchars($valor);
                
                // CORRECCIÓN: Detectar estado completado
                if ($key == 'Estado' || $key == 'estado' || $key == 'ESTADO') {
                    $badge_class = 'badge-default';
                    $estado_lower = strtolower($valor_original);
                    
                    if ($estado_lower == 'completado' || $estado_lower == 'activa' || $estado_lower == 'activo') {
                        $badge_class = 'badge-completado';
                    } elseif ($estado_lower == 'pendiente') {
                        $badge_class = 'badge-pendiente';
                    } elseif ($estado_lower == 'fallido' || $estado_lower == 'vencida' || $estado_lower == 'cancelada') {
                        $badge_class = 'badge-fallido';
                    }
                    
                    // Si está vacío, mostrar un badge por defecto
                    if (empty($valor_original) || $valor_original == '-') {
                        $html .= '<td><span class="badge badge-default">SIN ESTADO</span></td>';
                    } else {
                        $html .= '<td><span class="badge ' . $badge_class . '">' . strtoupper($valor) . '</span></td>';
                    }
                }
                elseif ($key == 'Email' || $key == 'email' || $key == 'EMAIL') {
                    $html .= '<td><span class="email">' . $valor . '</span></td>';
                }
                elseif ($key == 'Teléfono' || $key == 'telefono' || $key == 'TELÉFONO') {
                    $html .= '<td><span class="telefono">' . $valor . '</span></td>';
                }
                elseif ($key == 'Membresía' || $key == 'membresia' || $key == 'MEMBRESÍA') {
                    if ($valor != 'Sin membresía' && $valor != '-') {
                        $html .= '<td><span class="membresia-tag">' . $valor . '</span></td>';
                    } else {
                        $html .= '<td>' . $valor . '</td>';
                    }
                }
                elseif (strpos($key, 'Monto') !== false || strpos($key, 'Ingresos') !== false || $key == 'total_ingresos') {
                    $html .= '<td class="monto">$ ' . $valor . '</td>';
                }
                else {
                    $html .= '<td>' . $valor . '</td>';
                }
            }
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
        $html .= '</div>';
    }
    
    // FOOTER
    $html .= '
        <div class="footer">
            <p class="footer-bold">DeluxGym - Sistema de Gestión Deportiva</p>
            <p>Reporte generado el ' . $fecha_actual . ' | © ' . date('Y') . ' Todos los derechos reservados</p>
        </div>
    </body>
    </html>';
    
    return $html;
}
?>