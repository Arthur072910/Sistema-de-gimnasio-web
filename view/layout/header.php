<nav class="navbar navbar-expand-sm navbar-light bg-light" id="nav-1">
  
  <img src="/Sistema-de-gimnasio-web/assets/img/logo_deluxGym.png" style="height: 100px;">

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavId">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="collapsibleNavId">
    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">

      <li class="nav-item active">
        <a class="nav-link" href="/Sistema-de-gimnasio-web/index.php">Inicio</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="/Sistema-de-gimnasio-web/view/productos.php">Tienda</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="/Sistema-de-gimnasio-web/view/carrito.php">Carrito</a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown">
          Administracion
        </a>

        <div class="dropdown-menu">
          <a class="dropdown-item" href="/Sistema-de-gimnasio-web/view/admin.php">Admin</a>
          <a class="dropdown-item" href="#">No se2</a>
        </div>
      </li>

      <?php if(isset($_SESSION['cliente_id'])): ?>
        <li class="nav-item">
          <a class="nav-link text-primary font-weight-bold" href="/Sistema-de-gimnasio-web/view/Perfil.php">
          <span class="nav-link text-primary font-weight-bold">
            👤 <?php echo $_SESSION['cliente_nombre'] ?? 'Usuario'; ?>
          </span>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-danger btn-sm text-white"
             href="/Sistema-de-gimnasio-web/view/logout.php">
            Cerrar Sesión
          </a>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a class="nav-link btn btn-outline-primary btn-sm mx-1"
             href="/Sistema-de-gimnasio-web/view/login.php">
            🔐 Iniciar Sesión
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-primary btn-sm text-white mx-1"
             href="/Sistema-de-gimnasio-web/view/registro.php">
            📝 Registrarse
          </a>
        </li>
      <?php endif; ?>

    </ul>
  </div>
</nav>
