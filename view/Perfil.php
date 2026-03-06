<?php
session_start();

if(!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../controller/ClienteController.php';
require_once __DIR__ . '/../controller/MembresiaController.php';
require_once __DIR__ . '/../controller/PagoController.php';
require_once __DIR__ . '/../config/database.php';

$database = new Database();
$conn = $database->getConnection();

$controller = new ClienteController();
$membresiaController = new MembresiaController();
$pagoController = new PagoController();

$rol = $_SESSION['rol'] ?? 'cliente';

// Obtener membresía activa
$membresiaActiva = $membresiaController->obtenerMembresiaActiva($_SESSION['cliente_id']);
$tipoMembresia   = $membresiaActiva['tipo_membresia'] ?? '';
$fechaVencimiento = $membresiaActiva['fecha_vencimiento'] ?? '';
$diasRestantes = $membresiaActiva['dias_restantes'] ?? 0;
$id_membresia = $membresiaActiva['id_membresia'] ?? '';

// Obtener historial de pagos
$historialPagos = $pagoController->obtenerHistorialPagos($_SESSION['cliente_id']);

// Total de clases inscritas
$totalClases = 0;
if($membresiaActiva){
    $stmtCount = $conn->prepare("
        SELECT COUNT(*) 
        FROM inscripciones_clases
        WHERE id_cliente = ?
        AND estado = 'activa'
    ");
    $stmtCount->execute([$_SESSION['cliente_id']]);
    $totalClases = $stmtCount->fetchColumn();
}

// Clases disponibles
$stmtClases = $conn->prepare("
    SELECT id_clase, nombre, descripcion
    FROM clases
    WHERE estado = 'activa'
");
$stmtClases->execute();
$clases = $stmtClases->fetchAll(PDO::FETCH_ASSOC);

// Horarios por clase
$horariosClases = [];
foreach ($clases as $c) {
    $stmtH = $conn->prepare("
        SELECT dia_semana, hora_inicio, hora_fin
        FROM horarios_clases
        WHERE id_clase = ?
        ORDER BY FIELD(dia_semana,'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'), hora_inicio
    ");
    $stmtH->execute([$c['id_clase']]);
    $horariosClases[$c['id_clase']] = $stmtH->fetchAll(PDO::FETCH_ASSOC);
}

// Datos del cliente
if($rol == 'cliente') {
    $datos = $controller->obtenerDatosCompletos($_SESSION['cliente_id']);
    if(!$datos) {
        $datos = [
            'nombre'            => $_SESSION['cliente_nombre'] ?? 'Usuario',
            'apellido'          => '',
            'email'             => $_SESSION['cliente_email'] ?? '',
            'rol'               => $rol,
            'telefono'          => null,
            'fecha_nacimiento'  => null,
        ];
    }
} else {
    $nombreCompleto = $_SESSION['cliente_nombre'] ?? 'Administrador';
    $partes = explode(' ', $nombreCompleto, 2);
    $datos = [
        'nombre'            => $partes[0] ?? $nombreCompleto,
        'apellido'          => $partes[1] ?? '',
        'email'             => $_SESSION['cliente_email'] ?? '',
        'rol'               => $rol,
        'telefono'          => 'N/A',
        'fecha_nacimiento'  => 'N/A',
    ];
}

// Historial de actividades
$stmtHistorial = $conn->prepare("
    SELECT 
        tipo_accion, 
        descripcion, 
        fecha_accion 
    FROM historial 
    WHERE id_cliente = ? 
    ORDER BY fecha_accion DESC
");
$stmtHistorial->execute([$_SESSION['cliente_id']]);
$historialActividades = $stmtHistorial->fetchAll(PDO::FETCH_ASSOC);

// Notificaciones
$notificacion = null;
$claseAlerta = "";

if ($membresiaActiva && !empty($fechaVencimiento)) {
    if ($diasRestantes < 0) {
        $notificacion = "Tu membresía venció. ¡Renueva para no perder tus beneficios!";
        $claseAlerta = "alert-danger";
    } elseif ($diasRestantes <= 31) {
        $notificacion = "Tu membresía vence en " . ($diasRestantes == 0 ? "hoy" : "$diasRestantes días") . ".";
        $claseAlerta = "alert-warning";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mi Perfil - Delux Gym</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/header.css">
<link rel="stylesheet" href="../assets/css/Perfil.css">
<link rel="stylesheet" href="../assets/css/footer.css">
</head>
<body>

<?php include "../layout/header.php"; ?>

<div class="container">

<?php if(isset($_GET['success']) && $_GET['success'] == 'inscrito'): ?>
    <div class="alert alert-success mt-3">
        Te inscribiste correctamente en la clase.
    </div>
<?php endif; ?>

<?php if(isset($_GET['error']) && $_GET['error'] == 'ya_inscrito'): ?>
    <div class="alert alert-warning mt-3">
        Ya estás inscrito en esta clase.
    </div>
<?php endif; ?>

<?php if(isset($_GET['error']) && $_GET['error'] == 'plan_basico'): ?>
    <div class="alert alert-danger mt-3">
        Los planes Básico y Diario no permiten inscripción a clases.
        Mejora tu plan para acceder.
    </div>
<?php endif; ?>

<?php if(isset($_GET['error']) && $_GET['error'] == 'limite_intermedio'): ?>
    <div class="alert alert-warning mt-3">
        Tu plan Intermedio permite solo 2 clases.
        Ya has alcanzado el límite. Actualiza a Premium para tener más clases.
    </div>
<?php endif; ?>

<?php if(isset($_GET['error']) && $_GET['error'] == 'sin_membresia'): ?>
    <div class="alert alert-danger mt-3">
        No tienes una membresía activa.
    </div>
<?php endif; ?>

<?php if(isset($_GET['pago']) && $_GET['pago'] == 'exitoso'): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <i class="fas fa-check-circle mr-2"></i>
        ¡Pago realizado con éxito!
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
<?php endif; ?>

<header class="header">
<h1><i class="fas fa-user-circle"></i> Mi Perfil</h1>
</header>

<div class="profile-grid">

<aside class="profile-card card">
<div class="avatar-container">
<img src="<?php echo file_exists('../assets/img/avatar-placeholder.png')
? '../assets/img/avatar-placeholder.png'
: 'https://ui-avatars.com/api/?name=' . urlencode($datos['nombre']) . '&background=ffd700&color=111&bold=true'; ?>">
<?php if ($notificacion): ?>
        <div class="btn-notify pulse-gold" 
             id="campanaNotificacion"
             data-container="body" 
             data-toggle="popover" 
             data-placement="right" 
             data-html="true"
             data-content='<div class="text-dark"><strong>Aviso:</strong><br><?= $notificacion ?><br><br><a href="renovar.php" class="btn btn-sm btn-warning btn-block">Renovar ahora</a></div>'
             style="cursor: pointer;">
            <i class="fas fa-bell"></i>
        </div>
    <?php endif; ?>
</div>

<h3><?php echo htmlspecialchars($datos['nombre'] . ' ' . $datos['apellido']); ?></h3>
<span class="role"><?php echo strtoupper($datos['rol']); ?></span>

<div class="info-list">
<div class="info-item">
<i class="fas fa-envelope"></i>
<span><?php echo htmlspecialchars($datos['email']); ?></span>
</div>
</div>
</aside>

<main class="profile-details">
    <?php if ($notificacion): ?>
        <div class="alert <?= $claseAlerta ?> border-0 shadow-sm d-flex align-items-center" style="border-radius: 50px; padding: 15px 25px; margin-bottom: 25px;">
            <i class="fas <?= ($claseAlerta == 'alert-danger') ? 'fa-exclamation-triangle' : 'fa-clock' ?> mr-3" style="font-size: 1.2rem;"></i>
            <div class="flex-grow-1">
                <span style="font-weight: 600;"><?= $notificacion ?></span>
            </div>
            <?php if ($claseAlerta == 'alert-warning' || $claseAlerta == 'alert-danger'): ?>
                <a href="renovar.php" class="btn btn-sm btn-dark ml-3" style="border-radius: 50px; font-weight: 700;">RENOVAR</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<section class="card">
<h2><i class="fas fa-id-card"></i> Información de la Cuenta</h2>
<div class="info-grid">
<div class="info-item">
<strong>Teléfono</strong>
<span><?php echo htmlspecialchars($datos['telefono'] ?? 'No registrado'); ?></span>
</div>
<div class="info-item">
<strong>Fecha de Nacimiento</strong>
<span><?php echo htmlspecialchars($datos['fecha_nacimiento'] ?? 'No registrada'); ?></span>
</div>
</div>
</section>

<section class="card racha-card">
<h2><i class="fas fa-crown"></i> Estado de Membresía</h2>

<div class="racha-content">
<div>
<?php if($membresiaActiva): ?>
<p>Plan Actual: <strong><?= htmlspecialchars($tipoMembresia) ?></strong></p>
<p>Vence el: <span class="role"><?= date('d/m/Y', strtotime($fechaVencimiento)) ?></span></p>
<p>Días restantes: <span class="badge badge-warning"><?= $diasRestantes ?> días</span></p>
<?php else: ?>
<p><strong>Sin plan activo</strong></p>
<?php endif; ?>
</div>

<div class="suscripcion-box">
<?php if($membresiaActiva): ?>
    <form action="renovar.php" method="POST" style="display:inline;">
        <input type="hidden" name="renovar" value="1">
        <button type="submit" class="btn btn-gold">
            <i class="fas fa-sync-alt"></i> Renovar
        </button>
    </form>

    <form action="cancelar.php" method="POST"
          onsubmit="return confirm('¿Seguro que deseas cancelar tu membresía?');"
          style="display:inline;">
        <input type="hidden" name="id_membresia"
               value="<?= htmlspecialchars($id_membresia) ?>">
        <button type="submit" class="btn btn-danger">
            Cancelar
        </button>
    </form>
<?php else: ?>
    <a href="plan.php" class="btn btn-success">
        Elegir Plan
    </a>
<?php endif; ?>
</div>
</div>

<?php if($rol == 'cliente'): ?>
<div class="suscripcion-box bottom-actions">
    <a href="tarjeta.php" class="btn btn-gold btn-sm">
        <i class="fas fa-qrcode mr-1"></i> Tarjeta de Inscripción
    </a>
    <button type="button" class="btn btn-gold btn-sm" data-toggle="modal" data-target="#modalHistorial">
        <i class="fas fa-history mr-1"></i> Ver Actividad
    </button>
    <button type="button" class="btn btn-gold btn-sm" data-toggle="modal" data-target="#modalPagos">
        <i class="fas fa-credit-card mr-1"></i> Ver Pagos
    </button>
</div>
<?php endif; ?>
</section>

<?php 
// --- SECCIÓN PARA MOSTRAR CLASES SEGÚN EL PLAN ---
// Versión CORREGIDA que reemplaza acentos correctamente
$planNormalizado = trim(str_replace(['á','é','í','ó','ú',' '], ['a','e','i','o','u',''], mb_strtolower($tipoMembresia, 'UTF-8')));
$esBasico = strpos($planNormalizado, 'basico') !== false;
$esDiario = strpos($planNormalizado, 'diario') !== false;
$esIntermedio = strpos($planNormalizado, 'intermedio') !== false;
$esPremium = strpos($planNormalizado, 'premium') !== false;
$sinMembresia = !$membresiaActiva; // Nueva variable para detectar si no hay membresía

// Para depuración (puedes eliminarlo después)
echo "<!-- Plan original: " . $tipoMembresia . " -->";
echo "<!-- Plan normalizado: " . $planNormalizado . " -->";
echo "<!-- Sin membresía: " . ($sinMembresia ? 'SI' : 'NO') . " -->";
echo "<!-- Es Básico: " . ($esBasico ? 'SI' : 'NO') . " -->";
echo "<!-- Es Diario: " . ($esDiario ? 'SI' : 'NO') . " -->";
echo "<!-- Es Intermedio: " . ($esIntermedio ? 'SI' : 'NO') . " -->";
echo "<!-- Es Premium: " . ($esPremium ? 'SI' : 'NO') . " -->";
?>

<section class="card mt-4">
<h2><i class="fas fa-dumbbell"></i> Clases Disponibles</h2>

<?php 
// Para usuarios SIN MEMBRESÍA: mensaje diferente
if($sinMembresia): 
?>
    <div class="alert alert-secondary mb-3" style="background-color: #333333; border-color: #666666; color: #cccccc;">
        <div class="d-flex align-items-center">
            <i class="fas fa-crown fa-2x mr-3" style="color: #999999;"></i>
            <div>
                <strong>¡Elige un plan!</strong><br>
                No tienes una membresía activa. 
            </div>
        </div>
    </div>

<?php 
// Para planes Básico y Diario: mensaje de mejora
elseif($esBasico || $esDiario): 
?>
    <div class="alert alert-warning mb-3" style="background-color: #332c00; border-color: #ffd700; color: #ffd700;">
        <div class="d-flex align-items-center">
            <i class="fas fa-crown fa-2x mr-3"></i>
            <div>
                <strong>¡Mejora tu experiencia!</strong><br>
                Con tu plan actual no tienes acceso a clases grupales.
            </div>
        </div>
    </div>

<?php 
else: 
    // Para Intermedio y Premium: mostrar la tabla con clases
    
    // Para plan Intermedio: obtener las clases a las que tiene acceso
    $clasesVisibles = [];
    
    if ($esIntermedio) {
        // Obtener las clases donde el usuario YA ESTÁ inscrito
        $stmtInscritas = $conn->prepare("
            SELECT c.* 
            FROM clases c
            INNER JOIN inscripciones_clases ic ON c.id_clase = ic.id_clase
            WHERE ic.id_cliente = ? 
            AND ic.estado = 'activa'
            AND c.estado = 'activa'
        ");
        $stmtInscritas->execute([$_SESSION['cliente_id']]);
        $clasesInscritas = $stmtInscritas->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener las clases disponibles para inscribir (máximo 2 - las ya inscritas)
        $limiteDisponible = 2 - count($clasesInscritas);
        
        if ($limiteDisponible > 0) {
            // Excluir las clases en las que ya está inscrito
            $idsInscritos = array_column($clasesInscritas, 'id_clase');
            
            $sql = "SELECT id_clase, nombre, descripcion FROM clases WHERE estado = 'activa'";
            if (!empty($idsInscritos)) {
                $placeholders = implode(',', array_fill(0, count($idsInscritos), '?'));
                $sql .= " AND id_clase NOT IN ($placeholders)";
            }
            $sql .= " LIMIT " . intval($limiteDisponible);
            
            $stmtDisponibles = $conn->prepare($sql);
            if (!empty($idsInscritos)) {
                $stmtDisponibles->execute($idsInscritos);
            } else {
                $stmtDisponibles->execute();
            }
            $clasesDisponibles = $stmtDisponibles->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $clasesDisponibles = [];
        }
        
        // Combinar clases inscritas y disponibles
        $clasesVisibles = array_merge($clasesInscritas, $clasesDisponibles);
        
    } else {
        // Para Premium: ver todas las clases
        $clasesVisibles = $clases;
    }
?>

<?php if ($esIntermedio): ?>
    <div class="alert alert-info mb-3" style="background-color: #1a3300; border-color: #00ff00; color: #00ff00;">
        <i class="fas fa-info-circle mr-2"></i>
        Tu plan Intermedio te permite inscribirte hasta 2 clases. 
        Actualmente tienes <strong><?= count($clasesInscritas ?? 0) ?></strong> clase(s) inscrita(s) 
        y puedes inscribirte a <strong><?= $limiteDisponible ?? 0 ?></strong> clase(s) más.
    </div>
<?php endif; ?>

<?php if (empty($clasesVisibles) && $esIntermedio): ?>
    <div class="alert alert-secondary mb-3">
        <i class="fas fa-calendar-times mr-2"></i>
        No hay clases disponibles en este momento.
    </div>
<?php else: ?>
<div class="table-responsive">
<table class="table table-hover">
<thead>
<tr>
<th>Clase</th>
<th>Descripción</th>
<th>Acción</th>
</tr>
</thead>
<tbody>

<?php foreach($clasesVisibles as $clase): ?>
    <?php
    // Verificar si el usuario ya está inscrito en esta clase
    $stmtInscrito = $conn->prepare("SELECT id_inscripcion FROM inscripciones_clases WHERE id_cliente = ? AND id_clase = ? AND estado = 'activa'");
    $stmtInscrito->execute([$_SESSION['cliente_id'], $clase['id_clase']]);
    $yaInscrito = $stmtInscrito->rowCount() > 0;

    // Para Intermedio: verificar si puede inscribirse a más clases
    $puedeInscribirse = true;
    if ($esIntermedio && !$yaInscrito) {
        $stmtClasesInscritas = $conn->prepare("
            SELECT COUNT(*) 
            FROM inscripciones_clases 
            WHERE id_cliente = ? AND estado = 'activa'
        ");
        $stmtClasesInscritas->execute([$_SESSION['cliente_id']]);
        $totalClasesInscritas = $stmtClasesInscritas->fetchColumn();
        $puedeInscribirse = $totalClasesInscritas < 2;
    }
    ?>

    <tr>
        <td>
            <div class="d-flex align-items-center justify-content-between">
                <strong class="text-white"><?= htmlspecialchars($clase['nombre']) ?></strong>
                <button type="button"
                        class="btn btn-outline-gold btn-gold ml-2 btn-toggle-horario"
                        style="font-size: 0.75rem;"
                        data-target="horario-row-<?= $clase['id_clase'] ?>">
                    <i class="fas fa-calendar-alt"></i> Ver Horario
                </button>
            </div>
        </td>

        <td class="text-secondary align-middle">
            <?= htmlspecialchars($clase['descripcion']) ?>
        </td>

        <td class="align-middle">
            <?php if($yaInscrito): ?>
                <button class="btn btn-success btn-sm btn-block" disabled>
                    <i class="fas fa-check"></i> Inscrito
                </button>
            <?php elseif(!$puedeInscribirse): ?>
                <button class="btn btn-danger btn-sm btn-block" disabled>
                    <i class="fas fa-lock"></i> Límite alcanzado (2/2)
                </button>
            <?php else: ?>
                <form action="inscribirse.php" method="POST" class="m-0">
                    <input type="hidden" name="id_clase" value="<?= $clase['id_clase'] ?>">
                    <button type="submit" class="btn btn-gold btn-sm btn-block">
                        Inscribirse
                    </button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
    <tr id="horario-row-<?= $clase['id_clase'] ?>" style="display:none;">
        <td colspan="3" class="p-0">
            <div class="p-3">
                <?php if (!empty($horariosClases[$clase['id_clase']])): ?>
                    <div class="d-flex flex-wrap">
                    <?php foreach ($horariosClases[$clase['id_clase']] as $h): ?>
                        <div class="card bg-secondary text-white mr-2 mb-2"
                             style="min-width:160px; border-radius:10px; border:1px solid #ffd700;">
                            <div class="card-body py-2 px-3">
                                <p class="mb-1 font-weight-bold text-warning" style="font-size:0.85rem;">
                                    <i class="fas fa-calendar-day mr-1"></i>
                                    <?= htmlspecialchars($h['dia_semana']) ?>
                                </p>
                                <p class="mb-0" style="font-size:0.82rem;">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?= date('h:i A', strtotime($h['hora_inicio'])) ?> - 
                                    <?= date('h:i A', strtotime($h['hora_fin'])) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle mr-1"></i> Sin horarios asignados.
                    </p>
                <?php endif; ?>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
</table>
</div>
<?php endif; // Fin del if empty clases ?>
<?php endif; // Fin del else para Intermedio/Premium ?>
</section>

</main>
</div>

<div class="modal fade" id="modalHistorial" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content bg-dark text-white border-warning" style="border-radius: 15px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-warning"><i class="fas fa-list-ul mr-2"></i> Mi Historial de Actividad</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-dark mb-0">
                        <thead class="bg-black">
                            <tr class="text-secondary small">
                                <th class="pl-4">Acción</th>
                                <th>Detalle</th>
                                <th class="text-right pr-4">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($historialActividades) > 0): ?>
                                <?php foreach ($historialActividades as $log): ?>
                                    <tr>
                                        <td class="pl-4 align-middle">
                                            <?php 
                                                $icon = 'fa-info-circle text-info';
                                                if(strpos($log['tipo_accion'], 'cancel') !== false) $icon = 'fa-times-circle text-danger';
                                                if(strpos($log['tipo_accion'], 'renov') !== false) $icon = 'fa-sync-alt text-success';
                                                if(strpos($log['tipo_accion'], 'compra') !== false) $icon = 'fa-shopping-cart text-warning';
                                            ?>
                                            <i class="fas <?= $icon ?> mr-2"></i>
                                            <span class="small font-weight-bold text-uppercase"><?= str_replace('_', ' ', $log['tipo_accion']) ?></span>
                                        </td>
                                        <td class="small align-middle text-white-50"><?= htmlspecialchars($log['descripcion']) ?></td>
                                        <td class="text-right pr-4 small align-middle text-muted"><?= date('d/m/Y H:i', strtotime($log['fecha_accion'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center py-5">No hay actividad registrada.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPagos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content bg-dark text-white border-warning" style="border-radius: 15px;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-warning"><i class="fas fa-credit-card mr-2"></i> Mis Pagos</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-dark mb-0">
                        <thead class="bg-black">
                            <tr class="text-secondary small">
                                <th class="pl-4">Fecha</th>
                                <th>Concepto</th>
                                <th>Método</th>
                                <th class="text-right pr-4">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($historialPagos) > 0): ?>
                                <?php foreach ($historialPagos as $pago): ?>
                                    <tr>
                                        <td class="pl-4 align-middle"><?= date('d/m/Y', strtotime($pago['fecha_pago'])) ?></td>
                                        <td class="align-middle">
                                            <?php 
                                                if($pago['tipo_transaccion'] == 'membresia') {
                                                    echo '<i class="fas fa-crown text-warning mr-2"></i>' . htmlspecialchars($pago['concepto']);
                                                } else {
                                                    echo '<i class="fas fa-box text-info mr-2"></i>Compra de productos';
                                                }
                                            ?>
                                        </td>
                                        <td class="align-middle">
                                            <?php 
                                                $metodo = $pago['metodo_pago'];
                                                if($metodo == 'tarjeta') echo '<i class="fas fa-credit-card mr-2"></i>Tarjeta';
                                                elseif($metodo == 'efectivo') echo '<i class="fas fa-money-bill-wave mr-2"></i>Efectivo';
                                                else echo ucfirst($metodo);
                                            ?>
                                        </td>
                                        <td class="text-right pr-4 align-middle text-warning font-weight-bold">$<?= number_format($pago['monto_total'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-5">No hay pagos registrados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="../assets/js/notificaciones.js"></script>

<script>
document.querySelectorAll('.btn-toggle-horario').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var targetId = this.getAttribute('data-target');
        var fila = document.getElementById(targetId);
        if (!fila) return;
        if (fila.style.display === 'none' || fila.style.display === '') {
            fila.style.display = 'table-row';
            this.innerHTML = '<i class="fas fa-calendar-alt"></i> Ocultar';
        } else {
            fila.style.display = 'none';
            this.innerHTML = '<i class="fas fa-calendar-alt"></i> Ver Horario';
        }
    });
});
</script>

<?php include "../layout/footer.php"; ?>

</body>
</html>