<!doctype html>
<html lang="es">
<head>
    <title>Recepción - Delux Gym</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="../assets/css/recepcion.css">
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo-area">
                <img src="../assets/img/logo_deluxGym.png" alt="Delux Gym" style="width: 120px;">
                <h2>Recepción</h2>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#clienti" class="nav-link active" onclick="cambiarVista('clienti')">
                        <i class="fas fa-users"></i>
                        Clientes registrados
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#ingresso" class="nav-link" onclick="cambiarVista('ingresso')">
                        <i class="fas fa-sign-in-alt"></i>
                        Ingresar nuevo cliente
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#pagos" class="nav-link" onclick="cambiarVista('pagos')">
                        <i class="fas fa-money-bill-wave"></i>
                        Registrar pago 
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#productos" class="nav-link" onclick="cambiarVista('productos')">
                        <i class="fas fa-box"></i>
                        Validar ticket
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="header-title">
                    <h1 id="vista-titulo">Clientes registrados</h1>
                    <p class="fecha-actual"><i class="far fa-calendar-alt mr-2"></i><?php echo date('d/m/Y'); ?></p>
                </div>
                <div class="admin-profile">
                    <i class="fas fa-bell"></i>
                    <i class="fas fa-user-circle"></i>
                    <span>Recepcionista</span>
                </div>
            </div>

            <!-- VISTA 1: Lista de clientes -->
            <div id="vista-clienti" class="vista active">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Clientes hoy</h4>
                            <h2>24</h2>
                            <span>+12%</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Nuevos</h4>
                            <h2>8</h2>
                            <span>+5%</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Pagos hoy</h4>
                            <h2>$1,250</h2>
                            <span>$3,050</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Productos</h4>
                            <h2>15</h2>
                            <span>entregados</span>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Lista clientes</h5>
                            <button class="btn-nuevo" onclick="cambiarVista('ingresso')">
                                <i class="fas fa-plus mr-2"></i>Nuevo cliente
                            </button>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>DUI</th>
                                    <th>Nombre</th>
                                    <th>Plan</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>12345678-9</td>
                                    <td>Juan Pérez</td>
                                    <td>Premium</td>
                                    <td><span class="badge badge-success">Activo</span></td>
                                    <td>
                                        <button class="btn-accion"><i class="fas fa-eye"></i></button>
                                        <button class="btn-accion"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>87654321-0</td>
                                    <td>María López</td>
                                    <td>Básico</td>
                                    <td><span class="badge badge-warning">Pendiente</span></td>
                                    <td>
                                        <button class="btn-accion"><i class="fas fa-eye"></i></button>
                                        <button class="btn-accion"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- VISTA 2: Ingreso cliente (Registro de cliente) -->
            <div id="vista-ingresso" class="vista">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-user-plus mr-2"></i>
                            Registrar nuevo cliente
                        </h5>

                        <form id="form-cliente">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>DUI <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="dui" placeholder="00000000-0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" placeholder="7000-1234">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombres <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nombres" placeholder="Juan Carlos">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Apellidos <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="apellidos" placeholder="Pérez López">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" placeholder="correo@ejemplo.com">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Dirección</label>
                                        <input type="text" class="form-control" placeholder="Calle, colonia">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Plan <span class="text-danger">*</span></label>
                                        <select class="form-control" id="select-plan">
                                            <option value="">Seleccionar membresía</option>
                                            <option value="basico">Básico - $30/mes</option>
                                            <option value="estandar">Estándar - $50/mes</option>
                                            <option value="premium">Premium - $80/mes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Método de pago</label>
                                        <select class="form-control">
                                            <option value="">Seleccionar</option>
                                            <option value="efectivo">Efectivo</option>
                                            <option value="tarjeta">Tarjeta</option>
                                            <option value="transferencia">Transferencia</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Número de caja</label>
                                        <input type="text" class="form-control" placeholder="c-001">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Responsable</label>
                                        <input type="text" class="form-control" value="Recepcionista" readonly>
                                    </div>
                                </div>
                               
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha de ingreso</label>
                                        <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total a pagar</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" id="total-pagar" value="0.00" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="button" class="btn-cancelar mr-2">Cancelar</button>
                                <button type="submit" class="btn-registrar">
                                    <i class="fas fa-save mr-2"></i>
                                    Registrar cliente
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

           
            <!-- VISTA 4: Registrar pago en efectivo -->
            <div id="vista-pagos" class="vista">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            Registrar pago en efectivo
                        </h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Buscar cliente por DUI</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="00000000-0">
                                        <div class="input-group-append">
                                            <button class="btn-buscar">Buscar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="cliente-info mt-4 p-3">
                            <h6>Cliente: Juan Pérez López</h6>
                            <p>DUI: 12345678-9 | Plan: Premium | Próximo pago: 15/03/2024</p>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Monto a pagar</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="text" class="form-control" value="80.00" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Recibido</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="text" class="form-control" id="recibido" value="100.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cambio</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="text" class="form-control" id="cambio" value="20.00" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button class="btn-registrar mt-3">
                                <i class="fas fa-check mr-2"></i>
                                Confirmar pago
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VISTA 5: Validar ticket de productos -->
            <div id="vista-productos" class="vista">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-box mr-2"></i>
                            Validar ticket y entregar productos
                        </h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Número de ticket</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="DG-2024-001234">
                                        <div class="input-group-append">
                                            <button class="btn-buscar">Validar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ticket-info mt-4 p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Ticket:</strong> DG-2026-001234</p>
                                    <p><strong>Cliente:</strong> Juan Pérez López</p>
                                    <p><strong>Fecha:</strong> 17/02/2024</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Total pagado:</strong> $45.00</p>
                                    <p><strong>Estado:</strong> <span class="badge badge-warning">Pendiente</span></p>
                                </div>
                            </div>
                        </div>

                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Entregar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Proteína Whey</td>
                                    <td>1</td>
                                    <td>$30.00</td>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="prod1">
                                            <label class="custom-control-label" for="prod1"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Shaker</td>
                                    <td>1</td>
                                    <td>$10.00</td>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="prod2">
                                            <label class="custom-control-label" for="prod2"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Magnesio</td>
                                    <td>1</td>
                                    <td>$5.00</td>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="prod3">
                                            <label class="custom-control-label" for="prod3"></label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="text-right mt-3">
                            <button class="btn-registrar" onclick="entregarProductos()">
                                <i class="fas fa-check-circle mr-2"></i>
                                Confirmar entrega
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    

    <!-- Scripts -->
    <script>
        function cambiarVista(vista) {
            // Actualizar links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Ocultar todas las vistas
            document.querySelectorAll('.vista').forEach(v => {
                v.classList.remove('active');
            });
            
            // Mostrar vista seleccionada
            document.getElementById('vista-' + vista).classList.add('active');
            
            // Actualizar título
            let titulos = {
                'clienti': 'Clientes registrados',
                'ingresso': 'Ingreso cliente',
                'visite': 'Visitas clientes',
                'pagos': 'Registrar pago',
                'productos': 'Validar ticket'
            };
            document.getElementById('vista-titulo').textContent = titulos[vista];
        }

    </script>
</body>
</html>