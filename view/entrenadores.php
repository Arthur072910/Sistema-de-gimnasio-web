<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Gestión de Entrenadores</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

  <!-- Tu CSS personalizado -->
  <link rel="stylesheet" href="../assets/css/entrenadores.css">
</head>

<body>

<div class="container-fluid">
  <div class="row">

    <!--  SIDEBAR -->
    <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 sidebar">

      <div class="logo-area text-center py-4">
        <img src="../assets/img/ChatGPT Image 30 ene 2026, 10_35_11 p.m..png"
             alt="Logo"
             style="height:100px;">
      </div>

      <ul class="nav flex-column nav-menu px-3">

        <li class="nav-item">
          <a href="#" class="nav-link active text-white">Dashboard</a>
        </li>

        <li class="nav-item">
          <a href="../view/registrouser.php" class="nav-link text-white">
            Registrar nuevos usuarios
          </a>
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
          <a href="../view/entrenadores.php" class="nav-link text-white">
            Registro de entrenadores
          </a>
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

          <h4>Agregar / Editar Entrenador</h4>

          <form id="trainerForm">

            <div class="form-row">

              <div class="form-group col-md-6">
                <label>Nombre completo</label>
                <input type="text" class="form-control"
                       placeholder="Ej. Juan Pérez" required>
              </div>

              <div class="form-group col-md-6">
                <label>Especialidad</label>
                <input type="text" class="form-control"
                       placeholder="Ej. CrossFit, Yoga" required>
              </div>

              <div class="form-group col-md-6">
                <label>Teléfono</label>
                <input type="text" class="form-control"
                       placeholder="Ej. 7211-7898" required>
              </div>

              <div class="form-group col-md-6">
                <label>Correo electrónico</label>
                <input type="email" class="form-control"
                       placeholder="Ej. correo@gmail.com" required>
              </div>

              <div class="form-group col-md-6">
                <label>Fecha de Registro</label>
                <input type="date" class="form-control">
              </div>

            </div>

            <button type="submit" class="btn btn-primary">
              Agregar Entrenador
            </button>

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
                  <th>Correo</th>
                  <th>Fecha Registro</th>
                  <th>Acciones</th>
                </tr>
              </thead>

              <tbody>
                <!-- Datos dinámicos -->
              </tbody>

            </table>
          </div>

        </div>
      </section>

    </main>

  </div>
</div>

<!-- JS Bootstrap -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>
