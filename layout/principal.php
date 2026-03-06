
  <div id="carouselId" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselId" data-slide-to="0" class="active"></li>
        <li data-target="#carouselId" data-slide-to="1"></li>
        <li data-target="#carouselId" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="assets/img/gym1.jpg" class="d-block w-100" alt="Gimnasio 1">
        </div>
        <div class="carousel-item">
            <img src="assets/img/gym2.jpg" class="d-block w-100" alt="Gimnasio 2">
        </div>
        <div class="carousel-item">
            <img src="assets/img/como-hacer-amigos-en-el-gym.webp" class="d-block w-100" alt="Gimnasio 3">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselId" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Anterior</span>
    </a>
    <a class="carousel-control-next" href="#carouselId" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Siguiente</span>
    </a>
</div>

<!-- ==================== SOBRE NOSOTROS ==================== -->
<div class="container-nosotros">
    <img src="assets/img/peso-muerto-gym.jpg" alt="Sobre nosotros">
    <div class="container d-block">
        <h2>Sobre nosotros</h2>
        <h5>
            Delux Gym es un gimnasio moderno dedicado a ayudarte a mejorar tu salud, fuerza y bienestar. 
            Contamos con equipos de alta calidad, entrenadores certificados y planes personalizados para 
            que alcances tus objetivos de forma segura y efectiva.
        </h5>
    </div>
</div>

<!-- ==================== NUESTROS PLANES ==================== -->
<!-- ==================== NUESTROS PLANES ==================== -->
<div class="wp-dev-pricing">
    <h1 style="text-align: center;">Nuestros planes</h1>

    <?php
    // Incluir la conexión a la base de datos
    require_once 'config/database.php';
    
    // Crear instancia de la clase Database y obtener la conexión
    $database = new Database();
    $conn = $database->getConnection();
    
    // Consulta para obtener las membresías activas
    $sql_membresias = "SELECT id_tipo_membresia, nombre, descripcion, precio, duracion_dias 
                      FROM tipo_membresia 
                      WHERE estado = 1 
                      ORDER BY precio ASC";
    
    $stmt = $conn->prepare($sql_membresias);
    $stmt->execute();
    $result_membresias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="pricing-row">
        <?php
        if (count($result_membresias) > 0) {
            $contador = 0;
            foreach($result_membresias as $membresia) {
                $contador++;
                // El segundo plan será el "popular"
                $popular_class = ($contador == 2) ? 'popular' : '';
                
                // Dividir la descripción en items (asumiendo que están separadas por comas)
                $caracteristicas = explode(',', $membresia['descripcion']);
                ?>
                
                <!-- Plan <?php echo $membresia['nombre']; ?> -->
                <div class="pricing-column">
                    <div class="pricing-card <?php echo $popular_class; ?>">
                        <h3><?php echo htmlspecialchars($membresia['nombre']); ?></h3>
                        <div class="price">
                            <span class="annual-price">$<?php echo number_format($membresia['precio'], 0); ?></span> /mes
                        </div>
                        <ul>
                            <?php
                            // Mostrar las características de la membresía
                            foreach($caracteristicas as $caracteristica) {
                                $caracteristica = trim($caracteristica);
                                if(!empty($caracteristica)) {
                                    echo '<li>✓ ' . htmlspecialchars($caracteristica) . '</li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <?php
            }
        } else {
            // Si no hay membresías, mostrar mensaje
            echo '<div style="text-align: center; width: 100%; padding: 20px;">';
            echo '<p>No hay planes disponibles en este momento.</p>';
            echo '</div>';
        }
        ?>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="view/plan.php" class="btn unete">Únete a nosotros</a>
    </div>
</div>

<!-- ==================== NUESTRAS INSTALACIONES ==================== -->
<section class="instalaciones-section">
    <h1 style="text-align: center;">Nuestras instalaciones</h1>

    <div class="hero-section">
        <div class="card-grid">
            <a class="card" href="#">
                <div class="card__background" style="background-image: url(assets/img/cardio.avif);"></div>
                <div class="card__content">
                    <p class="card__category">ENTRENAMIENTO PERSONAL</p>
                    <h3 class="card__heading">
                        Rutinas personalizadas según tu nivel y objetivos.
                    </h3>
                </div>
            </a>

            <a class="card" href="#">
                <div class="card__background" style="background-image: url(assets/img/sentadilla.jpeg);"></div>
                <div class="card__content">
                    <p class="card__category">CARDIO</p>
                    <h3 class="card__heading">
                        Equipos modernos para mejorar tu resistencia.
                    </h3>
                </div>
            </a>

            <a class="card" href="#">
                <div class="card__background" style="background-image: url(assets/img/nutricion.webp);"></div>
                <div class="card__content">
                    <p class="card__category">NUTRICIÓN</p>
                    <h3 class="card__heading">
                        Planes de alimentación y asesoría nutricional.
                    </h3>
                </div>
            </a>

            <a class="card" href="#">
                <div class="card__background" style="background-image: url(assets/img/AEROBICOS.jpg);"></div>
                <div class="card__content">
                    <p class="card__category">CLASES GRUPALES</p>
                    <h3 class="card__heading">
                        Zumba, spinning y funcional para entrenar con energía.
                    </h3>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- ==================== CONTÁCTANOS ==================== -->
<div class="contactos-container">
    <div class="contacto-horizontal">
        <div class="container-img-contactos">
            <img src="assets/img/caminadora.jpg" alt="Caminadora">
        </div>

        <div class="container-mensaje">
            <h3>Preguntas o inquietud</h3>
            
            <form id="formContacto" action="view/correos/ProcesarContacto.php" method="POST">
                
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <div style="color: #ffd700; margin-bottom: 15px; border: 1px solid #333; padding: 10px; background: rgba(255,215,0,0.05);">
                        <i class="fas fa-user-circle"></i> 
                        Hola, <strong><?php echo $_SESSION['cliente_nombre']; ?></strong>. 
                    </div>
                    <input type="hidden" name="nombre" value="<?php echo $_SESSION['cliente_nombre']; ?>">
                    <input type="hidden" name="email" value="<?php echo $_SESSION['cliente_email']; ?>">

                <?php else: ?>
                    <input type="text" name="nombre" placeholder="NOMBRE" class="input-inquietud" required>
                    <input type="email" name="email" placeholder="CORREO" class="input-inquietud" required>
                <?php endif; ?>

                <textarea name="mensaje" placeholder="ESCRIBE TU MENSAJE" class="input-inquietud textarea-inquietud" required></textarea>
                
                <button type="submit" class="btn-submit">Enviar mensaje</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/contacto.js"></script>