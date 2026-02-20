<?php
$base_url = '/Sistema-de-gimnasio-web';
$ruta_assets = $base_url . '/assets';
?>
<div class="footer-area">
    <footer class="footer">
        
        <!-- CONTENIDO SUPERIOR -->
        <div class="footer-content">
            
            <!-- Marca -->
            <div class="footer-brand">
                <span>© 2026 Delux Gym</span>
                <a href="<?php echo $base_url; ?>/index.php">deluxgym.com</a>
            </div>
            
            <!-- Navegación -->
            <div class="footer-nav">
                <a href="<?php echo $base_url; ?>/index.php">Inicio</a>
                <a href="<?php echo $base_url; ?>/view/productos.php">Tienda</a>
                <a href="<?php echo $base_url; ?>/view/contactos.php">Contactos</a>
            </div>
            
            <!-- Redes Sociales -->
            <div class="footer-social">
                <a class="social-icon" href="https://facebook.com" target="_blank">
                    <img src="<?php echo $ruta_assets; ?>/img/facebook (1).png" alt="Facebook">
                </a>
                <a class="social-icon" href="https://instagram.com" target="_blank">
                    <img src="<?php echo $ruta_assets; ?>/img/instagram.png" alt="Instagram">
                </a>
            </div>
            
        </div>
        
        <!-- CONTENIDO INFERIOR -->
        <div class="footer-bottom">
            
            <!-- Contacto -->
            <div class="footer-contact">
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>info@deluxgym.com</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>+503 1234-5678</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>San Salvador, ES</span>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="footer-copyright">
                Diseñado con pasión por el <strong>Grupo 1</strong>
            </div>
            
        </div>
        
    </footer>
</div>


<link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/footer.css">