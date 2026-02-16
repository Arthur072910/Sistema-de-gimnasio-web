<?php
session_start();

if(isset($_SESSION['cliente_id'])) {
    header("Location: ../index.php");
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Aquí tu lógica de autenticación
    if($email == 'demo@deluxgym.com' && $password == '1234') {
        $_SESSION['cliente_id'] = 1;
        $_SESSION['cliente_nombre'] = 'Usuario Demo';
        $_SESSION['cliente_email'] = $email;
        header("Location: ../index.php");
        exit();
    } else {
        $error = 'Email o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Delux Gym</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            

            <div class="logo">
                <img src="../assets/img/ChatGPT Image 30 ene 2026, 10_35_11 p.m..png" alt="Delux Gym">
                <h1>Delux Gym</h1>
                <p>BIENVENIDO DE VUELTA</p>
            </div>
            

            <?php if($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error; ?></span>
            </div>
            <?php endif; ?>
            

            <form method="POST" id="loginForm">
                <div class="form-group">
                    <label>
                        <i class="fas fa-envelope"></i>
                        Email
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               id="email"
                               name="email" 
                               class="form-control" 
                               placeholder="tu@email.com"
                               value="<?php echo $_POST['email'] ?? ''; ?>"
                               required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-lock"></i>
                        Contraseña
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               id="password"
                               name="password" 
                               class="form-control" 
                               placeholder="••••••••"
                               required>
                    </div>
                </div>
                
                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" id="remember" name="remember">
                        <span>Recordarme</span>
                    </label>
                    <a href="#" class="forgot">¿Olvidaste tu contraseña?</a>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>INICIAR SESIÓN</span>
                </button>
            </form>
            
            <div class="divider">
                <span class="divider-line"></span>
                <span class="divider-text">¿Nuevo aquí?</span>
                <span class="divider-line"></span>
            </div>
            
            <div class="register-section">
                <p class="register-text">Únete a la comunidad Delux Gym</p>
                <a href="registro.php" class="register-link">
                    <span>CREAR CUENTA</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="terms">
                Al iniciar sesión aceptas nuestros 
                <a href="#">Términos</a> y 
                <a href="#">Política de Privacidad</a>
            </div>
            
        </div>
    </div>
    
    <!-- JS Personalizado -->
    <script src="../assets/js/login.js"></script>
</body>
</html>