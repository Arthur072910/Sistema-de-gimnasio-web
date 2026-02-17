<?php
session_start();
if(!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../controller/ClienteController.php';
$controller = new ClienteController();
$datos = $controller->obtenerDatosCompletos($_SESSION['cliente_id']);

if (!$datos) {
    echo "Error al cargar los datos del perfil.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Delux Gym</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/Perfil.css">
</head>
<body>
    <?php include __DIR__ . '/layout/header.php'; ?>

    <div class="container"> <header class="header">
            <h1><i class="fas fa-user-circle"></i> MI PERFIL</h1>
        </header>

        <div class="profile-grid">
            <aside class="profile-card card">
                <div class="avatar-container">
                    <button class="btn-notify"><i class="fas fa-bell"></i></button>
                    <img src="<?php echo file_exists('../assets/img/avatar-placeholder.png') ? '../assets/img/avatar-placeholder.png' : 'https://ui-avatars.com/api/?name=' . urlencode($datos['nombre']); ?>" alt="Avatar">
                </div>
                <h3><?php echo htmlspecialchars($datos['nombre'] . ' ' . $datos['apellido']); ?></h3>
                <p class="role"><?php echo strtoupper($datos['rol']); ?></p>
                
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
                            <strong>Teléfono:</strong>
                            <span><?php echo htmlspecialchars($datos['telefono'] ?? 'No registrado'); ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Fecha de Nacimiento:</strong>
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
                            <a href="#" class="btn-suscripcion btn-gold">RENOVAR</a>
                            <a href="#" class="btn-tarjeta btn-dark">MÉTODOS DE PAGO</a>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <div class="suscripcion-box">

    

    <form action="tarjeta.php" method="get">
        <button type="submit" class="  btn btn-gold"  >
            <i class="fa-solid fa-id-card"></i>
            Tarjeta de inscripción
        </button>
    </form>
    <form action="recomendaciones.php" method="get">
        <button type="submit" class="  btn btn-gold"  >
            <i class="fa-solid fa-credit-card"></i>
            Recomendaciones
        </button>
    </form>

</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>