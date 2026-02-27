<?php
session_start();

if(!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../controller/ClienteController.php';
require_once __DIR__ . '/../controller/MembresiaController.php';

$controller = new ClienteController();
$membresiaController = new MembresiaController();

$rol = $_SESSION['rol'] ?? 'cliente';


$membresiaActiva = $membresiaController->obtenerActiva($_SESSION['cliente_id']);


$membresiaActiva = $membresiaActiva ?: null;

$tipoMembresia   = $membresiaActiva['tipo_membresia'] ?? '';
$fechaVencimiento = $membresiaActiva['fecha_vencimiento'] ?? '';


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
    <header class="header">
        <h1><i class="fas fa-user-circle"></i> Mi Perfil</h1>
    </header>

    <div class="profile-grid">

      
        <aside class="profile-card card">
            <div class="avatar-container">
                <img src="<?php echo file_exists('../assets/img/avatar-placeholder.png')
                    ? '../assets/img/avatar-placeholder.png'
                    : 'https://ui-avatars.com/api/?name=' . urlencode($datos['nombre']) . '&background=ffd700&color=111&bold=true'; ?>"
                    alt="Avatar">
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
                            <p>
                                Plan Actual:
                                <?= htmlspecialchars($tipoMembresia) ?>
                            </p>

                            <p>
                                Vence el:
                                <span class="role">
                                    <?= htmlspecialchars($fechaVencimiento) ?>
                                </span>
                            </p>
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
           value="<?= htmlspecialchars($membresiaActiva['id_membresia'] ?? '') ?>">

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
            </section>

        </main>
    </div>

    <?php if($rol == 'cliente'): ?>
    <div class="suscripcion-box bottom-actions">
        <form action="tarjeta.php" method="get">
            <button type="submit" class="btn btn-gold">
                Tarjeta de inscripción
            </button>
        </form>
    </div>
    <?php endif; ?>

    

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<?php include "../layout/footer.php"; ?>

</body>
</html>