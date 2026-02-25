<?php
// Evita doble session_start si ya fue llamado antes
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_url = '/Sistema-de-gimnasio-web';
$rol = $_SESSION['rol'] ?? 'visitante';
$nombreUsuario = $_SESSION['cliente_nombre'] ?? 'Usuario';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/header.css">
<nav class="navbar navbar-expand-sm navbar-dark" id="nav-1">
  <a class="navbar-brand" href="<?php echo $base_url; ?>/index.php">
    <img src="<?php echo $base_url; ?>/assets/img/logo_deluxGym.png" alt="Delux Gym" style="height: 60px;">
  </a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavId">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="collapsibleNavId">
    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">

      <li class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="<?php echo $base_url; ?>/index.php">Inicio</a>
      </li>

      <li class="nav-item <?php echo ($current_page == 'productos.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="<?php echo $base_url; ?>/view/productos.php">Tienda</a>
      </li>
      <li class="nav-item <?php echo ($current_page == 'asistencia.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="<?php echo $base_url; ?>/view/asistencia.php">Asistencia</a>
      </li>

      <?php if($rol != 'visitante'): ?>
      <li class="nav-item <?php echo ($current_page == 'carrito.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="<?php echo $base_url; ?>/view/carrito.php">Carrito</a>
      </li>
      <?php endif; ?>

      <?php if($rol == 'administrador' || $rol == 'recepcionista'): ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown">
          Administración
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item <?php echo ($current_page == 'admin.php') ? 'active' : ''; ?>" 
             href="<?php echo $base_url; ?>/view/admin.php">Panel Administrador</a>
        </div>
      </li>
      <?php endif; ?>

      <?php if($rol != 'visitante'): ?>
        <li class="nav-item <?php echo ($current_page == 'Perfil.php') ? 'active' : ''; ?>">
          <a class="nav-link text-warning font-weight-bold" href="<?php echo $base_url; ?>/view/Perfil.php">
            <?php echo htmlspecialchars($nombreUsuario); ?>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-outline-danger btn-sm ml-2" href="<?php echo $base_url; ?>/view/logout.php">
            Cerrar Sesión
          </a>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a class="nav-link btn btn-outline-warning btn-sm mx-1" href="<?php echo $base_url; ?>/view/login.php">
            Iniciar Sesión
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-warning btn-sm text-dark mx-1" href="<?php echo $base_url; ?>/view/registro.php">
            Registrarse
          </a>
        </li>
      <?php endif; ?>

    </ul>
  </div>
</nav>