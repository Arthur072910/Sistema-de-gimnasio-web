<?php
session_start();

if(!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../controller/ClienteController.php';

$controller = new ClienteController();
$rol = $_SESSION['rol'] ?? 'cliente';

if($rol == 'cliente' && isset($_SESSION['cliente_id'])) {
    $datos = $controller->obtenerDatosCompletos($_SESSION['cliente_id']);

    if(!$datos) {
        $datos = [
            'nombre'            => $_SESSION['cliente_nombre'] ?? 'Usuario',
            'apellido'          => '',
            'email'             => $_SESSION['cliente_email'] ?? '',
            'rol'               => $rol,
            'telefono'          => null,
            'fecha_nacimiento'  => null,
            'plan'              => null,
            'fecha_vencimiento' => null,
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
        'plan'              => 'Acceso Total',
        'fecha_vencimiento' => 'Sin vencimiento',
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Delux Gym</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
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
                    <button class="btn-notify" title="Notificaciones">
                        <i class="fas fa-bell"></i>
                    </button>
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
                            <p>Plan Actual: <strong><?php echo htmlspecialchars($datos['plan'] ?? 'Sin plan activo'); ?></strong></p>
                            <p>Vence el: <span class="role"><?php echo htmlspecialchars($datos['fecha_vencimiento'] ?? 'N/A'); ?></span></p>
                        </div>
                        <div class="suscripcion-box">
                            <a href="#" class="btn-suscripcion btn-gold">
                                <i class="fas fa-sync-alt"></i> Renovar
                            </a>
                            <a href="#" class="btn-tarjeta btn-dark">
                                <i class="fas fa-credit-card"></i> Métodos de Pago
                            </a>
                        </div>
                    </div>
                </section>

            </main>
        </div>

       
        <?php if($rol == 'cliente'): ?>
        <div class="suscripcion-box bottom-actions">
            <form action="tarjeta.php" method="get">
                <button type="submit" class="btn btn-gold">
                    <i class="fa-solid fa-id-card"></i>
                    Tarjeta de inscripción
                </button>
            </form>
            <form action="recomendaciones.php" method="get">
                <button type="submit" class="btn btn-gold">
                    <i class="fa-solid fa-dumbbell"></i>
                    Recomendaciones
                </button>
            </form>
        </div>
        <?php endif; ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <?php include "../layout/footer.php"; ?>
</body>
</html>