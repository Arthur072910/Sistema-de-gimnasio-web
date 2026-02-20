<?php
session_start();

if(!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!doctype html>
<html lang="es">
<head>
    <title>Mi Carrito - Delux Gym</title>
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
    <link rel="stylesheet" href="../assets/css/carrito.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>
<body>
    <?php include "../layout/header.php"; ?>

    <div id="content-wrapper">
        <div class="container pb-5">
            
            <!-- Título -->
            <h2 class="pt-4">
                <i class="fas fa-shopping-cart"></i> 
                Tu Carrito
            </h2>
            
            <!-- Tabla del carrito -->
            <div class="table-responsive">
                <table class="table table-hover mt-4">
                    <thead class="thead-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="lista-carrito">
                        <!-- Aquí se cargarán los productos con JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <!-- Total y botones -->
            <div class="total-section">
                <div class="total-card">
                    <h4 id="gran-total">$0.00</h4>
                    <div class="botones-accion">
                        <a href="productos.php" class="btn-outline-light">SEGUIR COMPRANDO</a>
                        <a href="pago.php" class="btn-pagar">PROCEDER AL PAGO</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include "../layout/footer.php"; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="../assets/js/carrito.js"></script>
</body>
</html>