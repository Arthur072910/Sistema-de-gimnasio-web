<!doctype html>
<html lang="es">
<head>
    <title>Recepción - Delux Gym</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap 4 CSS -->
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
                    <a href="#ingreso-pago" class="nav-link" onclick="cambiarVista('ingreso-pago')">
                        <i class="fas fa-user-plus"></i>
                        Ingreso + Pago
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
                    <p class="fecha-actual"><i class="far fa-calendar-alt mr-2"></i>19/02/2026</p>
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
                            <button class="btn-nuevo" onclick="cambiarVista('ingreso-pago')">
                                <i class="fas fa-plus mr-2"></i>Nuevo cliente
                            </button>
                        </div>
                        
                        <!-- Barra de búsqueda -->
                        <div class="search-bar mb-4">
                            <input type="text" class="form-control" placeholder="Buscar por nombre, DUI o email..." id="buscarCliente">
                            <button class="btn-buscar"><i class="fas fa-search"></i></button>
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
                                        <button class="btn-accion" onclick="editarCliente(this)"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>87654321-0</td>
                                    <td>María López</td>
                                    <td>Básico</td>
                                    <td><span class="badge badge-warning">Pendiente</span></td>
                                    <td>
                                        <button class="btn-accion"><i class="fas fa-eye"></i></button>
                                        <button class="btn-accion" onclick="editarCliente(this)"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>45678912-3</td>
                                    <td>Carlos Rodríguez</td>
                                    <td>Estándar</td>
                                    <td><span class="badge badge-success">Activo</span></td>
                                    <td>
                                        <button class="btn-accion"><i class="fas fa-eye"></i></button>
                                        <button class="btn-accion" onclick="editarCliente(this)"><i class="fas fa-edit"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- VISTA 2: INGRESO NUEVO CLIENTE + REGISTRAR PAGO (UNIDAS) -->
            <div id="vista-ingreso-pago" class="vista">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="fas fa-user-plus mr-2"></i>
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            Ingreso de cliente + Registrar pago
                        </h5>

                        <!-- Buscar cliente existente (para pago) -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Buscar cliente por DUI (para pago)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="buscarDui" placeholder="00000000-0">
                                        <div class="input-group-append">
                                            <button class="btn-buscar" onclick="buscarClientePorDUI()">Buscar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>O seleccionar cliente reciente</label>
                                    <select class="form-control" id="clienteReciente" onchange="cargarClienteReciente()">
                                        <option value="">-- Seleccionar cliente --</option>
                                        <option value="1">Juan Pérez - 12345678-9</option>
                                        <option value="2">María López - 87654321-0</option>
                                        <option value="3">Carlos Rodríguez - 45678912-3</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Información del cliente (se muestra cuando se busca/select) -->
                        <div id="infoCliente" class="cliente-info mt-2 p-3" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 id="clienteNombre">Cliente: Juan Pérez López</h6>
                                    <p id="clienteDUI">DUI: 12345678-9</p>
                                </div>
                                <div class="col-md-6">
                                    <p id="clientePlan">Plan: Premium</p>
                                    <p id="clienteProximoPago">Próximo pago: 19/03/2026</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="mb-3">Datos del cliente</h6>
                        <form id="form-cliente-pago">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>DUI <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="dui" placeholder="00000000-0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" placeholder="7000-1234">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" id="email" placeholder="correo@ejemplo.com">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nombres <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nombres" placeholder="Juan Carlos">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Apellidos <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="apellidos" placeholder="Pérez López">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Dirección</label>
                                        <input type="text" class="form-control" id="direccion" placeholder="Calle, colonia">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="mb-3">Datos de membresía y pago</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Plan <span class="text-danger">*</span></label>
                                        <select class="form-control" id="select-plan" onchange="calcularMonto()">
                                            <option value="">Seleccionar membresía</option>
                                            <option value="30">Básico - $30/mes</option>
                                            <option value="50">Estándar - $50/mes</option>
                                            <option value="80">Premium - $80/mes</option>
                                            <option value="120">VIP - $120/mes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Método de pago</label>
                                        <select class="form-control" id="metodoPago">
                                            <option value="">Seleccionar</option>
                                            <option value="efectivo">Efectivo</option>
                                            <option value="tarjeta">Tarjeta</option>
                                            <option value="transferencia">Transferencia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha de ingreso</label>
                                        <input type="date" class="form-control" id="fechaIngreso" value="2026-02-19">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Próximo pago</label>
                                        <input type="date" class="form-control" id="proximoPago" value="2026-03-19">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Monto a pagar</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" id="monto-pagar" value="0.00" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Recibido</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" id="recibido" value="0.00" onkeyup="calcularCambio()">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Cambio</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="text" class="form-control" id="cambio" value="0.00" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Número de caja</label>
                                        <input type="text" class="form-control" id="caja" placeholder="c-001" value="c-001">
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="text-right">
                                <button type="button" class="btn-cancelar mr-2" onclick="limpiarFormulario()">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancelar
                                </button>
                                <button type="button" class="btn-registrar" onclick="procesarIngresoPago()">
                                    <i class="fas fa-save mr-2"></i>
                                    Procesar ingreso + pago
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tabla de clientes y pagos recientes -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Clientes y pagos recientes</h5>
                        
                        <ul class="nav nav-tabs" id="pagosTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="ultimos-clientes-tab" data-toggle="tab" href="#ultimos-clientes" role="tab">Últimos clientes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pagos-recientes-tab" data-toggle="tab" href="#pagos-recientes" role="tab">Pagos recientes</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="pagosTabContent">
                            <!-- Tabla de últimos clientes -->
                            <div class="tab-pane fade show active" id="ultimos-clientes" role="tabpanel">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>DUI</th>
                                            <th>Nombre completo</th>
                                            <th>Plan</th>
                                            <th>Fecha ingreso</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>12345678-9</td>
                                            <td>Juan Pérez López</td>
                                            <td>Premium</td>
                                            <td>19/02/2026</td>
                                            <td><span class="badge badge-success">Activo</span></td>
                                            <td>
                                                <button class="btn-accion" onclick="cargarClienteParaPago('12345678-9', 'Juan Pérez López', 'Premium')"><i class="fas fa-money-bill-wave"></i></button>
                                                <button class="btn-accion" onclick="editarClienteDesdeTabla(this)"><i class="fas fa-edit"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>87654321-0</td>
                                            <td>María López García</td>
                                            <td>Básico</td>
                                            <td>18/02/2026</td>
                                            <td><span class="badge badge-warning">Pendiente</span></td>
                                            <td>
                                                <button class="btn-accion" onclick="cargarClienteParaPago('87654321-0', 'María López García', 'Básico')"><i class="fas fa-money-bill-wave"></i></button>
                                                <button class="btn-accion" onclick="editarClienteDesdeTabla(this)"><i class="fas fa-edit"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Tabla de pagos recientes -->
                            <div class="tab-pane fade" id="pagos-recientes" role="tabpanel">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Cliente</th>
                                            <th>Plan</th>
                                            <th>Monto</th>
                                            <th>Método</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>19/02/2026</td>
                                            <td>Juan Pérez</td>
                                            <td>Premium</td>
                                            <td>$80.00</td>
                                            <td>Efectivo</td>
                                            <td><span class="badge badge-success">Pagado</span></td>
                                        </tr>
                                        <tr>
                                            <td>18/02/2026</td>
                                            <td>María López</td>
                                            <td>Básico</td>
                                            <td>$30.00</td>
                                            <td>Tarjeta</td>
                                            <td><span class="badge badge-success">Pagado</span></td>
                                        </tr>
                                        <tr>
                                            <td>17/02/2026</td>
                                            <td>Carlos Rodríguez</td>
                                            <td>Estándar</td>
                                            <td>$50.00</td>
                                            <td>Transferencia</td>
                                            <td><span class="badge badge-success">Pagado</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VISTA 3: Validar ticket de productos -->
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
                                        <input type="text" class="form-control" placeholder="DG-2026-001234">
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
                                    <p><strong>Fecha:</strong> 19/02/2026</p>
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

    <!-- Scripts de Bootstrap 4 -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- Scripts personalizados -->
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
                'ingreso-pago': 'Ingreso de cliente + Registrar pago',
                'productos': 'Validar ticket'
            };
            document.getElementById('vista-titulo').textContent = titulos[vista];
        }

        
    </script>
</body>
</html>