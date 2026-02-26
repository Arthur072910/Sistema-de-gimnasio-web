<?php
session_start();

// Verificar si el usuario está logueado
if(!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Cargar productos desde la base de datos
require_once __DIR__ . '/../controller/ProductoController.php';
$productoController = new ProductoController();
$todos_los_productos = $productoController->listarTodos();

// Separar productos por categoría
$suplementos = array_filter($todos_los_productos, function($p) {
    return $p['categoria'] === 'suplemento';
});

$accesorios = array_filter($todos_los_productos, function($p) {
    return $p['categoria'] === 'equipo' || $p['categoria'] === 'ropa';
});

// Función para mostrar tarjetas de productos
function mostrarProductos($productos) {
    if (empty($productos)) {
        echo '<div class="col-12 text-center py-5">
                <i class="fas fa-box-open fa-4x mb-3" style="color: #333;"></i>
                <p class="text-muted">No hay productos disponibles en esta categoría</p>
              </div>';
        return;
    }
    
    foreach($productos as $producto): ?>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow">
                <?php if(!empty($producto['imagen_url'])): ?>
                    <img class="card-img-custom" src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                         alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                <?php else: ?>
                    <div class="card-img-custom" style="background: #1a1a1a; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-image fa-3x" style="color: #333;"></i>
                    </div>
                <?php endif; ?>
                
                <div class="card-body text-center">
                    <h4 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h4>
                    <p class="card-text"><?php echo htmlspecialchars($producto['descripcion'] ?? 'Sin descripción'); ?></p>
                    <h5 class="font-weight-bold">$<?php echo number_format($producto['precio'], 2); ?></h5>
                    <small class="text-muted">Stock: <?php echo $producto['stock']; ?> unidades</small>
                </div>
                
                <div class="card-footer bg-transparent border-0">
                    <button class="btn btn-warning btn-block font-weight-bold btn-comprar" 
                            data-id="<?php echo $producto['id_producto']; ?>"
                            data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
                            data-precio="<?php echo $producto['precio']; ?>">
                        <i class="fas fa-cart-plus mr-2"></i>COMPRAR
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Delux Gym - Tienda</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700,800" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/tienda.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>
<body>
    <?php include "../layout/header.php"; ?>

    <!-- SECCIÓN: SUPLEMENTOS Y NUTRICIÓN -->
    <div class="contenedor-texto">
        <h1 class="texto">Suplementos y Nutrición</h1>
    </div>
    
    <div class="container my-5">
        <div class="row justify-content-center">
            <?php mostrarProductos($suplementos); ?>
        </div>
    </div>

    <!-- SECCIÓN: ACCESORIOS Y EQUIPO -->
    <div class="contenedor-texto">
        <h1 class="texto">Accesorios y Equipo</h1>
    </div>
    
    <div class="container my-5">
        <div class="row justify-content-center">
            <?php mostrarProductos($accesorios); ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include "../layout/footer.php"; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="../assets/js/carrito.js"></script>
    
    <!-- Script para manejar compras -->
    <script>
    document.querySelectorAll('.btn-comprar').forEach(button => {
        button.addEventListener('click', function() {
            const producto = {
                id: this.dataset.id,
                nombre: this.dataset.nombre,
                precio: parseFloat(this.dataset.precio)
            };
            
            // Aquí puedes agregar la lógica del carrito
            console.log('Producto agregado:', producto);
            
            // Ejemplo: mostrar notificación
            alert(`✅ ${producto.nombre} agregado al carrito`);
            
            // Aquí puedes llamar a tu función de carrito.js
            // agregarAlCarrito(producto);
        });
    });
    </script>
</body>
</html>