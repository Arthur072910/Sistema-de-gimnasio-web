<?php
session_start();
require_once "../config/database.php";
require_once "../controller/MembresiaController.php";
require_once "../controller/PagoController.php";

$database = new Database();
$conn = $database->getConnection();

// Verificar que el usuario está logueado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

$id_cliente = $_SESSION['cliente_id'];
$error = '';

// Verificar si hay datos de membresía en sesión
$tiene_membresia = isset($_SESSION['id_membresia']) && isset($_SESSION['precio_plan']);

// Procesar el pago
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['procesar_pago'])) {
    
    $metodo_pago = $_POST['metodo_pago'];
    $tipo_transaccion = $_POST['tipo_transaccion'] ?? '';
    
    try {
        $conn->beginTransaction();
        
        if ($tipo_transaccion == 'membresia') {
            // PAGO DE MEMBRESÍA
            $membresiaController = new MembresiaController();
            $resultadoMembresia = $membresiaController->comprar(
                $id_cliente, 
                $_SESSION['id_membresia']
            );
            
            if (!$resultadoMembresia['success']) {
                throw new Exception($resultadoMembresia['message'] ?? 'Error al crear membresía');
            }
            
            $id_membresia = $resultadoMembresia['id_membresia'];
            
            // Registrar pago
            $pagoController = new PagoController();
            $resultadoPago = $pagoController->registrarPagoMembresia(
                $id_cliente,
                $_SESSION['precio_plan'],
                $metodo_pago,
                $id_membresia
            );
            
            if (!$resultadoPago['success']) {
                throw new Exception('Error al registrar el pago');
            }
            
            // Limpiar sesión de membresía
            unset($_SESSION['id_membresia']);
            unset($_SESSION['precio_plan']);
            unset($_SESSION['duracion_dias']);
            unset($_SESSION['nombre_plan']);
            
            $conn->commit();
            
            // LIMPIAR CARRITO Y REDIRIGIR
            echo "<script>
                localStorage.removeItem('gym_cart');
                sessionStorage.removeItem('pago_carrito');
                sessionStorage.removeItem('pago_total');
                window.location.href = 'perfil.php?pago=exitoso&tipo=membresia';
            </script>";
            exit();
            
        } else {
            // PAGO DE PRODUCTOS
            $items = json_decode($_POST['items'], true);
            $monto_total = $_POST['monto_total'] ?? 0;
            
            $pagoController = new PagoController();
            $resultadoPago = $pagoController->registrarPagoProductos(
                $id_cliente,
                $monto_total,
                $metodo_pago,
                $items
            );
            
            if (!$resultadoPago['success']) {
                throw new Exception('Error al registrar el pago');
            }
            
            $conn->commit();
            
            // LIMPIAR CARRITO Y REDIRIGIR
            echo "<script>
                localStorage.removeItem('gym_cart');
                sessionStorage.removeItem('pago_carrito');
                sessionStorage.removeItem('pago_total');
                window.location.href = 'perfil.php?pago=exitoso&tipo=productos';
            </script>";
            exit();
        }
        
    } catch (Exception $e) {
        $conn->rollBack();
        $error = "Error al procesar el pago: " . $e->getMessage();
        error_log("Error en pago: " . $e->getMessage());
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
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/pago.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>
<body>
    <?php include "../layout/header.php"; ?>

    <div class="container py-4">
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo $error; ?>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <h3 class="mb-4">
            <i class="fas fa-credit-card mr-2" style="color: var(--gold);"></i>
            Finalizar Compra
        </h3>

        <div class="row">
            <!-- Columna izquierda - Formulario de pago -->
            <div class="col-lg-8 mb-4">
                <div class="card-gym">
                    <div class="card-header-gym">
                        <i class="fas fa-credit-card mr-2"></i>
                        Método de Pago
                    </div>
                    <div class="card-body">
                        <!-- Selector de método de pago -->
                        <div class="metodos-pago-simple mb-4" id="metodos-container">
                            <!-- Se llenará con JavaScript -->
                        </div>

                        <!-- Formulario de pago -->
                        <form method="POST" action="" id="form-pago">
                            <input type="hidden" name="procesar_pago" value="1">
                            <input type="hidden" name="metodo_pago" id="metodo_pago" value="tarjeta">
                            <input type="hidden" name="tipo_transaccion" id="tipo_transaccion" value="">
                            <input type="hidden" name="monto_total" id="monto_total" value="">
                            <input type="hidden" name="items" id="items" value="">
                            <input type="hidden" name="id_tipo_membresia" id="id_tipo_membresia" value="<?php echo $_SESSION['id_membresia'] ?? ''; ?>">
                            <input type="hidden" name="duracion_dias" id="duracion_dias" value="<?php echo $_SESSION['duracion_dias'] ?? ''; ?>">
                            
                            <!-- Campo oculto para indicar si hay membresía -->
                            <input type="hidden" id="tiene-membresia" value="<?php echo $tiene_membresia ? '1' : '0'; ?>">

                            <!-- Panel Tarjeta -->
                            <div id="metodo-tarjeta" class="metodo-panel active">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Número de tarjeta</label>
                                        <input type="text" class="form-control-gym" name="numero_tarjeta" placeholder="1234 5678 9012 3456" 
                                               pattern="\d{16}" title="16 dígitos" maxlength="16" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">MM/AA</label>
                                        <input type="text" class="form-control-gym" name="vencimiento" placeholder="12/25" 
                                               pattern="\d{2}/\d{2}" title="MM/AA" maxlength="5" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">CVV</label>
                                        <input type="text" class="form-control-gym" name="cvv" placeholder="123" 
                                               pattern="\d{3}" title="3 dígitos" maxlength="3" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nombre en tarjeta</label>
                                        <input type="text" class="form-control-gym" name="nombre_tarjeta" placeholder="Como aparece en la tarjeta" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel Efectivo (solo para membresías) -->
                            <div id="metodo-efectivo" class="metodo-panel">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Paga en efectivo en recepción y presenta tu DUI
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">DUI</label>
                                        <input type="text" class="form-control-gym" id="dui" placeholder="00000000-0" 
                                               pattern="\d{8}-\d{1}" title="00000000-0">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" class="form-control-gym" id="telefono" placeholder="7000-1234">
                                    </div>
                                </div>
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input" id="acepto">
                                    <label class="custom-control-label" for="acepto">
                                        Confirmo que pagaré en efectivo en recepción
                                    </label>
                                </div>
                            </div>

                            <!-- BOTONES JUNTOS Y SIMÉTRICOS (MEJORA 1) -->
                            <div class="row mt-4">
                                <div class="col-md-6 mb-2">
                                    <a href="#" onclick="cancelarCompra(event)" class="btn-cancelar btn-block">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Cancelar
                                    </a>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <button type="submit" class="btn-pagar btn-block" id="btn-pagar">
                                        <i class="fas fa-lock mr-2"></i>
                                        PAGAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Columna derecha - Resumen -->
            <div class="col-lg-4">
                <div class="card-gym sticky-top" style="top: 100px;">
                    <div class="card-header-gym">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Resumen del Pedido
                    </div>
                    <div class="card-body" id="resumen-container">
                        <p class="text-center text-muted">Cargando...</p>
                    </div>
                    
                    <!-- TOTAL ÚNICO EN EL FOOTER (MEJORA 2) -->
                    <div class="card-footer-gym text-center py-3 bg-dark" id="total-footer" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center px-3">
                            <span class="h5 mb-0">Total:</span>
                            <span class="h4 mb-0 text-warning" id="total-unico">$0.00</span>
                        </div>
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
    <script src="../assets/js/pago.js"></script>
    
    <script>
        function cancelarCompra(e) {
            e.preventDefault();
            
            // Limpiar carrito
            localStorage.removeItem('gym_cart');
            sessionStorage.removeItem('pago_carrito');
            sessionStorage.removeItem('pago_total');
            
            // Redirigir según el tipo
            <?php if ($tiene_membresia): ?>
                window.location.href = 'planes.php';
            <?php else: ?>
                window.location.href = 'productos.php';
            <?php endif; ?>
        }
    </script>
    
    <!-- Script para pasar datos de membresía desde PHP -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($tiene_membresia): ?>
                mostrarMembresia(
                    '<?php echo $_SESSION['nombre_plan'] ?? "Plan seleccionado"; ?>',
                    <?php echo $_SESSION['precio_plan'] ?? 0; ?>
                );
            <?php endif; ?>
        });
    </script>
</body>
</html>