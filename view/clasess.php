<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../controller/ClasesController.php';

$controller = new ClasesController();
$mensaje = '';
$tipo_mensaje = '';

// AGREGAR
if (isset($_POST['accion']) && $_POST['accion'] == 'agregar') {
    $resultado = $controller->agregar($_POST);
    $mensaje = $resultado['message'];
    $tipo_mensaje = $resultado['success'] ? 'success' : 'error';
}

// ACTUALIZAR
if (isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    $resultado = $controller->actualizar($_POST);
    $mensaje = $resultado['message'];
    $tipo_mensaje = $resultado['success'] ? 'success' : 'error';
}

// ELIMINAR
if (isset($_GET['eliminar'])) {
    $resultado = $controller->eliminar($_GET['eliminar']);
    $mensaje = $resultado['message'];
    $tipo_mensaje = $resultado['success'] ? 'success' : 'error';
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
    <link rel="stylesheet" href="../assets/css/dashboard-global.css">
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
                <h1>Gestión de Clases</h1>
                <div class="admin-profile">
                    <i class="fas fa-bell"></i>
                    <div class="admin-avatar">AD</div>
                </div>
            </div>

            <?php if ($mensaje): ?>
                <div class="alert alert-<?= $tipo_mensaje ?>">
                    <span>
                        <i class="fas <?= $tipo_mensaje == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-2"></i>
                        <?= htmlspecialchars($mensaje) ?>
                    </span>
                    <button type="button" class="close" onclick="this.parentElement.style.display='none'">&times;</button>
                </div>
            <?php endif; ?>

            <div class="classes-section">
                <div class="section-header">
                    <h3 class="chart-title">Gestión de Clases</h3>
                </div>

                <!-- Formulario de Registro -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title" style="color: var(--accent-gold);">Agregar Nueva Clase</h5>
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
                            <button type="submit" class="btn-add">
                                <i class="fas fa-save mr-2"></i>Guardar Clase
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tabla de Clases -->
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
                                    <td><strong><?= htmlspecialchars($clase['nombre']) ?></strong></td>
                                    <td><?= htmlspecialchars($clase['descripcion'] ?: '-') ?></td>
                                    <td><span class="badge-specialty"><?= htmlspecialchars($clase['cupo_maximo']) ?> personas</span></td>
                                    <td>
                                        <i class="fas fa-user mr-1"></i>
                                        <?= htmlspecialchars($clase['nombre_entrenador'] ?? 'Sin asignar') ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        <?= isset($clase['fecha_creacion']) ? date('d/m/Y H:i', strtotime($clase['fecha_creacion'])) : '-' ?>
                                    </td>
                                    <td>
                                        <span class="class-status <?= ($clase['estado'] == 'activo') ? 'status-active' : 'status-cancelled' ?>">
                                            <?= ucfirst($clase['estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn-edit" style="background: none; border: none; color: var(--accent-gold); font-size: 1.2rem; margin-right: 10px;"
                                                data-toggle="modal" data-target="#modalEditar"
                                                onclick="cargarDatos('<?= $clase['id_clase'] ?>', 
                                                    '<?= addslashes($clase['nombre']) ?>', 
                                                    '<?= addslashes($clase['descripcion']) ?>', 
                                                    '<?= $clase['cupo_maximo'] ?>', 
                                                    '<?= $clase['id_entrenador'] ?>', 
                                                    '<?= $clase['estado'] ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?eliminar=<?= $clase['id_clase'] ?>" class="btn-delete" style="color: #ff4444; font-size: 1.2rem;" onclick="return confirm('¿Estás seguro de eliminar esta clase?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="text-center py-4">No hay clases registradas</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL DE EDICIÓN -->
    <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit mr-2"></i>Editar Clase
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">
                        <span>&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="actualizar">
                        <input type="hidden" name="id_clase" id="edit_id">
                        
                        <div class="form-group">
                            <label>Nombre de la clase</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="2"></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Cupo máximo</label>
                                <input type="number" name="cupo_maximo" id="edit_cupo" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Estado</label>
                                <select name="estado" id="edit_estado" class="form-control">
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #333; border: none;">
                            <i class="fas fa-times mr-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn-add">
                            <i class="fas fa-save mr-1"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    <script src="../assets/js/clasess.js"></script>
</body>
</html>