<!doctype html>
<html lang="es">
<head>
    <title>Mi Carrito - Delux Gym</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/carrito.css">
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/index.css">

</head>

<body>
    <?php include "../view/layout/header.php"; ?>



    <div class="container my-5">
        <h2><i class="fas fa-shopping-cart"></i> Tu Carrito</h2>
        <div class="table-responsive">
            <table class="table table-hover mt-4">
                <thead class="thead-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th> </tr>
                </thead>
                <tbody id="lista-carrito">
                    </tbody>
            </table>
        </div>
        
        <div class="row justify-content-end">
            <div class="col-md-4 text-right">
                <h4 id="gran-total">Total: $0.00</h4>
                <br>
                <a href="productos.php" class="btn btn-outline-secondary">Seguir Comprando</a>
                <a href="pago.php" class="btn btn-success">PROCEDER AL PAGO</a>
            </div>
        </div>
    </div>




    <?php include "../view/layout/footer.php"; ?>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    
</body>
<script src="../assets/js/carrito.js"></script>
</html>