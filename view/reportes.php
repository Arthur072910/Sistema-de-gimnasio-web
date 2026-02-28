<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes | DeluxGym</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/reportes.css">
    <link rel="stylesheet" href="../assets/css/sidebar-global.css">
        <link rel="stylesheet" href="../assets/css/dashboard-global.css">

</head>
<body>
    <?php include dirname(__DIR__) . '/layout/siderbar.php'; ?>

        <!-- Main Content -->
        <div class="main-content" style="flex: 1;">
             <?php 
                $dashboard_path = realpath(__DIR__ . '/../layout/dashboard.php');
                if ($dashboard_path) {
                    include $dashboard_path;
                }
            ?>
            <!-- Header -->
            
            <!-- Filtros -->
            <div class="classes-section mb-4">
                <div class="section-header">
                    <h3 class="chart-title">Reportes</h3>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="form-row align-items-end">
                            <div class="form-group col-md-3">
                                <label>Desde</label>
                                <input type="date" class="form-control" id="desde" name="desde">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Hasta</label>
                                <input type="date" class="form-control" id="hasta" name="hasta">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tipo de reporte</label>
                                <select class="form-control" id="tipo_reporte" name="tipo_reporte">
                                    <option value="pagos">Pagos</option>
                                    <option value="miembros">Miembros</option>
                                    <option value="ingresos">Ingresos</option>
                                </select>
                            </div>
                            <div class="form-group col-md-1">
                                <button class="btn-add w-100">
                                    <i class="fas fa-print"> </i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Pagos -->
            <div class="classes-section">
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

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="../assets/js/reportes.js"></script>

</body>
</html>