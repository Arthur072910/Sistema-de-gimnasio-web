<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/../controller/ProductoController.php';

$controller = new ProductoController();
$mensaje = '';
$error = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] == 'agregar') {
        $resultado = $controller->agregar($_POST, $_FILES['imagen'] ?? null);
        if ($resultado['success']) {
            $mensaje = $resultado['message'];
        } else {
            $error = $resultado['message'];
        }
    } elseif ($_POST['accion'] == 'editar') {
        $resultado = $controller->actualizar($_POST['id_producto'], $_POST, $_FILES['imagen'] ?? null);
        if ($resultado['success']) {
            $mensaje = $resultado['message'];
        } else {
            $error = $resultado['message'];
        }
    } elseif ($_POST['accion'] == 'eliminar') {
        $resultado = $controller->eliminar($_POST['id_producto']);
        if ($resultado['success']) {
            $mensaje = $resultado['message'];
        } else {
            $error = $resultado['message'];
        }
    }
}

// Obtener lista de productos
$productos = $controller->listarTodos();

// Obtener producto para editar
$producto_editar = null;
if (isset($_GET['editar'])) {
    $producto_editar = $controller->obtenerPorId($_GET['editar']);
}

// Estadísticas
$total_productos = count($productos);
$stock_bajo = 0;
$total_valor = 0;
foreach($productos as $p) {
    if($p['stock'] < 10) $stock_bajo++;
    $total_valor += $p['precio'] * $p['stock'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos · Delux Gym Admin</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/sidebar-global.css">
    <link rel="stylesheet" href="../assets/css/registroproductos.css">
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
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $total_productos; ?></h3>
                    <p>Total productos</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $stock_bajo; ?></h3>
                    <p>Stock bajo (&lt;10)</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <h3>$<?php echo number_format($total_valor, 2); ?></h3>
                    <p>Valor inventario</p>
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
                <i class="fas <?php echo $producto_editar ? 'fa-edit' : 'fa-plus-circle'; ?> mr-2"></i>
                <?php echo $producto_editar ? 'Editar Producto' : 'Registrar Nuevo Producto'; ?>
            </div>
            <div class="card-body">
                <form method="POST" class="producto-form" enctype="multipart/form-data">
                    <input type="hidden" name="accion" value="<?php echo $producto_editar ? 'editar' : 'agregar'; ?>">
                    <?php if($producto_editar): ?>
                        <input type="hidden" name="id_producto" value="<?php echo $producto_editar['id_producto']; ?>">
                    <?php endif; ?>
                    
                    <div class="row">
                        <!-- Nombre del producto -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-tag mr-2"></i>
                                Nombre del producto
                            </label>
                            <input type="text" 
                                   name="nombre" 
                                   class="form-control-gym" 
                                   placeholder="Ej. Proteína Whey" 
                                   value="<?php echo $producto_editar ? htmlspecialchars($producto_editar['nombre']) : ''; ?>"
                                   required>
                        </div>

                        <!-- Categoría -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-list mr-2"></i>
                                Categoría
                            </label>
                            <select name="categoria" class="form-control-gym" required>
                                <option value="">Seleccionar categoría</option>
                                <option value="suplemento" <?php echo ($producto_editar && $producto_editar['categoria'] == 'suplemento') ? 'selected' : ''; ?>>Suplemento</option>
                                <option value="ropa" <?php echo ($producto_editar && $producto_editar['categoria'] == 'ropa') ? 'selected' : ''; ?>>Ropa</option>
                                <option value="equipo" <?php echo ($producto_editar && $producto_editar['categoria'] == 'equipo') ? 'selected' : ''; ?>>Equipo</option>
                            </select>
                        </div>

                        <!-- Precio -->
                        <div class="col-md-4 mb-3">
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
                                   value="<?php echo $producto_editar ? htmlspecialchars($producto_editar['precio']) : ''; ?>"
                                   required>
                        </div>

                        <!-- Stock -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-cubes mr-2"></i>
                                Stock
                            </label>
                            <input type="number" 
                                   name="stock" 
                                   class="form-control-gym" 
                                   min="0" 
                                   placeholder="0" 
                                   value="<?php echo $producto_editar ? htmlspecialchars($producto_editar['stock']) : ''; ?>"
                                   required>
                        </div>

                        <!-- Imagen (subida de archivo) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                <i class="fas fa-image mr-2"></i>
                                Imagen del producto
                            </label>
                            <div class="custom-file-upload">
                                <input type="file" 
                                       name="imagen" 
                                       id="imagen" 
                                       class="form-control-gym-file" 
                                       accept="image/jpeg,image/png,image/webp,image/gif">
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Formatos: JPG, PNG, WEBP, GIF (Max. 2MB)
                                </small>
                            </div>
                            <?php if($producto_editar && !empty($producto_editar['imagen_url'])): ?>
                                <div class="mt-2 d-flex align-items-center">
                                    <img src="<?php echo htmlspecialchars($producto_editar['imagen_url']); ?>" 
                                         alt="Imagen actual" 
                                         class="img-preview mr-3">
                                    <small class="text-muted">
                                        <i class="fas fa-check-circle text-success mr-1"></i>
                                        Imagen actual (subir nueva para reemplazar)
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Descripción -->
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                <i class="fas fa-align-left mr-2"></i>
                                Descripción
                            </label>
                            <textarea name="descripcion" 
                                      class="form-control-gym" 
                                      rows="3" 
                                      placeholder="Descripción del producto (opcional)"><?php echo $producto_editar ? htmlspecialchars($producto_editar['descripcion']) : ''; ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <?php if($producto_editar): ?>
                            <a href="registroproductos.php" class="btn btn-secondary-gym mr-2">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar edición
                            </a>
                        <?php endif; ?>
                        <button type="submit" class="btn-gold-gym">
                            <i class="fas <?php echo $producto_editar ? 'fa-save' : 'fa-plus-circle'; ?> mr-2"></i>
                            <?php echo $producto_editar ? 'Actualizar Producto' : 'Guardar Producto'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de productos existentes -->
        <div class="card-gym">
            <div class="card-header-gym">
                <i class="fas fa-list mr-2"></i>
                Productos Registrados
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table-gym">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($productos)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-box-open fa-3x mb-3" style="color: #333;"></i>
                                        <p>No hay productos registrados</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($productos as $producto): ?>
                                <tr>
                                    <td><?php echo $producto['id_producto']; ?></td>
                                    <td>
                                        <?php if(!empty($producto['imagen_url'])): ?>
                                            <img src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                                                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                                 class="img-thumbnail">
                                        <?php else: ?>
                                            <div class="img-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>
                                        <?php if(!empty($producto['descripcion'])): ?>
                                            <br><small class="text-muted"><?php echo substr(htmlspecialchars($producto['descripcion']), 0, 50); ?><?php echo strlen($producto['descripcion']) > 50 ? '...' : ''; ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge-categoria <?php echo $producto['categoria']; ?>">
                                            <?php echo ucfirst($producto['categoria']); ?>
                                        </span>
                                    </td>
                                    <td class="text-warning font-weight-bold">$<?php echo number_format($producto['precio'], 2); ?></td>
                                    <td>
                                        <span class="stock-badge <?php echo $producto['stock'] < 10 ? 'stock-bajo' : 'stock-normal'; ?>">
                                            <?php echo $producto['stock']; ?> uni.
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-categoria <?php echo $producto['estado']; ?>">
                                            <?php echo ucfirst($producto['estado']); ?>
                                        </span>
                                    </td>
                                    <td class="acciones">
                                        <a href="?editar=<?php echo $producto['id_producto']; ?>" class="btn-action edit" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                            <input type="hidden" name="accion" value="eliminar">
                                            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
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