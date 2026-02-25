<?php
// Al principio del archivo, antes de cualquier HTML
session_start();

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Aquí incluimos los controladores que necesitamos
require_once __DIR__ . '/controller/ProductoController.php';

// Procesar acciones de productos ANTES de cualquier HTML
$mensaje_producto = '';
$tipo_mensaje_producto = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion_producto'])) {
    $productoController = new ProductoController();
    
    switch ($_POST['accion_producto']) {
        case 'agregar':
            $resultado = $productoController->agregar($_POST);
            $mensaje_producto = $resultado['message'];
            $tipo_mensaje_producto = $resultado['success'] ? 'success' : 'danger';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delux Gym - Panel Administrativo</title>
    
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    
    <style>
       
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 px-0 sidebar">
                <div style="width: 120px; height: 120px; margin: 0 auto;">
                    <img src="../assets/img/logo_deluxGym.png" 
                     alt="Delux Gym" 
                     style="width: 100%; height: 100%; object-fit: contain;">
                </div>

                
                
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#" data-section="dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a class="nav-link" href="#" data-section="entrenadores">
                        <i class="fas fa-user-tie"></i> Registro de Entrenadores
                    </a>
                    <a class="nav-link" href="#" data-section="miembros">
                        <i class="fas fa-users"></i> Miembros y Registro
                    </a>
                    <a class="nav-link" href="#" data-section="clases">
                        <i class="fas fa-chalkboard-teacher"></i> Registro de Clases
                    </a>
                    <a class="nav-link" href="#" data-section="productos">
                        <i class="fas fa-box"></i> Registro de Productos
                    </a>
                    <a class="nav-link" href="#" data-section="pagos">
                        <i class="fas fa-credit-card"></i> Pagos del Gym
                    </a>
                    <a class="nav-link" href="#" data-section="membresias">
                        <i class="fas fa-id-card"></i> Planes/Membresías
                    </a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 content">
                <!-- Dashboard Section -->
                <div id="dashboard-section" class="section">
                    <h3 class="mb-4" style="color: #ffd700;">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </h3>
                    
                    <!-- Stats Cards -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <i class="fas fa-users fa-2x mb-2" style="color: #ffd700;"></i>
                                    <div class="stat-value">156</div>
                                    <div class="stat-label">Miembros Activos</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <i class="fas fa-user-tie fa-2x mb-2" style="color: #ffd700;"></i>
                                    <div class="stat-value">12</div>
                                    <div class="stat-label">Entrenadores</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <i class="fas fa-calendar-alt fa-2x mb-2" style="color: #ffd700;"></i>
                                    <div class="stat-value">8</div>
                                    <div class="stat-label">Clases Hoy</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card">
                                <div class="card-body">
                                    <i class="fas fa-dollar-sign fa-2x mb-2" style="color: #ffd700;"></i>
                                    <div class="stat-value">$12,450</div>
                                    <div class="stat-label">Ingresos del Mes</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Activity -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-clock mr-2"></i>Actividad Reciente
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-3">
                                            <i class="fas fa-user-plus text-success mr-2"></i>
                                            Nuevo miembro: Carlos Rodríguez
                                            <small class="d-block text-muted">Hace 10 minutos</small>
                                        </li>
                                        <li class="mb-3">
                                            <i class="fas fa-credit-card text-warning mr-2"></i>
                                            Pago recibido: Ana Martínez
                                            <small class="d-block text-muted">Hace 25 minutos</small>
                                        </li>
                                        <li class="mb-3">
                                            <i class="fas fa-dumbbell text-info mr-2"></i>
                                            Clase iniciada: Crossfit
                                            <small class="d-block text-muted">Hace 30 minutos</small>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-chart-line mr-2"></i>Próximas Clases
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Clase</th>
                                                <th>Hora</th>
                                                <th>Entrenador</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Yoga</td>
                                                <td>4:00 PM</td>
                                                <td>Laura Sánchez</td>
                                            </tr>
                                            <tr>
                                                <td>Spinning</td>
                                                <td>5:00 PM</td>
                                                <td>Pedro Gómez</td>
                                            </tr>
                                            <tr>
                                                <td>Funcional</td>
                                                <td>6:00 PM</td>
                                                <td>Carlos Ruiz</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Entrenadores Section -->
                <div id="entrenadores-section" class="section" style="display: none;">
                    <h3 class="mb-4" style="color: #ffd700;">
                        <i class="fas fa-user-tie mr-2"></i>Registro de Entrenadores
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-plus-circle mr-2"></i>Nuevo Entrenador
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="form-group">
                                            <label>Nombre Completo</label>
                                            <input type="text" class="form-control" placeholder="Ingrese nombre">
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" placeholder="correo@ejemplo.com">
                                        </div>
                                        <div class="form-group">
                                            <label>Teléfono</label>
                                            <input type="text" class="form-control" placeholder="Número de teléfono">
                                        </div>
                                        <div class="form-group">
                                            <label>Especialidad</label>
                                            <select class="form-control">
                                                <option>Seleccione especialidad</option>
                                                <option>Crossfit</option>
                                                <option>Yoga</option>
                                                <option>Spinning</option>
                                                <option>Funcional</option>
                                                <option>Musculación</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Experiencia (años)</label>
                                            <input type="number" class="form-control" min="0">
                                        </div>
                                        <button type="submit" class="btn btn-gold btn-block">
                                            <i class="fas fa-save mr-2"></i>Guardar Entrenador
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-list mr-2"></i>Lista de Entrenadores
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Especialidad</th>
                                                <th>Teléfono</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Laura Sánchez</td>
                                                <td>Yoga</td>
                                                <td>555-0123</td>
                                                <td><span class="badge-gold">Activo</span></td>
                                                <td>
                                                    <i class="fas fa-edit text-warning mr-2" style="cursor: pointer;"></i>
                                                    <i class="fas fa-trash text-danger" style="cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Pedro Gómez</td>
                                                <td>Spinning</td>
                                                <td>555-0456</td>
                                                <td><span class="badge-gold">Activo</span></td>
                                                <td>
                                                    <i class="fas fa-edit text-warning mr-2" style="cursor: pointer;"></i>
                                                    <i class="fas fa-trash text-danger" style="cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Carlos Ruiz</td>
                                                <td>Funcional</td>
                                                <td>555-0789</td>
                                                <td><span class="badge-gold">Activo</span></td>
                                                <td>
                                                    <i class="fas fa-edit text-warning mr-2" style="cursor: pointer;"></i>
                                                    <i class="fas fa-trash text-danger" style="cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Miembros Section -->
                <div id="miembros-section" class="section" style="display: none;">
                    <h3 class="mb-4" style="color: #ffd700;">
                        <i class="fas fa-users mr-2"></i>Miembros y Registro
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-user-plus mr-2"></i>Registrar Nuevo Miembro
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="form-group">
                                            <label>Nombre Completo</label>
                                            <input type="text" class="form-control" placeholder="Ingrese nombre">
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" placeholder="correo@ejemplo.com">
                                        </div>
                                        <div class="form-group">
                                            <label>Teléfono</label>
                                            <input type="text" class="form-control" placeholder="Número de teléfono">
                                        </div>
                                        <div class="form-group">
                                            <label>Fecha de Nacimiento</label>
                                            <input type="date" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Plan de Membresía</label>
                                            <select class="form-control">
                                                <option>Seleccione plan</option>
                                                <option>Básico</option>
                                                <option>Premium</option>
                                                <option>VIP</option>
                                                <option>Anual</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-gold btn-block">
                                            <i class="fas fa-save mr-2"></i>Registrar Miembro
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-search mr-2"></i>Buscar Miembros
                                    <div class="float-right">
                                        <input type="text" class="form-control form-control-sm" placeholder="Buscar...">
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Plan</th>
                                                <th>Vencimiento</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>001</td>
                                                <td>Ana Martínez</td>
                                                <td>Premium</td>
                                                <td>15/04/2024</td>
                                                <td><span class="badge-gold">Activo</span></td>
                                                <td>
                                                    <i class="fas fa-eye text-info mr-2" style="cursor: pointer;"></i>
                                                    <i class="fas fa-edit text-warning mr-2" style="cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>002</td>
                                                <td>Carlos Rodríguez</td>
                                                <td>Básico</td>
                                                <td>20/04/2024</td>
                                                <td><span class="badge-gold">Activo</span></td>
                                                <td>
                                                    <i class="fas fa-eye text-info mr-2" style="cursor: pointer;"></i>
                                                    <i class="fas fa-edit text-warning mr-2" style="cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>003</td>
                                                <td>María López</td>
                                                <td>VIP</td>
                                                <td>25/04/2024</td>
                                                <td><span class="badge-gold">Activo</span></td>
                                                <td>
                                                    <i class="fas fa-eye text-info mr-2" style="cursor: pointer;"></i>
                                                    <i class="fas fa-edit text-warning mr-2" style="cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Clases Section -->
                <div id="clases-section" class="section" style="display: none;">
                    <h3 class="mb-4" style="color: #ffd700;">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>Registro de Clases
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-plus-circle mr-2"></i>Nueva Clase
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="form-group">
                                            <label>Nombre de la Clase</label>
                                            <input type="text" class="form-control" placeholder="Ej: Crossfit">
                                        </div>
                                        <div class="form-group">
                                            <label>Entrenador</label>
                                            <select class="form-control">
                                                <option>Seleccione entrenador</option>
                                                <option>Laura Sánchez</option>
                                                <option>Pedro Gómez</option>
                                                <option>Carlos Ruiz</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Día</label>
                                            <select class="form-control">
                                                <option>Lunes</option>
                                                <option>Martes</option>
                                                <option>Miércoles</option>
                                                <option>Jueves</option>
                                                <option>Viernes</option>
                                                <option>Sábado</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Hora</label>
                                            <input type="time" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Duración (minutos)</label>
                                            <input type="number" class="form-control" value="60">
                                        </div>
                                        <div class="form-group">
                                            <label>Cupo máximo</label>
                                            <input type="number" class="form-control" value="20">
                                        </div>
                                        <button type="submit" class="btn btn-gold btn-block">
                                            <i class="fas fa-save mr-2"></i>Crear Clase
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-calendar-alt mr-2"></i>Horario de Clases
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Clase</th>
                                                <th>Entrenador</th>
                                                <th>Día</th>
                                                <th>Hora</th>
                                                <th>Cupos</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Yoga</td>
                                                <td>Laura Sánchez</td>
                                                <td>Lunes</td>
                                                <td>4:00 PM</td>
                                                <td>15/20</td>
                                                <td>
                                                    <i class="fas fa-edit text-warning mr-2" style="cursor: pointer;"></i>
                                                    <i class="fas fa-trash text-danger" style="cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Spinning</td>
                                                <td>Pedro Gómez</td>
                                                <td>Martes</td>
                                                <td>5:00 PM</td>
                                                <td>12/15</td>
                                                <td>
                                                    <i class="fas fa-edit text-warning mr-2" style="cursor: pointer;"></i>
                                                    <i class="fas fa-trash text-danger" style="cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Funcional</td>
                                                <td>Carlos Ruiz</td>
                                                <td>Miércoles</td>
                                                <td>6:00 PM</td>
                                                <td>18/20</td>
                                                <td>
                                                    <i class="fas fa-edit text-warning mr-2" style="cursor: pointer;"></i>
                                                    <i class="fas fa-trash text-danger" style="cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Productos Section -->
                <div id="productos-section" class="section" style="display: none;">
                    <h3 class="mb-4" style="color: #ffd700;">
                        <i class="fas fa-box mr-2"></i>Registro de Productos
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-plus-circle mr-2"></i>Nuevo Producto
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="form-group">
                                            <label>Nombre del Producto</label>
                                            <input type="text" class="form-control" placeholder="Ej: Proteína Whey">
                                        </div>
                                        <div class="form-group">
                                            <label>Categoría</label>
                                            <select class="form-control">
                                                <option>Suplementos</option>
                                                <option>Ropa Deportiva</option>
                                                <option>Accesorios</option>
                                                <option>Bebidas</option>
                                                <option>Equipamiento</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Precio</label>
                                            <input type="number" class="form-control" placeholder="$">
                                        </div>
                                        <div class="form-group">
                                            <label>Cantidad en Stock</label>
                                            <input type="number" class="form-control" placeholder="0">
                                        </div>
                                        <div class="form-group">
                                            <label>Proveedor</label>
                                            <input type="text" class="form-control" placeholder="Nombre del proveedor">
                                        </div>
                                        <button type="submit" class="btn btn-gold btn-block">
                                            <i class="fas fa-save mr-2"></i>Agregar Producto
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-warehouse mr-2"></i>Inventario de Productos
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Categoría</th>
                                                <th>Precio</th>
                                                <th>Stock</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Proteína Whey</td>
                                                <td>Suplementos</td>
                                                <td>$45.00</td>
                                                <td>25</td>
                                                <td>
                                                    <i class="fas fa-edit text-warning mr-2" style="cursor: pointer;"></i>
                                                    <i class="fas fa-trash text-danger" style="cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Camiseta Dry Fit</td>
                                                <td>Ropa Deportiva</td>
                                                <td>$25.00</td>
                                                <td>50</td>
                                                <td>
                                                    <i class="fas fa-edit text-warning mr-2" style="cursor: pointer;"></i>
                                                    <i class="fas fa-trash text-danger" style="cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Guantes de Gym</td>
                                                <td>Accesorios</td>
                                                <td>$15.00</td>
                                                <td>30</td>
                                                <td>
                                                    <i class="fas fa-edit text-warning mr-2" style="cursor: pointer;"></i>
                                                    <i class="fas fa-trash text-danger" style="cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pagos Section -->
                <div id="pagos-section" class="section" style="display: none;">
                    <h3 class="mb-4" style="color: #ffd700;">
                        <i class="fas fa-credit-card mr-2"></i>Pagos del Gym
                    </h3>
                    
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-history mr-2"></i>Historial de Pagos
                            <div class="float-right">
                                <button class="btn btn-gold btn-sm">
                                    <i class="fas fa-plus-circle"></i> Registrar Pago
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Concepto</th>
                                        <th>Monto</th>
                                        <th>Método de Pago</th>
                                        <th>Estado</th>
                                        <th>Comprobante</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>15/03/2024</td>
                                        <td>Pago de membresía - Ana Martínez</td>
                                        <td>$75.00</td>
                                        <td>Tarjeta de Crédito</td>
                                        <td><span class="badge-gold">Pagado</span></td>
                                        <td><i class="fas fa-file-pdf text-danger" style="cursor: pointer;"></i></td>
                                    </tr>
                                    <tr>
                                        <td>14/03/2024</td>
                                        <td>Compra de productos - Carlos López</td>
                                        <td>$120.00</td>
                                        <td>Efectivo</td>
                                        <td><span class="badge-gold">Pagado</span></td>
                                        <td><i class="fas fa-file-pdf text-danger" style="cursor: pointer;"></i></td>
                                    </tr>
                                    <tr>
                                        <td>13/03/2024</td>
                                        <td>Pago de membresía - Pedro Gómez</td>
                                        <td>$90.00</td>
                                        <td>Transferencia</td>
                                        <td><span class="badge-gold">Pagado</span></td>
                                        <td><i class="fas fa-file-pdf text-danger" style="cursor: pointer;"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <!-- Resumen de Pagos -->
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="card stat-card">
                                        <div class="card-body text-center">
                                            <div class="stat-value">$2,450</div>
                                            <div class="stat-label">Pagos Hoy</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card stat-card">
                                        <div class="card-body text-center">
                                            <div class="stat-value">$12,450</div>
                                            <div class="stat-label">Este Mes</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card stat-card">
                                        <div class="card-body text-center">
                                            <div class="stat-value">45</div>
                                            <div class="stat-label">Transacciones</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card stat-card">
                                        <div class="card-body text-center">
                                            <div class="stat-value">$45,780</div>
                                            <div class="stat-label">Total Año</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Membresías Section -->
                <div id="membresias-section" class="section" style="display: none;">
                    <h3 class="mb-4" style="color: #ffd700;">
                        <i class="fas fa-id-card mr-2"></i>Planes y Membresías
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-plus-circle mr-2"></i>Nuevo Plan
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="form-group">
                                            <label>Nombre del Plan</label>
                                            <input type="text" class="form-control" placeholder="Ej: Plan Premium">
                                        </div>
                                        <div class="form-group">
                                            <label>Duración</label>
                                            <select class="form-control">
                                                <option>Mensual</option>
                                                <option>Trimestral</option>
                                                <option>Semestral</option>
                                                <option>Anual</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Precio</label>
                                            <input type="number" class="form-control" placeholder="$">
                                        </div>
                                        <div class="form-group">
                                            <label>Descripción</label>
                                            <textarea class="form-control" rows="3" placeholder="Beneficios del plan..."></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Características</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="">
                                                <label class="form-check-label">Acceso a todas las áreas</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="">
                                                <label class="form-check-label">Clases grupales</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="">
                                                <label class="form-check-label">Entrenador personal</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="">
                                                <label class="form-check-label">Acceso a sauna</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-gold btn-block">
                                            <i class="fas fa-save mr-2"></i>Crear Plan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-tags mr-2"></i>Planes Disponibles
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <h4 style="color: #ffd700;">Plan Básico</h4>
                                                    <p class="display-4">$45</p>
                                                    <p class="text-muted">por mes</p>
                                                    <hr>
                                                    <ul class="list-unstyled">
                                                        <li><i class="fas fa-check text-success mr-2"></i>Acceso al gym</li>
                                                        <li><i class="fas fa-check text-success mr-2"></i>Horario regular</li>
                                                        <li><i class="fas fa-times text-danger mr-2"></i>Clases grupales</li>
                                                        <li><i class="fas fa-times text-danger mr-2"></i>Entrenador personal</li>
                                                    </ul>
                                                    <button class="btn btn-gold btn-block mt-3">Editar Plan</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <h4 style="color: #ffd700;">Plan Premium</h4>
                                                    <p class="display-4">$75</p>
                                                    <p class="text-muted">por mes</p>
                                                    <hr>
                                                    <ul class="list-unstyled">
                                                        <li><i class="fas fa-check text-success mr-2"></i>Acceso al gym</li>
                                                        <li><i class="fas fa-check text-success mr-2"></i>Horario 24/7</li>
                                                        <li><i class="fas fa-check text-success mr-2"></i>Clases grupales</li>
                                                        <li><i class="fas fa-check text-success mr-2"></i>1 sesión personal/mes</li>
                                                    </ul>
                                                    <button class="btn btn-gold btn-block mt-3">Editar Plan</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <h4 style="color: #ffd700;">Plan VIP</h4>
                                                    <p class="display-4">$120</p>
                                                    <p class="text-muted">por mes</p>
                                                    <hr>
                                                    <ul class="list-unstyled">
                                                        <li><i class="fas fa-check text-success mr-2"></i>Acceso al gym</li>
                                                        <li><i class="fas fa-check text-success mr-2"></i>Horario 24/7</li>
                                                        <li><i class="fas fa-check text-success mr-2"></i>Todas las clases</li>
                                                        <li><i class="fas fa-check text-success mr-2"></i>Entrenador personal</li>
                                                        <li><i class="fas fa-check text-success mr-2"></i>Acceso a sauna</li>
                                                    </ul>
                                                    <button class="btn btn-gold btn-block mt-3">Editar Plan</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            
                            <!-- Miembros por Plan -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <i class="fas fa-chart-pie mr-2"></i>Distribución de Miembros por Plan
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Plan</th>
                                                <th>Miembros Activos</th>
                                                <th>Ingresos Mensuales</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Básico</td>
                                                <td>65</td>
                                                <td>$2,925</td>
                                            </tr>
                                            <tr>
                                                <td>Premium</td>
                                                <td>48</td>
                                                <td>$3,600</td>
                                            </tr>
                                            <tr>
                                                <td>VIP</td>
                                                <td>25</td>
                                                <td>$3,000</td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        // Navegación entre secciones
        $(document).ready(function() {
            $('.nav-link').click(function(e) {
                e.preventDefault();
                
                // Remover active de todos los links
                $('.nav-link').removeClass('active');
                // Agregar active al link clickeado
                $(this).addClass('active');
                
                // Ocultar todas las secciones
                $('.section').hide();
                
                // Mostrar la sección correspondiente
                var sectionId = $(this).data('section') + '-section';
                $('#' + sectionId).show();
            });
            
            // Mostrar dashboard por defecto
            $('#dashboard-section').show();
        });
    </script>
</body>
</html>