<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../controller/PlanController.php';

$controller = new PlanController();

// AGREGAR
if (isset($_POST['accion']) && $_POST['accion'] == 'agregar') {
    $controller->agregar($_POST);
    header('Location: planes.php');
    exit();
}

// ACTUALIZAR
if (isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    $controller->actualizar($_POST);
    header('Location: planes.php');
    exit();
}

// ELIMINAR
if (isset($_GET['eliminar'])) {
    $controller->eliminar($_GET['eliminar']);
    header('Location: planes.php');
    exit();
}

$planes = $controller->listar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Planes | DeluxGym</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Tu CSS personalizado -->
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/membresia.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar (ajusta según tu proyecto) -->
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
                <li class="nav-item"><a href="planes.php" class="nav-link active"><i class="fas fa-cog"></i> Planes</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="search-area">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Buscar plan...">
                    </div>
                </div>
                <div class="admin-profile">
                    <i class="fas fa-bell"></i>
                    <div class="admin-avatar">AD</div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="classes-section">
                <div class="section-header">
                    <h3 class="chart-title">Gestión de Planes de Membresía</h3>
                </div>

                <!-- Formulario AGREGAR -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Agregar Nuevo Plan</h5>
                        <form method="POST" action="">
                            <input type="hidden" name="accion" value="agregar">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" class="form-control" placeholder="Ej. Premium" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Precio ($)</label>
                                    <input type="number" step="0.01" name="precio" class="form-control" placeholder="49.99" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Duración (días)</label>
                                    <input type="number" name="duracion_dias" class="form-control" placeholder="30" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Estado</label>
                                    <select name="estado" class="form-control">
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="2" placeholder="Beneficios del plan..."></textarea>
                            </div>
                            <button type="submit" class="btn-add">Guardar Plan</button>
                        </form>
                    </div>
                </div>

                <!-- Tabla de Planes -->
                <table class="classes-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Duración (días)</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($planes) > 0): ?>
                            <?php foreach ($planes as $plan): ?>
                                <tr>
                                    <td><?= htmlspecialchars($plan['id_tipo_membresia']) ?></td>
                                    <td><?= htmlspecialchars($plan['nombre']) ?></td>
                                    <td>$<?= number_format($plan['precio'], 2) ?></td>
                                    <td><?= htmlspecialchars($plan['duracion_dias']) ?></td>
                                    <td><?= htmlspecialchars($plan['descripcion']) ?></td>
                                    <td>
                                        <?php if ($plan['estado'] == 'activo'): ?>
                                            <span class="class-status status-active">Activo</span>
                                        <?php else: ?>
                                            <span class="class-status status-cancelled">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn-edit" data-toggle="modal" data-target="#modalEditar"
                                            onclick="cargarDatos(
                                                '<?= $plan['id_tipo_membresia'] ?>',
                                                '<?= htmlspecialchars(addslashes($plan['nombre'])) ?>',
                                                '<?= $plan['precio'] ?>',
                                                '<?= $plan['duracion_dias'] ?>',
                                                '<?= htmlspecialchars(addslashes($plan['descripcion'])) ?>',
                                                '<?= $plan['estado'] ?>'
                                            )">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?eliminar=<?= $plan['id_tipo_membresia'] ?>" class="btn-delete" onclick="return confirm('¿Seguro que deseas eliminar este plan?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center" style="color: var(--text-secondary);">No hay planes registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Editar Plan -->
    <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Plan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="actualizar">
                        <input type="hidden" name="id_tipo_membresia" id="edit_id">

                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Precio ($)</label>
                            <input type="number" step="0.01" name="precio" id="edit_precio" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Duración (días)</label>
                            <input type="number" name="duracion_dias" id="edit_duracion" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Estado</label>
                            <select name="estado" id="edit_estado" class="form-control">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-add">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        function cargarDatos(id, nombre, precio, duracion, descripcion, estado) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_precio').value = precio;
            document.getElementById('edit_duracion').value = duracion;
            document.getElementById('edit_descripcion').value = descripcion;
            document.getElementById('edit_estado').value = estado;
        }
    </script>
</body>
</html>