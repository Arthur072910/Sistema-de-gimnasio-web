<?php
session_start();
require_once "../config/database.php";

$database = new Database();
$conn = $database->getConnection();


if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}


if (!isset($_SESSION['id_membresia'])) {
    header("Location: plan.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['procesar_pago'])) {

    $id_cliente   = $_SESSION['cliente_id'];
    $id_tipo      = $_SESSION['id_membresia']; 
    $metodo_pago  = $_POST['metodo_pago'];
    $monto        = $_SESSION['precio_plan'];

    try {

       
        $conn->beginTransaction();

      
        $fecha_inicio = date("Y-m-d");
        $fecha_fin    = date("Y-m-d", strtotime("+30 days"));
        $codigo_qr    = uniqid('GYM-' . $id_cliente . '-');

        $sqlM = "INSERT INTO membresias 
                (id_cliente, id_tipo_membresia, fecha_inicio, fecha_vencimiento, codigo_qr, estado)
                VALUES (?, ?, ?, ?, ?, 'activa')";

        $stmtM = $conn->prepare($sqlM);
        $stmtM->execute([
            $id_cliente,
            $id_tipo,
            $fecha_inicio,
            $fecha_fin,
            $codigo_qr
        ]);

        
        $id_membresia_real = $conn->lastInsertId();
       
$codigo_qr = str_pad($id_membresia_real, 12, "0", STR_PAD_LEFT);


$update = $conn->prepare("UPDATE membresias SET codigo_qr = ? WHERE id_membresia = ?");
$update->execute([$codigo_qr, $id_membresia_real]);


      
        $sqlP = "INSERT INTO pagos 
                (id_cliente, id_membresia, monto_total, metodo_pago, estado_pago, tipo_transaccion, fecha_pago)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $stmtP = $conn->prepare($sqlP);
        $stmtP->execute([
            $id_cliente,
            $id_membresia_real,
            $monto,
            $metodo_pago,
            'pagado',
            'membresia'
        ]);

        $conn->commit();

        
        unset($_SESSION['id_membresia']);
        unset($_SESSION['nombre_plan']);
        unset($_SESSION['precio_plan']);

        echo "<script>
            alert('Pago realizado correctamente 🎉');
            window.location='plan.php';
        </script>";
        exit();

    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error al procesar el pago: " . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <title>Finalizar Compra - Delux Gym</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
   
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/pago.css">
</head>
<body>
    
    <nav class="navbar navbar-expand" id="navegacion">
        <img src="../assets/img/logo_deluxGym.png" alt="Logo" width="50" height="auto" id="logo">
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="productos.php">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>
            </ul>
        </div>
    </nav>

    <div class="container py-4">
        
        <h3 class="mb-4"><i class="fas fa-credit-card mr-2"></i>Finalizar Compra</h3>

        <div class="row">
            
            <div class="col-lg-8 mb-4">
                <div class="card pago-card">
                    <div class="card-body">
                       
                        <div class="metodos-pago-simple mb-4">
                            <div class="row">
                                <div class="col-4">
                                    <div class="metodo-item active" onclick="cambiarMetodo('tarjeta')">
                                        <i class="fas fa-credit-card"></i>
                                        <span>Tarjeta</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="metodo-item" onclick="cambiarMetodo('paypal')">
                                        <i class="fab fa-paypal"></i>
                                        <span>PayPal</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="metodo-item" onclick="cambiarMetodo('efectivo')">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span>Efectivo</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                       
                        <div id="metodo-tarjeta" class="metodo-panel active">

    <form method="POST" action="">

        <input type="hidden" name="procesar_pago" value="1">
        <input type="hidden" name="metodo_pago" value="Tarjeta">
        <input type="hidden" name="tipo_transaccion" value="membresia">

        <div class="form-group">
            <label>Número de tarjeta</label>
            <input type="text" class="form-control" name="numero_tarjeta" placeholder="1234 5678 9012 3456" required>
        </div>
        
       

       

        <button type="submit" class="btn-pago btn-block mt-4">
    <i class="fas fa-lock mr-2"></i>
    PAGAR $<?= $_SESSION['precio_plan'] ?? '0.00' ?>
</button>

    </form>
</div>

                       
                        <div id="metodo-paypal" class="metodo-panel">
                            <div class="text-center py-4">
                                <i class="fab fa-paypal" style="font-size: 60px; color: #003087;"></i>
                                <h5 class="mt-3">Pagar con PayPal</h5>
                                <p class="text-muted small">Serás redirigido a PayPal para completar el pago</p>
                                
                                <button class="btn-paypal btn-block mt-3" onclick="pagarPayPal()">
                                    <i class="fab fa-paypal mr-2"></i>
                                    CONTINUAR CON PAYPAL
                                </button>
                            </div>
                        </div>

                        
                        <div id="metodo-efectivo" class="metodo-panel">
                            <div class="alert alert-info py-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                Genera tu factura y paga en recepción con tu DUI
                            </div>

                            <h6 class="mb-3">Datos para factura</h6>
                            
                            
                            <div class="form-group">
                                <label>DUI <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="dui" placeholder="00000000-0" required>
                                <small class="form-text text-muted">
                                    <i class="fas fa-id-card mr-1"></i>
                                    Ingresa tu Documento Único de Identidad (ejemplo: 12345678-9)
                                </small>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Nombres <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nombres" placeholder="Juan Carlos" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Apellidos <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="apellidos" placeholder="Pérez López" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Correo electrónico</label>
                                        <input type="email" class="form-control" id="email-factura" placeholder="correo@ejemplo.com">
                                        <small class="form-text text-muted">Para enviarte la factura digital</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" placeholder="7000-1234">
                                        <small class="form-text text-muted">Formato: 7000-1234</small>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            
                            <div class="factura-simple mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-1"><strong>Folio:</strong> <span class="text-info">DG-2026-001234</span></p>
                                        <p class="mb-1"><strong>Fecha:</strong> 17/02/2026</p>
                                        <p class="mb-1"><strong>Total a pagar:</strong> <span class="text-warning font-weight-bold">$85.00</span></p>
                                    </div>
                                    <div class="text-center">
                                        <i class="fas fa-receipt" style="font-size: 40px; color: var(--accent-primary);"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="acepto" required>
                                <label class="custom-control-label" for="acepto">
                                    <span class="text-danger">*</span> Confirmo que pagaré en efectivo en recepción y presentaré mi DUI original
                                </label>
                            </div>

                            <button class="btn-generar btn-block" onclick="generarFactura()">
                                <i class="fas fa-file-pdf mr-2"></i>
                                GENERAR FACTURA DIGITAL
                            </button>

                            <p class="text-center mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-clock mr-1"></i>
                                    La factura será válida por 24 horas
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

          
            <div class="col-lg-4">
                <div class="card resumen-card">
                    <div class="card-body">
                        <div class="resumen">
    <h3>Resumen</h3>

    <p>
        <?= $_SESSION['nombre_plan'] ?? 'Sin plan seleccionado' ?>
    </p>
    

    <hr>

    <p>
        TOTAL:
        <strong>
            $<?= $_SESSION['precio_plan'] ?? '0.00' ?>
        </strong>
    </p>
</div>
                </div>
            </div>
        </div>
    </div>

   
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    <script>
       
        function cambiarMetodo(metodo) {
            
            document.querySelectorAll('.metodo-item').forEach(item => {
                item.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
           
            document.querySelectorAll('.metodo-panel').forEach(panel => {
                panel.classList.remove('active');
            });
            document.getElementById('metodo-' + metodo).classList.add('active');
        }

        
    </script>
</body>
</html>