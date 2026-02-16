<!doctype html>
<html lang="es">
<head>
    <title>Gestión de Gimnasio</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-sm" id="navegacion">
        <img src="../assets/img/delux gym.png" alt="Logo" width="60" height="auto" class="d-inline-block align-top" id="logo">
       
        <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId">
            <i class="fas fa-bars" style="color: rgba(255, 238, 0, 0.952); font-size: 28px;"></i>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavId">
            <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="nav-link" href="../index.php">Inicio <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Servicios</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownId">
                        <a class="dropdown-item" href="productos.php">Productos</a>
                        <a class="dropdown-item" href="membresias.php">Membresias</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Recomendaciones</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownId">
                        <a class="dropdown-item" href="#">Alimentos</a>
                        <a class="dropdown-item" href="#">Ejercicios</a>
                    </div>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="#">Contactos</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="carrito.php">
                        <i class="fas fa-shopping-cart mr-1"></i> Carrito <span class="badge badge-danger" id="cart-count">0</span>
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="#">Mi Cuenta</a>
                </li>
            </ul>
            
        </div>
    </nav>

<div class="container mt-5">
    <h2 class="mb-4">Panel de Administración</h2>
    
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="registro-tab" data-toggle="tab" href="#seccion1" role="tab">1. Crear Entrenador</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="asignacion-tab" data-toggle="tab" href="#seccion2" role="tab">2. Asignar Clase y Horario</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="reporte-tab" data-toggle="tab" href="#seccion3" role="tab">3. Ver Horarios</a>
        </li>
    </ul>

    <div class="tab-content border-left border-right border-bottom p-4" id="myTabContent">
        
        <div class="tab-pane fade show active" id="seccion1" role="tabpanel">
            <h3>Registro de Personal</h3>
            <form action="guardar_entrenador.php" method="POST">
                <div class="form-group">
                    <label>Nombre del Entrenador</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej. Juan Pérez" required>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Entrenador</button>
            </form>
        </div>

        <div class="tab-pane fade" id="seccion2" role="tabpanel">
            <h3>Configurar Clase</h3>
            <form action="asignar_clase.php" method="POST">
                <div class="form-group">
                    <label>Seleccionar Entrenador</label>
                    <select class="form-control" name="id_entrenador">
                        <option>Juan Pérez (Cargado de DB)</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Tipo de Clase</label>
                        <input type="text" name="clase" class="form-control" placeholder="Ej. Zumba">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Hora Inicio</label>
                        <input type="time" name="hora_inicio" class="form-control" value="08:00">
                    </div>
                    <div class="form-group col-md-3">
                        <label>Hora Fin</label>
                        <input type="time" name="hora_fin" class="form-control" value="10:00">
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Asignar Horario</button>
            </form>
        </div>

        <div class="tab-pane fade" id="seccion3" role="tabpanel">
            <h3>Lista de Actividades</h3>
            <table class="table table-striped mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th>Entrenador</th>
                        <th>Clase</th>
                        <th>Horario</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Juan Pérez</td>
                        <td>Zumba</td>
                        <td>08:00 AM - 10:00 AM</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>