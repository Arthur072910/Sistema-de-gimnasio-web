<?php
session_start();

if(isset($_SESSION['cliente_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/../controller/ClienteController.php';

$mensaje = '';
$tipo = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar que las contraseñas coincidan
    if($_POST['password'] !== $_POST['confirmar']) {
        $mensaje = 'Las contraseñas no coinciden';
        $tipo = 'danger';
    } else {
        $controller = new ClienteController();
        
        $datos = [
            'nombre' => $_POST['nombre'],
            'apellido' => $_POST['apellido'],
            'email' => $_POST['email'],
            'contraseña' => $_POST['password'],
            'telefono' => $_POST['telefono'] ?? '',
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
            'genero' => $_POST['genero'] ?? null
        ];

        $resultado = $controller->agregar($datos);
        $mensaje = $resultado['message'];
        $tipo = $resultado['success'] ? 'success' : 'danger';

        if($resultado['success']) {
            echo "<script>
                setTimeout(function() {
                    window.location.href='login.php';
                }, 2000);
            </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Delux Gym</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/registro.css">
</head>
<body>
    <div class="registro-container">
        <div class="registro-card">
            
            <div class="logo">
                <img src="../assets/img/ChatGPT Image 30 ene 2026, 10_35_11 p.m..png" alt="Delux Gym">
                <h1>Delux Gym</h1>
                <p>CREA TU CUENTA</p>
            </div>
            
            <?php if($mensaje): ?>
            <div class="alert alert-<?php echo $tipo; ?>">
                <i class="fas fa-<?php echo $tipo == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <span><?php echo htmlspecialchars($mensaje); ?></span>
            </div>
            <?php endif; ?>
            
            <form method="POST" id="registroForm">
                
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
                                   value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
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
                                   value="<?php echo htmlspecialchars($_POST['apellido'] ?? ''); ?>"
                                   required>
                        </div>
                    </div>
                </div>
                
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
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                               required>
                    </div>
                </div>
                
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
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-phone"></i>
                        Teléfono
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone"></i>
                        <input type="text" 
                               id="telefono"
                               name="telefono" 
                               class="form-control" 
                               placeholder="0000-0000"
                               value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>
                            <i class="fas fa-calendar"></i>
                            Fecha de Nacimiento
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-calendar"></i>
                            <input type="date" 
                                   id="fecha_nacimiento"
                                   name="fecha_nacimiento" 
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($_POST['fecha_nacimiento'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <i class="fas fa-venus-mars"></i>
                            Género
                        </label>
                        <div class="input-wrapper">
                            <i class="fas fa-venus-mars"></i>
                            <select name="genero" id="genero" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="M" <?php echo (isset($_POST['genero']) && $_POST['genero'] == 'M') ? 'selected' : ''; ?>>Masculino</option>
                                <option value="F" <?php echo (isset($_POST['genero']) && $_POST['genero'] == 'F') ? 'selected' : ''; ?>>Femenino</option>
                                <option value="Otro" <?php echo (isset($_POST['genero']) && $_POST['genero'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                            </select>
                        </div>
                    </div>
                </div>
                
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
            
            <div class="divider">
                <span class="divider-line"></span>
                <span class="divider-text">¿Ya tienes cuenta?</span>
                <span class="divider-line"></span>
            </div>
            
            <div class="login-link">
                <a href="login.php">
                    <i class="fas fa-sign-in-alt"></i>
                    Inicia sesión aquí
                </a>
            </div>
            
            <div class="terms">
                Al registrarte aceptas nuestros 
                <a href="#">Términos y Condiciones</a> y 
                <a href="#">Política de Privacidad</a>
            </div>
            
        </div>
    </div>
    
    <script src="../assets/js/registro.js"></script>
</body>
</html>