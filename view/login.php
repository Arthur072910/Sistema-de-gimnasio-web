<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/../controller/ClienteController.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller = new ClienteController();
    $resultado = $controller->login($_POST['email'], $_POST['password']);
    
    if($resultado['success']) {
<<<<<<< HEAD
        $_SESSION['usuario_id']    = $resultado['id_usuario']; 
=======
        $_SESSION['usuario_id']    = $resultado['id_usuario'];
>>>>>>> origin/BackEnd1
        $_SESSION['cliente_id']    = $resultado['id_cliente'] ?? null;
        $_SESSION['cliente_nombre'] = $resultado['nombre'];
        $_SESSION['cliente_email'] = $resultado['email'];
        $_SESSION['rol']           = $resultado['rol'];
        
        header("Location: ../index.php");
        exit();
    } else {
        $error = $resultado['message'];
    }
}

// Verificar si hay error de Google
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'google_error') {
        $error = "Error al iniciar sesión con Google. Intenta de nuevo.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Delux Gym</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            
            <div class="logo">
                <img src="../assets/img/logo_deluxgym.png" alt="Delux Gym">
                <h1>Delux Gym</h1>
                <p>BIENVENIDO DE VUELTA</p>
            </div>
            
            <?php if($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="tu@email.com" 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                               required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Contraseña</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               name="password" 
                               class="form-control" 
                               placeholder="••••••••"
                               required>
                    </div>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> INICIAR SESIÓN
                </button>
            </form>

            <!-- ======================================== -->
            <!-- BOTÓN DE GOOGLE - NUEVO                  -->
            <!-- ======================================== -->
            <div class="google-login">
                <a href="google-auth.php" class="btn-google">
                    <i class="fab fa-google"></i>
                    CONTINUAR CON GOOGLE
                </a>
            </div>
            
            <!-- ======================================== -->
            <!-- SEPARADOR                                -->
            <!-- ======================================== -->
            <div class="divider">
                <span class="divider-line"></span>
                <span class="divider-text">O</span>
                <span class="divider-line"></span>
            </div>
            
            <div class="register-section">
                <p class="register-text">Únete a la comunidad Delux Gym</p>
                <a href="registro.php" class="register-link">
                    CREAR CUENTA <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="terms">
                Al iniciar sesión aceptas nuestros 
                <a href="#">términos</a> y 
                <a href="#">política de privacidad</a>
            </div>
            
        </div>
    </div>
    
    <script src="../assets/js/login.js"></script>
</body>
</html>