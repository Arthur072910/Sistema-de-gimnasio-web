
<!doctype html>
<html lang="es">
<head>
    <title>Finalizar Compra - Delux Gym</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="../assets/css/pago.css">
</head>
<body>
    <!-- Navbar simple -->
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
        <!-- Título simple -->
        <h3 class="mb-4"><i class="fas fa-credit-card mr-2"></i>Finalizar Compra</h3>

        <div class="row">
            <!-- Columna izquierda - Formulario de pago -->
            <div class="col-lg-8 mb-4">
                <div class="card pago-card">
                    <div class="card-body">
                        <!-- Selector de método de pago simple -->
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

                        <!-- TARJETA -->
                        <div id="metodo-tarjeta" class="metodo-panel active">
                            <form id="form-tarjeta">
                                <div class="form-group">
                                    <label>Número de tarjeta</label>
                                    <input type="text" class="form-control" id="card-number" placeholder="1234 5678 9012 3456">
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Vence</label>
                                            <input type="text" class="form-control" id="card-expiry" placeholder="MM/AA">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>CVV</label>
                                            <input type="text" class="form-control" id="card-cvv" placeholder="123">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Nombre del titular</label>
                                    <input type="text" class="form-control" id="card-name" placeholder="Como aparece en la tarjeta">
                                </div>

                                <div class="form-group">
                                    <label>Correo electrónico</label>
                                    <input type="email" class="form-control" id="card-email" placeholder="ejemplo@correo.com">
                                </div>

                                <button type="submit" class="btn-pago btn-block mt-4">
                                    <i class="fas fa-lock mr-2"></i>
                                    PAGAR $3,050.80
                                </button>
                            </form>
                        </div>

                        <!-- PAYPAL -->
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

                        <!-- EFECTIVO / FACTURA DIGITAL (VERSIÓN EL SALVADOR) -->
                        <div id="metodo-efectivo" class="metodo-panel">
                            <div class="alert alert-info py-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                Genera tu factura y paga en recepción con tu DUI
                            </div>

                            <h6 class="mb-3">Datos para factura</h6>
                            
                            <!-- DUI (Documento Único de Identidad) -->
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

                            <!-- Resumen de factura -->
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

            <!-- Columna derecha - Resumen simple -->
            <div class="col-lg-4">
                <div class="card resumen-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Resumen</h5>

                        <!-- Productos -->
                        <div class="productos-resumen mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Proteína Whey <small>x2</small></span>
                                <span>$45.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Creatina <small>x1</small></span>
                                <span>$30.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shaker <small>x1</small></span>
                                <span>$10.00</span>
                            </div>
                        </div>

                        <hr>

                        <!-- Totales -->
                        <div class="totales-simple">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>$85.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Envío:</span>
                                <span class="text-success">Gratis</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>IVA (13% SV):</span>
                                <span>$.......</span>
                            </div>
                            <div class="d-flex justify-content-between font-weight-bold mt-2">
                                <span>TOTAL:</span>
                                <span class="total-final">$85.00</span>
                            </div>
                        </div>

                        <!-- Cupón simple -->
                        <div class="cupon-simple mt-3">
                            <input type="text" class="form-control form-control-sm" placeholder="¿Cupón?" id="cupon">
                            <button class="btn-aplicar btn-sm mt-2" onclick="aplicarCupon()">Aplicar</button>
                        </div>

                        <!-- Seguridad -->
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-lock mr-1"></i>
                                Pago seguro SSL
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    <script>
        // Cambiar entre métodos de pago
        function cambiarMetodo(metodo) {
            // Actualizar clases de los items
            document.querySelectorAll('.metodo-item').forEach(item => {
                item.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Mostrar panel correspondiente
            document.querySelectorAll('.metodo-panel').forEach(panel => {
                panel.classList.remove('active');
            });
            document.getElementById('metodo-' + metodo).classList.add('active');
        }

        
    </script>
</body>
</html>