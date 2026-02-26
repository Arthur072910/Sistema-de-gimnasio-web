<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/../controller/MiembroController.php';

$controller = new MiembroController();
$mensaje = '';
$error = '';
$show_modal = false;

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] == 'editar') {
            $resultado = $controller->actualizar($_POST);
            if ($resultado['success']) {
                $mensaje = $resultado['message'];
            } else {
                $error = $resultado['message'];
            }
        } elseif ($_POST['accion'] == 'cambiar_password') {
            $resultado = $controller->cambiarContraseña($_POST);
            if ($resultado['success']) {
                $mensaje = $resultado['message'];
            } else {
                $error = $resultado['message'];
            }
        } elseif ($_POST['accion'] == 'eliminar') {
            $resultado = $controller->eliminar($_POST['id_usuario']);
            if ($resultado['success']) {
                $mensaje = $resultado['message'];
            } else {
                $error = $resultado['message'];
            }
        }
    }
}

// Obtener lista de miembros
$miembros = $controller->listar();

// Obtener miembro para editar
$miembro_editar = null;
if (isset($_GET['editar'])) {
    $resultado = $controller->obtener($_GET['editar']);
    if ($resultado['success']) {
        $miembro_editar = $resultado['data'];
        $show_modal = true;
    }
}

// Obtener miembro para cambiar contraseña
$miembro_password = null;
$show_password_modal = false;
if (isset($_GET['password'])) {
    $resultado = $controller->obtener($_GET['password']);
    if ($resultado['success']) {
        $miembro_password = $resultado['data'];
        $show_password_modal = true;
    }
}

