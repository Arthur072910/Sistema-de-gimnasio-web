<?php
require_once __DIR__ . '/../controller/EntrenadorController.php';

$controller = new EntrenadorController();

/* AGREGAR */
if(isset($_POST['accion']) && $_POST['accion'] == 'agregar'){
    $resultado = $controller->agregar($_POST);
    // Redirigir para evitar reenvío de formulario
    header('Location: entrenadores.php');
    exit();
}

/* ACTUALIZAR */
if(isset($_POST['accion']) && $_POST['accion'] == 'actualizar'){
    $resultado = $controller->actualizar($_POST);
    // Redirigir después de actualizar
    header('Location: entrenadores.php');
    exit();
}

/* ELIMINAR */
if(isset($_GET['eliminar'])){
    $controller->eliminar($_GET['eliminar']);
    // Redirigir después de eliminar
    header('Location: entrenadores.php');
    exit();
}

/* LISTAR */
$entrenadores = $controller->listar();

?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gestión de Entrenadores</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

  <!-- Tu CSS personalizado -->
  <link rel="stylesheet" href="../assets/css/entrenadores.css">
</head>

<body>

<div class="container-fluid">
  <div class="row">

    <!--  SIDEBAR -->
    <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 sidebar">
      <div class="logo-area text-center py-4">
        <img src="../assets/img/ChatGPT Image 30 ene 2026, 10_35_11 p.m..png" alt="Logo" style="height:100px;">
      </div>

      <ul class="nav flex-column nav-menu px-3">
        <li class="nav-item">
          <a href="#" class="nav-link active text-white">Dashboard</a>
        </li>
        <li class="nav-item">
          <a href="../view/registrouser.php" class="nav-link text-white">Registrar nuevos usuarios</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link text-white">Miembros</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link text-white">Clases</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link text-white">Pagos</a>
        </li>
        <li class="nav-item">
          <a href="../view/entrenadores.php" class="nav-link text-white">Registro de entrenadores</a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link text-white">Configuración</a>
        </li>
      </ul>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="col-md-9 col-lg-10 mt-4">

      <!-- Encabezado -->
      <header class="header mb-4">
        <h1>Gestión de Entrenadores</h1>
      </header>

      <!-- FORMULARIO -->
      <section class="card mb-4">
        <div class="card-body">
          <h4>Agregar Entrenador</h4>

          <form method="POST" action="">
            <input type="hidden" name="accion" value="agregar">

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Nombre completo</label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej. Juan Pérez" required>
              </div>

              <div class="form-group col-md-6">
                <label>Especialidad</label>
                <input type="text" name="especialidad" class="form-control" placeholder="Ej. CrossFit, Yoga" required>
              </div>

              <div class="form-group col-md-6">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control" placeholder="Ej. 7211-7898">
              </div>

              <div class="form-group col-md-6">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Ej. email@gmail.com" required>
              </div>

              <div class="form-group col-md-6">
                <label>Fecha de Registro</label>
                <input type="date" name="fecha_registro" class="form-control" required>
              </div>
            </div>

            <button type="submit" class="btn btn-primary">Agregar Entrenador</button>
          </form>
        </div>
      </section>

      <!-- TABLA -->
      <section class="card">
        <div class="card-body">
          <h4>Lista de Entrenadores</h4>

          <div class="table-responsive">
            <table class="table table-striped table-bordered">
              <thead class="thead-dark">
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Especialidad</th>
                  <th>Teléfono</th>
                  <th>Email</th>
                  <th>Fecha Registro</th>
                  <th>Acciones</th>
                </tr>
              </thead>

              <tbody>
                <?php if(count($entrenadores) > 0): ?>
                  <?php foreach($entrenadores as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['id_entrenador']); ?></td>
                        <td><?= htmlspecialchars($e['nombre']); ?></td>
                        <td><?= htmlspecialchars($e['especialidad']); ?></td>
                        <td><?= htmlspecialchars($e['telefono']); ?></td>
                        <td><?= htmlspecialchars($e['email']); ?></td>
                        <td><?= htmlspecialchars($e['fecha_registro']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalEditar"
                                onclick="cargarDatos(
                                    '<?= $e['id_entrenador']; ?>',
                                    '<?= htmlspecialchars($e['nombre']); ?>',
                                    '<?= htmlspecialchars($e['especialidad']); ?>',
                                    '<?= htmlspecialchars($e['telefono']); ?>',
                                    '<?= htmlspecialchars($e['email']); ?>',
                                    '<?= $e['fecha_registro']; ?>'
                                )">
                                Editar
                            </button>

                            <a href="?eliminar=<?= $e['id_entrenador']; ?>" class="btn btn-sm btn-danger"
                              onclick="return confirm('¿Seguro que deseas eliminar a este entrenador?')">
                              Eliminar
                            </a>
                        </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="7" class="text-center">No hay entrenadores registrados</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>

    </main>
  </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Entrenador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form method="POST" action="">
        <div class="modal-body">
            <input type="hidden" name="accion" value="actualizar">
            <input type="hidden" name="id_entrenador" id="edit_id">

            <div class="form-group">
              <label>Nombre completo</label>
              <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Especialidad</label>
              <input type="text" name="especialidad" id="edit_especialidad" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Teléfono</label>
              <input type="text" name="telefono" id="edit_telefono" class="form-control">
            </div>

            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" id="edit_email" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Fecha de Registro</label>
              <input type="date" name="fecha_registro" id="edit_fecha" class="form-control" required>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JS Bootstrap -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
function cargarDatos(id, nombre, especialidad, telefono, email, fecha) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_especialidad').value = especialidad;
    document.getElementById('edit_telefono').value = telefono;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_fecha').value = fecha;
}
</script>

</body>
</html>