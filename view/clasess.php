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
$entrenadores = $controller->listarEntrenadores(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clases | DeluxGym</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar-global.css">
    <link rel="stylesheet" href="../assets/css/dashboard-global.css"> <link rel="stylesheet" href="../assets/css/clasess.css">
    <link rel="stylesheet" href="../assets/css/clasess.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div style="display: flex">
        
        <?php include dirname(__DIR__) . '/layout/siderbar.php'; ?>            

        <div class="main-content" style="flex: 1;">
            <?php 
                $dashboard_path = realpath(__DIR__ . '/../layout/dashboard.php');
                if ($dashboard_path) {
                    include $dashboard_path;
                }
            ?>
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
                            <button type="submit" class="btn-add">Guardar Clase</button>
                        </form>
                    </div>
                </div>

                <table class="classes-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Cupo</th>
                            <th>Entrenador</th>
                            <th>Fecha Creación</th>
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
                                    <td><?= isset($clase['fecha_creacion']) ? date('d/m/Y H:i', strtotime($clase['fecha_creacion'])) : '-' ?></td>
                                    <td>
                                        <span class="class-status <?= ($clase['estado'] == 'activo') ? 'status-active' : 'status-cancelled' ?>">
                                            <?= ucfirst($clase['estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn-edit" data-toggle="modal" data-target="#modalEditar"
                                            onclick="cargarDatos('<?= $clase['id_clase'] ?>', '<?= addslashes($clase['nombre']) ?>', '<?= addslashes($clase['descripcion']) ?>', '<?= $clase['cupo_maximo'] ?>', '<?= $clase['id_entrenador'] ?>', '<?= $clase['estado'] ?>', '<?= $clase['fecha_creacion'] ?? '' ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?eliminar=<?= $clase['id_clase'] ?>" class="btn-delete" onclick="return confirm('¿Seguro?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center">No hay clases registradas</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
            if (fecha) {
                document.getElementById('edit_fecha').value = new Date(fecha).toLocaleDateString('es-ES');
            }
        }
    </script>
</body>
</html>