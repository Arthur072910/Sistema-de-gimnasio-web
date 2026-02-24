<?php
session_start();

// ✅ CAMBIO AQUÍ: Verificar usuario_id en lugar de cliente_id
if(!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <title>Delux Gym - Tienda</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/tienda.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>
<body>
    <?php include "../layout/header.php"; ?>

    <!-- contenedor de suplementos -->
    <div class="contenedor-texto">
        <h1 class="texto">Suplementos y Nutrición</h1>
    </div>
    
    <!-- primer apartado de suplementos -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/proteinawhey.webp" alt="Proteína Whey">
                    <div class="card-body text-center">
                        <h4 class="card-title">Proteína Whey</h4>
                        <p class="card-text">Aísla de suero de alta calidad.</p>
                        <h5 class="font-weight-bold">$45.00</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/Creatina-platinum.jpg" alt="Creatina">
                    <div class="card-body text-center">
                        <h4 class="card-title">Creatina</h4>
                        <p class="card-text">Aumenta tu fuerza y resistencia.</p>
                        <h5 class="font-weight-bold">$30.00</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/pre.webp" alt="Pre-Workout">
                    <div class="card-body text-center">
                        <h4 class="card-title">Pre-Workout</h4>
                        <p class="card-text">Energía explosiva para entrenar.</p>
                        <h5 class="font-weight-bold">$35.00</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/Amino-BCAAs-_-EAAs-RL_fc7f0f17-e656-494b-977e-6032e0a6794a.webp" alt="Aminoácidos">
                    <div class="card-body text-center">
                        <h4 class="card-title">Aminoácidos (BCAAs/EAAs)</h4>
                        <p class="card-text">Recuperación muscular rápida y reducción de la fatiga durante el entreno.</p>
                        <h5 class="font-weight-bold">$35.00</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>
        </div> 
    </div>

    <!-- segundo apartado de suplementos -->
    <div class="container my-3">
        <div class="row justify-content-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/quemadores_web_1_1200x1200.webp" alt="Quemadores">
                    <div class="card-body text-center">
                        <h4 class="card-title">Quemadores de Grasa</h4>
                        <p class="card-text">Acelera tu metabolismo y define cada músculo.</p>
                        <h5 class="font-weight-bold">$29.00</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/46051_01.webp" alt="Multivitamínicos">
                    <div class="card-body text-center">
                        <h4 class="card-title">Multivitamínicos / Omega 3</h4>
                        <p class="card-text">Blindaje total para tu salud.</p>
                        <h5 >$15.00</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/smart_nutrition_linea-whey_wheypure5lb_fresa_1.jpg" alt="Servicio de Proteína">
                    <div class="card-body text-center">
                        <h4 class="card-title">Servicio de Proteína</h4>
                        <p class="card-text">Nutrición pura en un solo sorbo.</p>
                        <h5 class="font-weight-bold">$2.50</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- contenedor de accesorios -->
    <div class="contenedor-texto">
        <h1 class="texto">Accesorios y Equipo</h1>
    </div>
    
    <!-- primer apartado de accesorios -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/Custom-Logo-500ml-Protein-Shaker-Bottle-BPA-Portable-Mixing-Cup-for-Gym-Fitness-Wholesale.jpg_300x300.avif" alt="Shaker">
                    <div class="card-body text-center">
                        <h4 class="card-title">Shakers (Mezcladores)</h4>
                        <p class="card-text">Mezclador de proteína portátil.</p>
                        <h5 class="font-weight-bold">$10.00</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/guantes.jpg" alt="Guantes">
                    <div class="card-body text-center">
                        <h4 class="card-title">Guantes de pesas</h4>
                        <p class="card-text">Protección y agarre superior.</p>
                        <h5 class="font-weight-bold">$15.00</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/Cinturon-de-Soporte-Lumbar-4pulg-JYR-900.jpg" alt="Cinturón">
                    <div class="card-body text-center">
                        <h4 class="card-title">Cinturón lumbar</h4>
                        <p class="card-text">Estabilidad en levantamientos pesados.</p>
                        <h5 class="font-weight-bold">$35.00</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/Straps (Agarre).jpg" alt="Straps">
                    <div class="card-body text-center">
                        <h4 class="card-title">Straps (Agarre)</h4>
                        <p class="card-text">Mejora tu agarre en peso muerto.</p>
                        <h5 class="font-weight-bold">$10.00</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>
        </div> 
    </div>

    <!-- segundo apartado de accesorios -->
    <div class="container my-3">
        <div class="row justify-content-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/Bandas de Resistencia.webp" alt="Bandas">
                    <div class="card-body text-center">
                        <h4 class="card-title">Bandas de Resistencia</h4>
                        <p class="card-text">Versatilidad total para tu entrenamiento.</p>
                        <h5 class="font-weight-bold">$15.00</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/Cuerdas para saltar.jpg" alt="Cuerdas">
                    <div class="card-body text-center">
                        <h4 class="card-title">Cuerdas para saltar</h4>
                        <p class="card-text">Cardio y agilidad a máxima velocidad.</p>
                        <h5 class="font-weight-bold">$15.00</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <img class="card-img-custom" src="../assets/img/Magnesio.png" alt="Magnesio">
                    <div class="card-body text-center">
                        <h4 class="card-title">Magnesio</h4>
                        <p class="card-text">Agarre de hierro para tus entrenamientos.</p>
                        <h5 class="font-weight-bold">$10.00</h5>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="#" class="btn btn-warning btn-block font-weight-bold">COMPRAR</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer (RUTA CORREGIDA) -->
    <?php include "../layout/footer.php"; ?>

    <!-- Scripts -->
    <script src="../assets/js/carrito.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>