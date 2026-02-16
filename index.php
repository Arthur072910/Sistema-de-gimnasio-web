<?php session_start(); ?>
<!doctype html>
<html lang="en">

<head>
  <title>Delux Gym - Inicio</title>

  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <link rel="stylesheet" href="assets/css/index.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-sm navbar-light bg-light" id="nav-1">
    <img src="assets/img/ChatGPT Image 30 ene 2026, 10_35_11 p.m..png" style="height: 100px;">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavId">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="collapsibleNavId">
      <ul class="navbar-nav ml-auto mt-2 mt-lg-0">

        <li class="nav-item active">
          <a class="nav-link" href="#">Inicio</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="view/productos.php">Tienda</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="view/Perfil.php">Perfil</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="view/carrito.php">Carrito</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown">
            Administracion
          </a>

          <div class="dropdown-menu">
            <a class="dropdown-item" href="view/admin.php">Admin</a>
            <a class="dropdown-item" href="">No se2</a>
          </div>
        </li>

        <!-- Mostrar opciones según login -->
        <?php if(isset($_SESSION['cliente_id'])): ?>
          <li class="nav-item">
            <span class="nav-link text-primary font-weight-bold">
              👤 <?php echo $_SESSION['cliente_nombre'] ?? 'Usuario'; ?>
            </span>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-danger btn-sm text-white" href="view/logout.php">
              Cerrar Sesión
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link btn btn-outline-primary btn-sm mx-1" href="view/login.php">
              🔐 Iniciar Sesión
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-primary btn-sm text-white mx-1" href="view/registro.php">
              📝 Registrarse
            </a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </nav>

  <!-- CAROUSEL -->
  <div id="carouselId" class="carousel slide" data-ride="carousel">

    <ol class="carousel-indicators">
      <li data-target="#carouselId" data-slide-to="0" class="active"></li>
      <li data-target="#carouselId" data-slide-to="1"></li>
      <li data-target="#carouselId" data-slide-to="2"></li>
    </ol>

    <div class="carousel-inner">

      <div class="carousel-item active">
        <img src="assets/img/gym1.jpg" style="width: 100%; height: auto;">
      </div>

      <div class="carousel-item">
        <img src="assets/img/gym2.jpg" style="width: 100%; height: auto;">
      </div>

    </div>

    <a class="carousel-control-prev" href="#carouselId" data-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </a>

    <a class="carousel-control-next" href="#carouselId" data-slide="next">
      <span class="carousel-control-next-icon"></span>
    </a>

  </div>

  <div class="container-nosotros">

    <img src="assets/img/peso-muerto-gym.jpg" alt="" srcset="">
    <div class="container d-block">
       <h2>Sobre nosotros</h2>
      <h5>
        Delux Gym es un gimnasio moderno dedicado a ayudarte a mejorar tu salud, fuerza y bienestar. 
        Contamos con equipos de alta calidad, entrenadores certificados y planes personalizados para 
        que alcances tus objetivos de forma segura y efectiva.
      </h5>
    </div>

  </div>

  <!-- PRICING -->
  <div class="wp-dev-pricing">
    <h1 style="text-align: center;">Nuestros planes</h1>

    <div class="pricing-row">

      <!-- Basic Plan -->
      <div class="pricing-column">
        <div class="pricing-card">
          <h3>Starter Website</h3>

          <div class="price">
            <span class="annual-price">$499</span>
            <span class="monthly-price">$49/mo</span>
          </div>

          <ul>
            <li>✓ 5 Page Website</li>
            <li>✓ Mobile Responsive</li>
            <li>✓ Basic SEO</li>
            <li>✓ Contact Form</li>
            <li>✓ 1 Month Support</li>
          </ul>
        </div>
      </div>

      <!-- Popular Plan -->
      <div class="pricing-column">
        <div class="pricing-card popular">
          <h3>Business Site</h3>

          <div class="price">
            <span class="annual-price">$999</span>
            <span class="monthly-price">$99/mo</span>
          </div>

          <ul>
            <li>✓ 15 Page Website</li>
            <li>✓ Elementor Pro</li>
            <li>✓ Advanced SEO</li>
            <li>✓ E-commerce Ready</li>
            <li>✓ 3 Months Support</li>
          </ul>
        </div>
      </div>

      <!-- Premium Plan -->
      <div class="pricing-column">
        <div class="pricing-card">
          <h3>E-commerce Store</h3>

          <div class="price">
            <span class="annual-price">$1,999</span>
            <span class="monthly-price">$199/mo</span>
          </div>

          <ul>
            <li>✓ WooCommerce Setup</li>
            <li>✓ Payment Gateway</li>
            <li>✓ Product Management</li>
            <li>✓ Premium Security</li>
            <li>✓ 6 Months Support</li>
          </ul>
        </div>
      </div>
    </div>

    <a href="#contact" class="btn unete">Unete a nosotros</a>
  </div>

  <!-- CARDS -->
  <section>
    <h1 style="text-align: center;">Nuestras instalaciones</h1>

    <div class="hero-section">
      <div class="card-grid">

        <a class="card" href="#">
          <div class="card__background" style="background-image: url(assets/img/mature-adult-man-working-out-at-personal-training-royalty-free-image-1573720585.avif)"></div>
          <div class="card__content">
            <p class="card__category">ENTRENAMIENTO PERSONAL</p>
            <h3 class="card__heading">
              Rutinas personalizadas según tu nivel y objetivos, con seguimiento profesional para maximizar tus resultados.
            </h3>
          </div>
        </a>

        <a class="card" href="#">
          <div class="card__background" style="background-image: url(assets/img/cardio.avif)"></div>
          <div class="card__content">
            <p class="card__category">CARDIO</p>
            <h3 class="card__heading">
              Equipos modernos para mejorar tu resistencia, fortalecer tu corazón y mantener una vida activa.
            </h3>
          </div>
        </a>

        <a class="card" href="#">
          <div class="card__background" style="background-image: url(assets/img/nutricion.webp)"></div>
          <div class="card__content">
            <p class="card__category">NUTRICION</p>
            <h3 class="card__heading">
              Planes de alimentación saludables y asesoría nutricional para complementar tu entrenamiento.
            </h3>
          </div>
        </a>

        <a class="card" href="#">
          <div class="card__background" style="background-image: url(assets/img/AEROBICOS.jpg)"></div>
          <div class="card__content">
            <p class="card__category">CLASES GRUPALES</p>
            <h3 class="card__heading">
              Disfruta de clases dinámicas como zumba, spinning y funcional, ideales para entrenar con energía y motivación.
            </h3>
          </div>
        </a>

      </div>
    </div>
  </section>

  <!-- CONTACTOS -->
  <div class="contactos-container" style="text-align: center;">
    <h2>CONTACTANOS</h2>

    <div class="formulario-container">
      <h4>CORREO ELECTRONICO</h4>
      <h6>deluxgym2026@gmail.com</h6>

      <h4>NUMERO DE TELEFONO</h4>
      <h6>+503 1111-1111</h6>
    </div>

    <div class="container-img-contactos">
      <img src="assets/img/caminadora.jpg" alt="" srcset="">
    </div>

    <div class="container-mensaje">
      <h3 style="text-align: center;">Preguntas o inquietud</h3>

      <form action="">
        <input type="text" placeholder="NOMBRE" class="input-inquietud">
        <input type="text" placeholder="CORREO" class="input-inquietud">

        <div class="usuario-group">
          <label>
            <input type="radio" name="usuario" checked>
            SOY USUARIO
          </label>

          <label>
            <input type="radio" name="usuario">
            NO SOY USUARIO
          </label>

          <textarea placeholder="ESCRIBE TU MENSAJE" class="input-inquietud textarea-inquietud"></textarea>

          <button type="submit" class="btn-submit">
            Enviar mensaje
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="footer-area">
    <footer class="footer text-center text-lg-start">

      <div class="brand">
        <span>© 2024 deluxgym</span>
        <a href="#" class="ml-2">deluxgym.com</a>
      </div>

      <div class="links">
        <a href="#">Inicio</a>
        <a href="">Catálogo</a>
        <a href="view/contactos.php">Contactos</a>
      </div>

      <div class="redes-area">
        <a class="redes-img" href="#"><img src="assets/img/facebook (1).png" alt="Facebook"></a>
        <a class="redes-img" href="#"><img src="assets/img/instagram.png" alt="Instagram"></a>
      </div>

      <div class="copyright">
        Diseñado por deluxgym
      </div>

    </footer>
  </div>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>