// Obtener estadísticas
$estadisticas = $controller->estadisticas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Miembros · Delux Gym Admin</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/sidebar-global.css">
    <link rel="stylesheet" href="../assets/css/miembros.css">
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
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $estadisticas['total'] ?? 0; ?></h3>
                    <p>Total miembros</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $estadisticas['por_rol']['administrador'] ?? 0; ?></h3>
                    <p>Administradores</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $estadisticas['por_rol']['recepcionista'] ?? 0; ?></h3>
                    <p>Recepcionistas</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $estadisticas['por_rol']['cliente'] ?? 0; ?></h3>
                    <p>Clientes</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $estadisticas['nuevos_mes'] ?? 0; ?></h3>
                    <p>Nuevos este mes</p>
                </div>
            </div>
        </div>

        <!-- Mensajes de éxito/error -->
        <?php if($mensaje): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo htmlspecialchars($mensaje); ?>
                <button type="button" class="close" data-dismiss="alert" onclick="cerrarModalYRecargar()">
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

        <!-- Tabla de miembros con buscador -->
        <div class="card-gym">
            <div class="card-header-gym">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <i class="fas fa-list mr-2"></i>
                        Miembros Registrados
                    </div>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="buscador" class="form-control-gym-search" placeholder="Buscar por nombre, email, rol...">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table-gym" id="tabla-miembros">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Teléfono</th>
                                <th>Fecha Nac.</th>
                                <th>Género</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-body">
                            <?php if(empty($miembros)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-users fa-3x mb-3" style="color: #333;"></i>
                                        <p>No hay miembros registrados</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($miembros as $miembro): ?>
                                <tr class="miembro-row">
                                    <td><?php echo $miembro['id_usuario']; ?></td>
                                    <td>
                                        <?php 
                                        $nombre_completo = trim(($miembro['nombre'] ?? '') . ' ' . ($miembro['apellido'] ?? ''));
                                        if(empty($nombre_completo)) {
                                            $nombre_completo = explode('@', $miembro['email'])[0];
                                        }
                                        ?>
                                        <strong><?php echo htmlspecialchars($nombre_completo); ?></strong>
                                        <?php if(!$miembro['id_cliente']): ?>
                                            <br><small class="text-muted staff-badge">Staff</small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($miembro['email'] ?? ''); ?></td>
                                    <td>
                                        <span class="badge-rol <?php echo $miembro['rol'] ?? 'cliente'; ?>">
                                            <?php 
                                                switch($miembro['rol'] ?? 'cliente') {
                                                    case 'administrador': echo '<i class="fas fa-crown mr-1"></i>Admin'; break;
                                                    case 'recepcionista': echo '<i class="fas fa-phone-alt mr-1"></i>Recep'; break;
                                                    default: echo '<i class="fas fa-user mr-1"></i>Cliente';
                                                }
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($miembro['telefono'] ?? '—'); ?></td>
                                    <td>
                                        <?php 
                                        if(!empty($miembro['fecha_nacimiento'])) {
                                            echo date('d/m/Y', strtotime($miembro['fecha_nacimiento']));
                                        } else {
                                            echo '—';
                                        }
                                        ?>
                                    </td>
                                    <td><?php 
                                        $genero = $miembro['genero'] ?? '—';
                                        if($genero == 'M') echo 'Masculino';
                                        elseif($genero == 'F') echo 'Femenino';
                                        elseif($genero == 'Otro') echo 'Otro';
                                        else echo '—';
                                    ?></td>
                                    <td>
                                        <span class="badge-estado <?php echo $miembro['estado'] ?? 'activo'; ?>">
                                            <?php echo ucfirst($miembro['estado'] ?? 'activo'); ?>
                                        </span>
                                    </td>
                                    <td class="acciones">
                                        <a href="?editar=<?php echo $miembro['id_usuario']; ?>" class="btn-action edit" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?password=<?php echo $miembro['id_usuario']; ?>" class="btn-action password" title="Cambiar contraseña">
                                            <i class="fas fa-key"></i>
                                        </a>
                                        <?php if($miembro['id_usuario'] != $_SESSION['usuario_id']): ?>
                                        <a href="#" onclick="confirmarEliminacion(<?php echo $miembro['id_usuario']; ?>)" class="btn-action delete" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Contador de resultados -->
                <div class="result-count mt-3" id="result-count">
                    Mostrando <span id="mostrando"><?php echo count($miembros); ?></span> de <?php echo count($miembros); ?> miembros
                </div>
            </div>
        </div>
    </main>

    <!-- Modal de edición -->
    <div class="modal fade <?php echo $show_modal ? 'show' : ''; ?>" id="editarModal" tabindex="-1" <?php echo $show_modal ? 'style="display: block; background: rgba(0,0,0,0.8);"' : ''; ?>>
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: #1a1a1a; border: 1px solid var(--gold);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border);">
                    <h5 class="modal-title" style="color: var(--gold);">
                        <i class="fas fa-user-edit mr-2"></i>
                        Editar Miembro
                    </h5>
                    <a href="miembros.php" class="close" style="color: var(--white);">
                        <span>&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <form method="POST" class="miembro-form" id="form-editar">
                        <input type="hidden" name="accion" value="editar">
                        <input type="hidden" name="id_usuario" value="<?php echo $miembro_editar['id_usuario'] ?? ''; ?>">
                        <input type="hidden" name="id_cliente" value="<?php echo $miembro_editar['id_cliente'] ?? ''; ?>">
                        
                        <div class="row">
                            <!-- Nombre -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user mr-2"></i>Nombre
                                </label>
                                <input type="text" name="nombre" class="form-control-gym" 
                                       value="<?php echo htmlspecialchars($miembro_editar['nombre'] ?? ''); ?>" required>
                            </div>
                            
                            <!-- Apellido -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user mr-2"></i>Apellido
                                </label>
                                <input type="text" name="apellido" class="form-control-gym" 
                                       value="<?php echo htmlspecialchars($miembro_editar['apellido'] ?? ''); ?>" required>
                            </div>
                            
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-envelope mr-2"></i>Email
                                </label>
                                <input type="email" name="email" class="form-control-gym" 
                                       value="<?php echo htmlspecialchars($miembro_editar['email'] ?? ''); ?>" required>
                            </div>
                            
                            <!-- Teléfono -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-phone mr-2"></i>Teléfono
                                </label>
                                <input type="text" name="telefono" class="form-control-gym" 
                                       value="<?php echo htmlspecialchars($miembro_editar['telefono'] ?? ''); ?>">
                            </div>
                            
                            <!-- Fecha Nacimiento -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-calendar mr-2"></i>Fecha Nacimiento
                                </label>
                                <input type="date" name="fecha_nacimiento" class="form-control-gym" 
                                       value="<?php echo $miembro_editar['fecha_nacimiento'] ?? ''; ?>">
                            </div>
                            
                            <!-- Género -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-venus-mars mr-2"></i>Género
                                </label>
                                <select name="genero" class="form-control-gym">
                                    <option value="">Seleccionar</option>
                                    <option value="M" <?php echo (isset($miembro_editar['genero']) && $miembro_editar['genero'] == 'M') ? 'selected' : ''; ?>>Masculino</option>
                                    <option value="F" <?php echo (isset($miembro_editar['genero']) && $miembro_editar['genero'] == 'F') ? 'selected' : ''; ?>>Femenino</option>
                                    <option value="Otro" <?php echo (isset($miembro_editar['genero']) && $miembro_editar['genero'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                                </select>
                            </div>
                            
                            <!-- Rol -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-tag mr-2"></i>Rol
                                </label>
                                <select name="rol" class="form-control-gym" required>
                                    <option value="cliente" <?php echo (isset($miembro_editar['rol']) && $miembro_editar['rol'] == 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                                    <option value="recepcionista" <?php echo (isset($miembro_editar['rol']) && $miembro_editar['rol'] == 'recepcionista') ? 'selected' : ''; ?>>Recepcionista</option>
                                    <option value="administrador" <?php echo (isset($miembro_editar['rol']) && $miembro_editar['rol'] == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                                </select>
                            </div>
                            
                            <!-- Estado -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-toggle-on mr-2"></i>Estado
                                </label>
                                <select name="estado" class="form-control-gym" required>
                                    <option value="activo" <?php echo (isset($miembro_editar['estado']) && $miembro_editar['estado'] == 'activo') ? 'selected' : ''; ?>>Activo</option>
                                    <option value="inactivo" <?php echo (isset($miembro_editar['estado']) && $miembro_editar['estado'] == 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="miembros.php" class="btn btn-secondary-gym mr-2">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn-gold-gym">
                                <i class="fas fa-save mr-2"></i>
                                Actualizar Miembro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de cambio de contraseña -->
    <div class="modal fade <?php echo $show_password_modal ? 'show' : ''; ?>" id="passwordModal" tabindex="-1" <?php echo $show_password_modal ? 'style="display: block; background: rgba(0,0,0,0.8);"' : ''; ?>>
        <div class="modal-dialog">
            <div class="modal-content" style="background: #1a1a1a; border: 1px solid var(--gold);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border);">
                    <h5 class="modal-title" style="color: var(--gold);">
                        <i class="fas fa-key mr-2"></i>
                        Cambiar Contraseña
                    </h5>
                    <a href="miembros.php" class="close" style="color: var(--white);">
                        <span>&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Cambiando contraseña para: <strong><?php echo htmlspecialchars($miembro_password['email'] ?? ''); ?></strong>
                    </p>
                    <form method="POST" class="password-form" id="form-password" onsubmit="return validarPassword()">
                        <input type="hidden" name="accion" value="cambiar_password">
                        <input type="hidden" name="id_usuario" value="<?php echo $miembro_password['id_usuario'] ?? ''; ?>">
                        
                        <div class="form-group mb-3">
                            <label class="form-label">
                                <i class="fas fa-lock mr-2"></i>Nueva Contraseña
                            </label>
                            <input type="password" name="nueva_contraseña" id="nueva_pass" class="form-control-gym" 
                                   placeholder="Mínimo 6 caracteres" minlength="6" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="form-label">
                                <i class="fas fa-lock mr-2"></i>Confirmar Contraseña
                            </label>
                            <input type="password" name="confirmar_contraseña" id="confirmar_pass" class="form-control-gym" 
                                   placeholder="Repite la contraseña" minlength="6" required>
                            <small class="text-muted" id="password-error" style="display: none; color: #dc3545 !important;">
                                Las contraseñas no coinciden
                            </small>
                        </div>

                        <div class="form-actions">
                            <a href="miembros.php" class="btn btn-secondary-gym mr-2">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn-gold-gym">
                                <i class="fas fa-save mr-2"></i>
                                Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario oculto para eliminar -->
    <form method="POST" id="form-eliminar" style="display: none;">
        <input type="hidden" name="accion" value="eliminar">
        <input type="hidden" name="id_usuario" id="eliminar-id">
    </form>

    <!-- Scripts -->
    <script>
        // Toggle sidebar
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        // Buscador en tiempo real
        document.getElementById('buscador').addEventListener('keyup', function() {
            let filtro = this.value.toLowerCase();
            let filas = document.querySelectorAll('#tabla-body .miembro-row');
            let contador = 0;
            
            filas.forEach(function(fila) {
                let texto = fila.textContent.toLowerCase();
                if (texto.indexOf(filtro) > -1) {
                    fila.style.display = '';
                    contador++;
                } else {
                    fila.style.display = 'none';
                }
            });
            
            document.getElementById('mostrando').textContent = contador;
        });

        // Validar contraseñas
        function validarPassword() {
            let pass = document.getElementById('nueva_pass').value;
            let confirm = document.getElementById('confirmar_pass').value;
            let error = document.getElementById('password-error');
            
            if (pass !== confirm) {
                error.style.display = 'block';
                return false;
            }
            return true;
        }

        // Confirmar eliminación
        function confirmarEliminacion(id) {
            if (confirm('¿Estás seguro de eliminar este miembro? Esta acción no se puede deshacer.')) {
                document.getElementById('eliminar-id').value = id;
                document.getElementById('form-eliminar').submit();
            }
        }

        // Cerrar modales con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.location.href = 'miembros.php';
            }
        });

        // Si hay mensaje de éxito, recargar después de 1 segundo
        <?php if($mensaje): ?>
        setTimeout(function() {
            window.location.href = 'miembros.php';
        }, 1500);
        <?php endif; ?>
    </script>

    <!-- Scripts Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>