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
        $_SESSION['usuario_id']    = $resultado['id_usuario']; 
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
    <div class="split-card">
        <!-- LADO IZQUIERDO - LOGO GRANDE -->
        <div class="split-left">
            <div class="left-content">
                <div class="logo-large">
                    <img src="../assets/img/logo_deluxgym.png" alt="Delux Gym">
                </div>
                <h1>¡Hola!</h1>
                <h2>Inicia sesión en tu cuenta</h2>
            </div>
        </div>
        
        <!-- LÍNEA SEPARADORA DORADA -->
        <div class="split-divider"></div>
        
        <!-- LADO DERECHO - FORMULARIO LOGIN -->
        <div class="split-right">
            <div class="login-card-compact">
                
                <?php if($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label>E-MAIL</label>
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
                        <label>CONTRASEÑA</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" 
                                   name="password" 
                                   class="form-control" 
                                   placeholder="··········"
                                   required>
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-login">
                        INICIAR SESIÓN
                    </button>
                </form>

                <div class="google-login">
                    <a href="google-auth.php" class="btn-google">
                        <i class="fab fa-google"></i>
                        CONTINUAR CON GOOGLE
                    </a>
                </div>
                
                <div class="divider">
                    <span class="divider-line"></span>
                    <span class="divider-text">O</span>
                    <span class="divider-line"></span>
                </div>
                
                <div class="register-section">
                    <p class="register-text">¿No tienes una cuenta?</p>
                    <a href="registro.php" class="register-link">
                        CREAR CUENTA
                    </a>
                </div>
                
            </div>
        </div>
    </div>
    
    <script>
    function togglePassword() {
        const passwordInput = document.querySelector('input[name="password"]');
        const toggleIcon = document.querySelector('.toggle-password i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    </script>
</body>
</html>