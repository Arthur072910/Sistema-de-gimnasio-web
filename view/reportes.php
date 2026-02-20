<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes | DeluxGym</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Tu CSS personalizado -->
    <link rel="stylesheet" href="../assets/css/admin.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo-area">
                <img src="../assets/img/logo_deluxGym.png" alt="Logo" style="height: 100px;">
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="admin.php" class="nav-link"><i class="fas fa-chart-pie"></i> Dashboard</a></li>
                <li class="nav-item"><a href="registrouser.php" class="nav-link"><i class="fas fa-users"></i> Registrar usuarios</a></li>
                <li class="nav-item"><a href="miembros.php" class="nav-link"><i class="fas fa-users"></i> Miembros</a></li>
                <li class="nav-item"><a href="clasess.php" class="nav-link"><i class="fas fa-calendar-alt"></i> Clases</a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-credit-card"></i> Pagos</a></li>
                <li class="nav-item"><a href="entrenadores.php" class="nav-link"><i class="fas fa-chart-line"></i> Registro de entrenadores</a></li>
                <li class="nav-item"><a href="horarios.php" class="nav-link"><i class="fas fa-clock"></i> Horarios</a></li>
                <li class="nav-item"><a href="planes.php" class="nav-link"><i class="fas fa-cog"></i> Planes</a></li>
                <li class="nav-item"><a href="reportes.php" class="nav-link active"><i class="fas fa-file-alt"></i> Reportes</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="search-area">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Buscar...">
                    </div>
                </div>
                <div class="admin-profile">
                    <i class="fas fa-bell"></i>
                    <div class="admin-avatar">AD</div>
                </div>
            </div>

            <!-- Cards de resumen -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Ingresos del Mes</h4>
                        <h2>$0.00</h2>
                        <span>Mes actual</span>
                    </div>
                    <div class="stat-icon green">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Ingresos del Año</h4>
                        <h2>$0.00</h2>
                        <span>Año actual</span>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Miembros Activos</h4>
                        <h2>0</h2>
                        <span>Membresías vigentes</span>
                    </div>
                    <div class="stat-icon blue">
                        <i class="fas fa-users"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <h4>Miembros Vencidos</h4>
                        <h2>0</h2>
                        <span>Membresías vencidas</span>
                    </div>
                    <div class="stat-icon pink">
                        <i class="fas fa-user-times"></i>
                    </div>
                </div>
            </div>

            <!-- Tabla de Pagos -->
            <div class="classes-section mt-4">
                <div class="section-header">
                    <h3 class="chart-title">Tabla de Pagos</h3>
                </div>

                <table class="classes-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Monto</th>
                            <th>Método de Pago</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" class="text-center" style="color: var(--text-secondary);">No hay pagos registrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>