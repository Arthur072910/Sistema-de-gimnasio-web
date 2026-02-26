<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/../controller/PlanController.php';

$controller = new PlanController();
$mensaje = '';
$error = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] == 'agregar') {
        $resultado = $controller->agregar($_POST);
        if ($resultado['success']) {
            $mensaje = $resultado['message'];
        } else {
            $error = $resultado['message'];
        }
    } elseif ($_POST['accion'] == 'editar') {
        $resultado = $controller->actualizar($_POST);
        if ($resultado['success']) {
            $mensaje = $resultado['message'];
        } else {
            $error = $resultado['message'];
        }
    } elseif ($_POST['accion'] == 'eliminar') {
        $resultado = $controller->eliminar($_POST['id_tipo_membresia']);
        if ($resultado['success']) {
            $mensaje = $resultado['message'];
        } else {
            $error = $resultado['message'];
        }
    }
}

// Obtener lista de planes
$planes = $controller->listar();

// Obtener plan para editar
$plan_editar = null;
if (isset($_GET['editar'])) {
    $resultado = $controller->obtener($_GET['editar']);
    if ($resultado['success']) {
        $plan_editar = $resultado['data'];
    }
}

// Estadísticas
$total_planes = count($planes);
$precio_promedio = 0;
$duracion_promedio = 0;
if ($total_planes > 0) {
    $suma_precios = 0;
    $suma_duracion = 0;
    foreach($planes as $p) {
        $suma_precios += $p['precio'];
        $suma_duracion += $p['duracion_dias'];
    }
    $precio_promedio = $suma_precios / $total_planes;
    $duracion_promedio = $suma_duracion / $total_planes;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Planes · Delux Gym Admin</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/sidebar-global.css">
    <link rel="stylesheet" href="../assets/css/planes.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include dirname(__DIR__) . '/layout/siderbar.php'; ?>

    <!-- Botón para móvil -->
    <button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Contenido principal -->
    <main class="main-content">
        
        <!-- Header con estadísticas -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $total_planes; ?></h3>
                    <p>Total planes</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <h3>$<?php echo number_format($precio_promedio, 2); ?></h3>
                    <p>Precio promedio</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo round($duracion_promedio); ?> días</h3>
                    <p>Duración promedio</p>
                </div>
            </div>
        </div>

        <!-- Mensajes de éxito/error -->
        <?php if($mensaje): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo htmlspecialchars($mensaje); ?>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Formulario de registro/edición -->
        <div class="card-gym mb-4">
            <div class="card-header-gym">
                <i class="fas <?php echo $plan_editar ? 'fa-edit' : 'fa-plus-circle'; ?> mr-2"></i>
                <?php echo $plan_editar ? 'Editar Plan' : 'Registrar Nuevo Plan'; ?>
            </div>
            <div class="card-body">
                <form method="POST" class="plan-form">
                    <input type="hidden" name="accion" value="<?php echo $plan_editar ? 'editar' : 'agregar'; ?>">
                    <?php if($plan_editar): ?>
                        <input type="hidden" name="id_tipo_membresia" value="<?php echo $plan_editar['id_tipo_membresia']; ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <!-- Nombre del plan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-tag mr-2"></i>
                                Nombre del plan
                            </label>
                            <input type="text" 
                                   name="nombre" 
                                   class="form-control-gym" 
                                   placeholder="Ej. Plan Básico" 
                                   value="<?php echo $plan_editar ? htmlspecialchars($plan_editar['nombre']) : ''; ?>"
                                   required>
                        </div>

                        <!-- Precio -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">
                                <i class="fas fa-dollar-sign mr-2"></i>
                                Precio ($)
                            </label>
                            <input type="number" 
                                   name="precio" 
                                   class="form-control-gym" 
                                   step="0.01" 
                                   min="0.01" 
                                   placeholder="0.00" 
                                   value="<?php echo $plan_editar ? htmlspecialchars($plan_editar['precio']) : ''; ?>"
                                   required>
                        </div>

                        <!-- Duración (días) -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Duración (días)
                            </label>
                            <input type="number" 
                                   name="duracion_dias" 
                                   class="form-control-gym" 
                                   min="1" 
                                   placeholder="30" 
                                   value="<?php echo $plan_editar ? htmlspecialchars($plan_editar['duracion_dias']) : ''; ?>"
                                   required>
                        </div>

                        <!-- Estado -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-toggle-on mr-2"></i>
                                Estado
                            </label>
                            <select name="estado" class="form-control-gym" required>
                                <option value="activo" <?php echo ($plan_editar && $plan_editar['estado'] == 'activo') ? 'selected' : ''; ?>>Activo</option>
                                <option value="inactivo" <?php echo ($plan_editar && $plan_editar['estado'] == 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                        </div>

                        <!-- Descripción -->
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-align-left mr-2"></i>
                                Descripción
                            </label>
                            <textarea name="descripcion" 
                                      class="form-control-gym" 
                                      rows="4" 
                                      placeholder="Describe los beneficios y características del plan"><?php echo $plan_editar ? htmlspecialchars($plan_editar['descripcion']) : ''; ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <?php if($plan_editar): ?>
                            <a href="planes.php" class="btn btn-secondary-gym mr-2">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar edición
                            </a>
                        <?php endif; ?>
                        <button type="submit" class="btn-gold-gym">
                            <i class="fas <?php echo $plan_editar ? 'fa-save' : 'fa-plus-circle'; ?> mr-2"></i>
                            <?php echo $plan_editar ? 'Actualizar Plan' : 'Guardar Plan'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de planes existentes -->
        <div class="card-gym">
            <div class="card-header-gym">
                <i class="fas fa-list mr-2"></i>
                Planes Registrados
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table-gym">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Duración</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($planes)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-tags fa-3x mb-3" style="color: #333;"></i>
                                        <p>No hay planes registrados</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($planes as $plan): ?>
                                <tr>
                                    <td><?php echo $plan['id_tipo_membresia']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($plan['nombre']); ?></strong>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo htmlspecialchars(substr($plan['descripcion'], 0, 100)); ?><?php echo strlen($plan['descripcion']) > 100 ? '...' : ''; ?></small>
                                    </td>
                                    <td class="text-warning font-weight-bold">$<?php echo number_format($plan['precio'], 2); ?></td>
                                    <td>
                                        <span class="badge-duracion">
                                            <?php echo $plan['duracion_dias']; ?> días
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-estado <?php echo $plan['estado']; ?>">
                                            <?php echo ucfirst($plan['estado']); ?>
                                        </span>
                                    </td>
                                    <td class="acciones">
                                        <a href="?editar=<?php echo $plan['id_tipo_membresia']; ?>" class="btn-action edit" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este plan?');">
                                            <input type="hidden" name="accion" value="eliminar">
                                            <input type="hidden" name="id_tipo_membresia" value="<?php echo $plan['id_tipo_membresia']; ?>">
                                            <button type="submit" class="btn-action delete" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Script para toggle sidebar en móvil -->
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }
    </script>

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>