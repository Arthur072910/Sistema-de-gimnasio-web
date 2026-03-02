<?php
// Evita doble session_start si ya fue llamado antes
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_url = '/Sistema-de-gimnasio-web';
$rol = $_SESSION['rol'] ?? 'visitante';
$nombreUsuario = $_SESSION['cliente_nombre'] ?? 'Usuario';
$current_page = basename($_SERVER['PHP_SELF']);

// Detectar si estamos en la página del carrito
$en_carrito = ($current_page == 'carrito.php');
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

      <!-- ASISTENCIA - SOLO PARA ADMINISTRADOR -->
      <?php if($rol == 'administrador'): ?>
      <li class="nav-item <?php echo ($current_page == 'asistencia.php') ? 'active' : ''; ?>">
        <a class="nav-link" href="<?php echo $base_url; ?>/view/asistencia.php">Asistencia</a>
      </li>
      <?php endif; ?>

      <?php if($rol != 'visitante'): ?>
      <li class="nav-item <?php echo ($current_page == 'carrito.php') ? 'active' : ''; ?>">
        <a class="nav-link cart-link" href="<?php echo $base_url; ?>/view/carrito.php">
          <i class="fas fa-shopping-cart"></i> Carrito
          <?php if(!$en_carrito): // Solo mostrar contador si NO estamos en carrito ?>
            <span class="cart-badge" id="cart-count">0</span>
          <?php endif; ?>
        </a>
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

<!-- Estilos mejorados para el badge del carrito -->
<style>
.cart-link {
    position: relative !important;
    padding-right: 5px !important;
    display: flex !important;
    align-items: center !important;
    gap: 5px !important;
}

.cart-link i {
    color: var(--gold);
    font-size: 1.1rem;
    transition: transform 0.3s ease;
}

.cart-link:hover i {
    transform: scale(1.2);
}

.cart-badge {
    position: relative;
    top: auto;
    right: auto;
    background: linear-gradient(135deg, #ffd700, #ffb347);
    color: #000000;
    font-size: 0.7rem;
    font-weight: 800;
    min-width: 20px;
    height: 20px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
    box-shadow: 0 2px 8px rgba(255, 215, 0, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.3);
    animation: cartPulse 1.5s infinite;
    margin-left: 3px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

@keyframes cartPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.7);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(255, 215, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 215, 0, 0);
    }
}

/* Cuando hay items, el badge brilla más */
.cart-badge:not(:empty) {
    background: linear-gradient(135deg, #ffd700, #ffa500);
    border: 1px solid white;
}

/* Ocultar badge cuando está vacío (lo manejamos con PHP y JS) */
.cart-badge[style*="display: none"] {
    display: none !important;
}

/* Responsive */
@media (max-width: 768px) {
    .cart-link {
        justify-content: center;
    }
}
</style>