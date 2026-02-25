<?php
// Detecta el nombre del archivo actual (ej: entrenadores.php)
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>

<div class="gym-sidebar">
    <div class="logo-area text-center py-4">
        <img src="../assets/img/logo_deluxGym.png" alt="Logo" style="height:80px;">
    </div>
    
    <nav class="nav flex-column">
        <a class="nav-link <?php echo ($pagina_actual == 'index.php' || $pagina_actual == 'admin.php') ? 'active' : ''; ?>" href="admin.php">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <a class="nav-link <?php echo ($pagina_actual == 'entrenadores.php') ? 'active' : ''; ?>" href="entrenadores.php">
            <i class="fas fa-user-tie"></i> Entrenadores
        </a>

        <a class="nav-link <?php echo ($pagina_actual == 'miembros.php') ? 'active' : ''; ?>" href="#">
            <i class="fas fa-users"></i> Miembros
        </a>

        <a class="nav-link <?php echo ($pagina_actual == 'clasess.php') ? 'active' : ''; ?>" href="clasess.php">
            <i class="fas fa-chalkboard-teacher"></i> Clases
        </a>

        <a class="nav-link <?php echo ($pagina_actual == 'productos.php') ? 'active' : ''; ?>" href="#">
            <i class="fas fa-box"></i> Productos
        </a>

        <a class="nav-link <?php echo ($pagina_actual == 'pagos.php') ? 'active' : ''; ?>" href="#">
            <i class="fas fa-credit-card"></i> Pagos
        </a>

        <a class="nav-link <?php echo ($pagina_actual == 'membresias.php') ? 'active' : ''; ?>" href="#">
            <i class="fas fa-id-card"></i> Planes
        </a>
    </nav>
</div>