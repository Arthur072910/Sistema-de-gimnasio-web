<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../controller/ClasesController.php';

$controller = new ClasesController();

// AGREGAR
if (isset($_POST['accion']) && $_POST['accion'] == 'agregar') {
    $controller->agregar($_POST);
    header('Location: clases.php');
    exit();
}

// ACTUALIZAR
if (isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    $controller->actualizar($_POST);
    header('Location: clases.php');
    exit();
}

// ELIMINAR
if (isset($_GET['eliminar'])) {
    $controller->eliminar($_GET['eliminar']);
    header('Location: clases.php');
    exit();
}

$clases = $controller->listar();
$entrenadores = $controller->listarEntrenadores(); // para el select
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clases | DeluxGym</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/clasess.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div style="display: flex; width: 100%;">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo-area text-center py-4">
                <img src="../assets/img/ChatGPT Image 30 ene 2026, 10_35_11 p.m..png" alt="Logo" style="height:100px;">
            </div>
            <ul class="nav flex-column nav-menu px-3">
                <li class="nav-item">
                    <a href="#" class="nav-link active text-white">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="../view/registrouser.php" class="nav-link text-white">Registrar nuevos usuarios</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">Miembros</a>
                </li>
                <li class="nav-item">
                    <a href="../view/clases.php" class="nav-link text-white">Clases</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">Pagos</a>
                </li>
                <li class="nav-item">
                    <a href="../view/entrenadores.php" class="nav-link text-white">Registro de entrenadores</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">Horarios</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white">Planes</a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div class="search-area">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Buscar clase...">
                    </div>
                </div>
                <div class="admin-profile">
                    <i class="fas fa-bell"></i>
                    <div class="admin-avatar">AD</div>
                </div>
            </div>

            <div class="classes-section">
                <div class="section-header">
                    <h3 class="chart-title">Gestión de Clases</h3>
                </div>

                <!-- Formulario AGREGAR -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Agregar Nueva Clase</h5>
                        <form method="POST" action="">
                            <input type="hidden" name="accion" value="agregar">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Nombre de la clase</label>
                                    <input type="text" name="nombre" class="form-control" placeholder="Ej. CrossFit" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Cupo máximo</label>
                                    <input type="number" name="cupo_maximo" class="form-control" placeholder="30" required>
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
                                <textarea name="descripcion" class="form-control" rows="2" placeholder="Descripción de la clase..."></textarea>
                            </div>
                            <div class="form-group">
                                <label>Entrenador</label>
                                <select name="id_entrenador" class="form-control" required>
                                    <option value="">Seleccione un entrenador</option>
                                    <?php foreach ($entrenadores as $entrenador): ?>
                                        <option value="<?= $entrenador['id_entrenador'] ?>"><?= htmlspecialchars($entrenador['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- La fecha de creación se genera automáticamente en el backend -->
                            <button type="submit" class="btn-add">Guardar Clase</button>
                        </form>
                    </div>
                </div>

                <!-- Tabla de Clases (AHORA CON FECHA) -->
                <table class="classes-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Cupo</th>
                            <th>Entrenador</th>
                            <th>Fecha Creación</th> <!-- NUEVA COLUMNA -->
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($clases) > 0): ?>
                            <?php foreach ($clases as $clase): ?>
                                <tr>
                                    <td><?= htmlspecialchars($clase['id_clase']) ?></td>
                                    <td><?= htmlspecialchars($clase['nombre']) ?></td>
                                    <td><?= htmlspecialchars($clase['descripcion']) ?></td>
                                    <td><?= htmlspecialchars($clase['cupo_maximo']) ?></td>
                                    <td><?= htmlspecialchars($clase['nombre_entrenador'] ?? 'Sin asignar') ?></td>
                                    <td>
                                        <?= isset($clase['fecha_creacion']) ? date('d/m/Y H:i', strtotime($clase['fecha_creacion'])) : '-' ?>
                                    </td>
                                    <td>
                                        <?php if ($clase['estado'] == 'activo'): ?>
                                            <span class="class-status status-active">Activo</span>
                                        <?php else: ?>
                                            <span class="class-status status-cancelled">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn-edit" data-toggle="modal" data-target="#modalEditar"
                                            onclick="cargarDatos(
                                                '<?= $clase['id_clase'] ?>',
                                                '<?= htmlspecialchars(addslashes($clase['nombre'])) ?>',
                                                '<?= htmlspecialchars(addslashes($clase['descripcion'])) ?>',
                                                '<?= $clase['cupo_maximo'] ?>',
                                                '<?= $clase['id_entrenador'] ?>',
                                                '<?= $clase['estado'] ?>',
                                                '<?= $clase['fecha_creacion'] ?? '' ?>'
                                            )">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?eliminar=<?= $clase['id_clase'] ?>" class="btn-delete" onclick="return confirm('¿Seguro que deseas eliminar esta clase?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center" style="color: var(--text-secondary);">No hay clases registradas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Editar Clase (también con fecha de solo lectura) -->
    <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Clase</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="actualizar">
                        <input type="hidden" name="id_clase" id="edit_id">

                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Cupo máximo</label>
                            <input type="number" name="cupo_maximo" id="edit_cupo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Entrenador</label>
                            <select name="id_entrenador" id="edit_entrenador" class="form-control" required>
                                <option value="">Seleccione un entrenador</option>
                                <?php foreach ($entrenadores as $entrenador): ?>
                                    <option value="<?= $entrenador['id_entrenador'] ?>"><?= htmlspecialchars($entrenador['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Fecha de creación (solo lectura, no se edita) -->
                        <div class="form-group">
                            <label>Fecha de creación</label>
                            <input type="text" id="edit_fecha" class="form-control" readonly disabled style="background: var(--bg-input); opacity: 0.7;">
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

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        function cargarDatos(id, nombre, descripcion, cupo, id_entrenador, estado, fecha) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_descripcion').value = descripcion;
            document.getElementById('edit_cupo').value = cupo;
            document.getElementById('edit_entrenador').value = id_entrenador;
            document.getElementById('edit_estado').value = estado;
            
            // Formatear fecha para mostrarla en el modal
            if (fecha && fecha != '') {
                let fechaObj = new Date(fecha);
                let fechaFormateada = fechaObj.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                document.getElementById('edit_fecha').value = fechaFormateada;
            } else {
                document.getElementById('edit_fecha').value = 'No disponible';
            }
        }
    </script>
</body>
</html>