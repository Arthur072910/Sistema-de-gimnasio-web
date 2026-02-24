

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['id_membresia'] = $_POST['id_tipo_membresia'];
    $_SESSION['precio_plan'] = $_POST['precio'];

    require_once "../config/database.php";
    $database = new Database();
    $conn = $database->getConnection();

    $sql = "SELECT nombre FROM tipo_membresia WHERE id_tipo_membresia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_POST['id_tipo_membresia']]);
    $plan = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['nombre_plan'] = $plan['nombre'];

    header("Location: pago.php");
    exit();
}


require_once "../config/database.php";

$database = new Database();
$conn = $database->getConnection();

$sql = "SELECT * FROM tipo_membresia WHERE estado = 'activo'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$planes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
    <title>Mi Plan - Delux Gym</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
   
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
   
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700,800" rel="stylesheet">
 
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/plan.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>
<body>
    <?php include "../layout/header.php"; ?>

    <div id="content-wrapper">
        <div class="container pb-5">
            
            
            <h2 class="pt-4">
                <i class="fas fa-shopping-cart"></i> 
                Planes
            </h2>
            
            
            <div class="table-responsive">
                <table class="table table-hover mt-4">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nombre</th>
                            <th><Data>Descripción</Data></th>
                            <th>Precio</th>
                            <th>Duración</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
<?php foreach($planes as $plan): ?>
<tr>
    <td><?= $plan['nombre'] ?></td>
    <td><?= $plan['descripcion'] ?></td>
    <td>$<?= $plan['precio'] ?></td>
    <td><?= $plan['duracion_dias'] ?> días</td>
    <td>
        <form method="POST" action="">
            <input type="hidden" name="id_tipo_membresia" value="<?= $plan['id_tipo_membresia'] ?>">
            <input type="hidden" name="precio" value="<?= $plan['precio'] ?>">
            <button type="submit" class="btn btn-pagar">
                Seleccionar
            </button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
                </table>
            </div>
            
            
            

        </div>
    </div>

    <?php include "../layout/footer.php"; ?>

    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="../assets/js/carrito.js"></script>
</body>
</html>