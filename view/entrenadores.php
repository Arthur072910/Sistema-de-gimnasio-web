<?php
require_once __DIR__ . '/../controller/EntrenadorController.php';
$controller = new EntrenadorController();

// Procesar formularios
$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'agregar':
                $resultado = $controller->agregar($_POST);
                $mensaje = $resultado['message'];
                $tipo_mensaje = $resultado['success'] ? 'success' : 'error';
                break;
                
            case 'actualizar':
                $resultado = $controller->actualizar($_POST);
                $mensaje = $resultado['message'];
                $tipo_mensaje = $resultado['success'] ? 'success' : 'error';
                break;
        }
    }
}

// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $resultado = $controller->eliminar($_GET['eliminar']);
    $mensaje = $resultado['message'];
    $tipo_mensaje = $resultado['success'] ? 'success' : 'error';
}

$entrenadores = $controller->listar();
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Gestión de Entrenadores | DeluxGym</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/sidebar-global.css">
    <link rel="stylesheet" href="../assets/css/dashboard-global.css">
    <link rel="stylesheet" href="../assets/css/entrenadores.css">
</head>
<body>

    <?php include __DIR__ . '/../layout/siderbar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/../layout/dashboard.php'; ?>
        
        <header class="header-section mb-4">
            <h1 class="title-gym">Gestión de Entrenadores</h1>
            <p class="subtitle-gym">Administra el personal técnico de DeluxGym</p>
        </header>

        <?php if ($mensaje): ?>
            <div class="alert alert-<?= $tipo_mensaje ?>" style="margin: 0 0 20px 0; padding: 12px 20px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; <?= $tipo_mensaje == 'success' ? 'background-color: #d4edda; color: #155724; border-left: 4px solid #28a745;' : 'background-color: #f8d7da; color: #721c24; border-left: 4px solid #dc3545;' ?>">
                <span>
                    <i class="fas <?= $tipo_mensaje == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-2"></i>
                    <?= htmlspecialchars($mensaje) ?>
                </span>
                <button type="button" class="close" onclick="this.parentElement.style.display='none'" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
            </div>
        <?php endif; ?>

        <div class="container-fluid">
            <div class="row">
                <!-- Formulario de Registro -->
                <div class="col-lg-4">
                    <section class="card-gym mb-4">
                        <div class="card-body">
                            <h4 class="card-title-gym"><i class="fas fa-user-plus"></i> Nuevo Entrenador</h4>
                            <form method="POST" action="">
                                <input type="hidden" name="accion" value="agregar">
                                <div class="form-group">
                                    <label>Nombre completo</label>
                                    <input type="text" name="nombre" class="form-control-gym" placeholder="Ej. Juan Pérez" required>
                                </div>
                                <div class="form-group">
                                    <label>Especialidad</label>
                                    <input type="text" name="especialidad" class="form-control-gym" placeholder="Ej. CrossFit" required>
                                </div>
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" name="telefono" class="form-control-gym" placeholder="7000-0000">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control-gym" placeholder="entrenador@email.com" required>
                                </div>
                                <div class="form-group">
                                    <label>Fecha de Registro</label>
                                    <input type="date" name="fecha_registro" class="form-control-gym" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <button type="submit" class="btn-gold-gym btn-block">
                                    <i class="fas fa-save mr-2"></i>Registrar Entrenador
                                </button>
                            </form>
                        </div>
                    </section>
                </div>

                <!-- Lista de Entrenadores -->
                <div class="col-lg-8">
                    <section class="card-gym">
                        <div class="card-body">
                            <h4 class="card-title-gym"><i class="fas fa-list"></i> Personal Activo (<?= count($entrenadores) ?>)</h4>
                            <div class="table-responsive">
                                <table class="table table-gym">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre / Contacto</th>
                                            <th>Especialidad</th>
                                            <th>Teléfono</th>
                                            <th>Fecha Registro</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($entrenadores)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <i class="fas fa-user-slash fa-2x mb-2" style="color: #666;"></i>
                                                    <p style="color: #999;">No hay entrenadores registrados</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach($entrenadores as $e): ?>
                                            <tr>
                                                <td><span class="badge badge-dark">#<?= $e['id_entrenador'] ?></span></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($e['nombre']); ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-envelope mr-1"></i><?= htmlspecialchars($e['email']); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge-specialty">
                                                        <i class="fas fa-dumbbell mr-1"></i><?= htmlspecialchars($e['especialidad']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($e['telefono'])): ?>
                                                        <i class="fas fa-phone mr-1"></i><?= htmlspecialchars($e['telefono']); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    <?= date('d/m/Y', strtotime($e['fecha_registro'])); ?>
                                                </td>
                                                <td>
                                                    <button class="btn-action edit" 
                                                            onclick="cargarDatos(
                                                                '<?= $e['id_entrenador']; ?>',
                                                                '<?= addslashes($e['nombre']); ?>',
                                                                '<?= addslashes($e['especialidad']); ?>',
                                                                '<?= addslashes($e['telefono'] ?? ''); ?>',
                                                                '<?= addslashes($e['email']); ?>',
                                                                '<?= $e['fecha_registro']; ?>'
                                                            )" 
                                                            data-toggle="modal" 
                                                            data-target="#modalEditar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <a href="?eliminar=<?= $e['id_entrenador']; ?>" 
                                                       class="btn-action delete" 
                                                       onclick="return confirm('¿Estás seguro de eliminar este entrenador?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>

    <!-- MODAL DE EDICIÓN -->
    <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: #1a1a1a; border: 1px solid #333; border-radius: 15px;">
                <div class="modal-header" style="border-bottom: 1px solid #333;">
                    <h5 class="modal-title" style="color: #ffd700;">
                        <i class="fas fa-edit mr-2"></i>Editar Entrenador
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">
                        <span>&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="actualizar">
                        <input type="hidden" name="id_entrenador" id="edit_id">
                        
                        <div class="form-group">
                            <label style="color: #fff;">Nombre completo</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control-gym" required>
                        </div>
                        
                        <div class="form-group">
                            <label style="color: #fff;">Especialidad</label>
                            <input type="text" name="especialidad" id="edit_especialidad" class="form-control-gym" required>
                        </div>
                        
                        <div class="form-group">
                            <label style="color: #fff;">Teléfono</label>
                            <input type="text" name="telefono" id="edit_telefono" class="form-control-gym">
                        </div>
                        
                        <div class="form-group">
                            <label style="color: #fff;">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control-gym" required>
                        </div>
                        
                        <div class="form-group">
                            <label style="color: #fff;">Fecha de Registro</label>
                            <input type="date" name="fecha_registro" id="edit_fecha" class="form-control-gym" required>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #333;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #333; border: none;">
                            <i class="fas fa-times mr-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn-gold-gym">
                            <i class="fas fa-save mr-1"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    <script src="../assets/js/entrenadores.js"></script>
</body>
</html>