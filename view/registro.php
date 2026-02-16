<?php
session_start();

if(isset($_SESSION['cliente_id'])) {
    header("Location: ../index.php");
    exit();
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Aquí tu lógica de registro
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    
    // Validación básica
    if($nombre && $apellido && $email && $password) {
        // Simulación de registro exitoso
        $success = '¡Registro exitoso! Serás redirigido al login.';
        header("refresh:3;url=login.php");
    } else {
        $error = 'Todos los campos obligatorios deben estar completos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Delux Gym</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="../assets/css/registro.css">
</head>
<body>
    <div class="registro-container">
        <div class="registro-card">
            
            <!-- LOGO -->
            <div class="logo">
                <img src="../assets/img/ChatGPT Image 30 ene 2026, 10_35_11 p.m..png" alt="Delux Gym">
                <h1>Delux Gym</h1>
                <p>CREA TU CUENTA</p>
            </div>
            
            <!-- MENSAJES -->
            <?php if($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error; ?></span>
            </div>
            <?php endif; ?>
            
            <?php if($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $success; ?></span>
            </div>
            <?php endif; ?>
            
            <!-- FORMULARIO -->
            <form method="POST" id="registroForm">
                
                <!-- NOMBRE Y APELLIDO -->
                <div class="form-row">
                    <div class="form-group">
                        <label>
                            <i class="fas fa-user"></i>
                            Nombre <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" 
                                   id="nombre"
                                   name="nombre" 
                                   class="form-control" 
                                   placeholder="Juan"
                                   value="<?php echo $_POST['nombre'] ?? ''; ?>"
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <i class="fas fa-user"></i>
                            Apellido <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" 
                                   id="apellido"
                                   name="apellido" 
                                   class="form-control" 
                                   placeholder="Pérez"
                                   value="<?php echo $_POST['apellido'] ?? ''; ?>"
                                   required>
                        </div>
                    </div>
                </div>
                
                <!-- EMAIL -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-envelope"></i>
                        Email <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               id="email"
                               name="email" 
                               class="form-control" 
                               placeholder="ejemplo@correo.com"
                               value="<?php echo $_POST['email'] ?? ''; ?>"
                               required>
                    </div>
                </div>
                
                <!-- CONTRASEÑA -->
                <div class="form-row">
                    <div class="form-group">
                        <label>
                            <i class="fas fa-lock"></i>
                            Contraseña <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" 
                                   id="password"
                                   name="password" 
                                   class="form-control" 
                                   placeholder="Mínimo 6 caracteres"
                                   minlength="6"
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <i class="fas fa-lock"></i>
                            Confirmar <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" 
                                   id="confirmar"
                                   name="confirmar" 
                                   class="form-control" 
                                   placeholder="Repite tu contraseña"
                                   required>
                        </div>
                    </div>
                </div>
                
                <!-- TELÉFONO -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-phone"></i>
                        Teléfono
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone"></i>
                        <input type="tel" 
                               id="telefono"
                               name="telefono" 
                               class="form-control" 
                               placeholder="0000-0000"
                               value="<?php echo $_POST['telefono'] ?? ''; ?>">
                    </div>
                </div>
                
                <!-- BOTONES -->
                <div class="button-row">
                    <a href="login.php" class="btn-cancelar">
                        <i class="fas fa-times"></i>
                        <span>CANCELAR</span>
                    </a>
                    
                    <button type="submit" class="btn-registro">
                        <i class="fas fa-user-plus"></i>
                        <span>REGISTRARSE</span>
                    </button>
                </div>
                
            </form>
            
            <!-- SEPARADOR -->
            <div class="divider">
                <span class="divider-line"></span>
                <span class="divider-text">¿Ya tienes cuenta?</span>
                <span class="divider-line"></span>
            </div>
            
            <!-- ENLACE A LOGIN -->
            <div class="login-link">
                <a href="login.php">
                    <i class="fas fa-sign-in-alt"></i>
                    Inicia sesión aquí
                </a>
            </div>
            
            <!-- TÉRMINOS -->
            <div class="terms">
                Al registrarte aceptas nuestros 
                <a href="#">Términos y Condiciones</a> y 
                <a href="#">Política de Privacidad</a>
            </div>
            
        </div>
    </div>
    
    <!-- JS Personalizado -->
    <script src="../assets/js/registro.js"></script>
</body>
</html>