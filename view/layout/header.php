<nav class="navbar navbar-expand-sm navbar-dark" id="nav-1">
  <a class="navbar-brand" href="/Sistema-de-gimnasio-web/index.php">
    <img src="/Sistema-de-gimnasio-web/assets/img/logo_deluxGym.png" alt="Delux Gym">
  </a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavId">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="collapsibleNavId">
    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">

      <li class="nav-item">
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
          Administración
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="/Sistema-de-gimnasio-web/view/admin.php">Panel Admin</a>
        </div>
      </li>

      <?php if(isset($_SESSION['cliente_id'])): ?>
        <li class="nav-item">
          <a class="nav-link text-warning font-weight-bold" href="/Sistema-de-gimnasio-web/view/Perfil.php">
            👤 <?php echo htmlspecialchars($_SESSION['cliente_nombre'] ?? 'Usuario'); ?>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-danger btn-sm text-white ml-2" href="/Sistema-de-gimnasio-web/view/logout.php">
            Cerrar Sesión
          </a>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a class="nav-link btn btn-outline-warning btn-sm mx-1" href="/Sistema-de-gimnasio-web/view/login.php">
            🔐 Iniciar Sesión
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-warning btn-sm text-black mx-1" href="/Sistema-de-gimnasio-web/view/registro.php">
            📝 Registrarse
          </a>
        </li>
      <?php endif; ?>

    </ul>
  </div>
</nav>