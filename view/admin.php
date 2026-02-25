<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Delux Gym | Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <link rel="stylesheet" href="../assets/css/sidebar-global.css">
    <link rel="stylesheet" href="../assets/css/dashboard-global.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body style="background-color: #111111;"> <?php include dirname(__DIR__) . '/layout/siderbar.php'; ?>

    <main class="main-content">
        
        <section class="dashboard-header-wrapper">
            <?php 
                // Asegúrate de que el nombre del archivo sea el correcto (dashboard.php o dashboard_summary.php)
                $path = realpath(__DIR__ . '/../layout/dashboard.php'); 
                if ($path) include $path; 
            ?>
        </section>

        <section class="dashboard-body">
            <div class="welcome-card">
                <div class="d-flex align-items-center">
                    <div class="mr-4">
                        <i class="fas fa-user-shield fa-3x text-warning"></i>
                    </div>
                    <div>
                        <h2 class="mb-1" style="color: #ffd700; font-weight: 800;">PANEL DE CONTROL</h2>
                        <p class="mb-0 text-white-50">Bienvenido al sistema de gestión DeluxGym. Selecciona una sección en el menú para comenzar.</p>
                    </div>
                </div>
            </div>
        </section>

    </main>

</body>
</html>