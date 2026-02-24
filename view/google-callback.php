<?php
session_start();
require_once __DIR__ . '/../config/google_config.php';
require_once __DIR__ . '/../config/Database.php';

if (!isset($_GET['code'])) {
    header("Location: login.php?error=google_auth_failed");
    exit();
}

try {
    $client = getGoogleClient();
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (isset($token['error'])) {
        throw new Exception($token['error_description'] ?? 'Error en token');
    }
    
    $client->setAccessToken($token['access_token']);
    
    // Obtener datos del usuario
    $oauth2 = new Google_Service_OAuth2($client);
    $userInfo = $oauth2->userinfo->get();
    
    $email = $userInfo->email;
    $nombre = $userInfo->givenName;
    $apellido = $userInfo->familyName ?? '';
    $google_id = $userInfo->id;
    
    if (empty($email)) {
        throw new Exception("No se pudo obtener el email");
    }
    
    // Conectar a BD
    $database = new Database();
    $db = $database->getConnection();
    
    // Buscar usuario por email
    $query = "SELECT u.*, c.id_cliente, c.nombre, c.apellido 
              FROM usuarios u
              LEFT JOIN clientes c ON u.id_usuario = c.id_usuario
              WHERE u.email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // Usuario existe
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (empty($usuario['google_id'])) {
            $update = "UPDATE usuarios SET google_id = :google_id WHERE id_usuario = :id";
            $stmtUpdate = $db->prepare($update);
            $stmtUpdate->bindParam(':google_id', $google_id);
            $stmtUpdate->bindParam(':id', $usuario['id_usuario']);
            $stmtUpdate->execute();
        }
        
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['cliente_id'] = $usuario['id_cliente'];
        $_SESSION['cliente_nombre'] = $usuario['nombre'] ? $usuario['nombre'] . ' ' . $usuario['apellido'] : $nombre . ' ' . $apellido;
        $_SESSION['cliente_email'] = $usuario['email'];
        $_SESSION['rol'] = $usuario['rol'];
        
    } else {
        // Usuario nuevo
        $db->beginTransaction();
        
        $query1 = "INSERT INTO usuarios (email, google_id, rol, estado) 
                  VALUES (:email, :google_id, 'cliente', 'activo')";
        $stmt1 = $db->prepare($query1);
        $stmt1->bindParam(':email', $email);
        $stmt1->bindParam(':google_id', $google_id);
        $stmt1->execute();
        
        $id_usuario = $db->lastInsertId();
        
        $query2 = "INSERT INTO clientes (id_usuario, nombre, apellido) 
                  VALUES (:id_usuario, :nombre, :apellido)";
        $stmt2 = $db->prepare($query2);
        $stmt2->bindParam(':id_usuario', $id_usuario);
        $stmt2->bindParam(':nombre', $nombre);
        $stmt2->bindParam(':apellido', $apellido);
        $stmt2->execute();
        
        $id_cliente = $db->lastInsertId();
        $db->commit();
        
        $_SESSION['usuario_id'] = $id_usuario;
        $_SESSION['cliente_id'] = $id_cliente;
        $_SESSION['cliente_nombre'] = $nombre . ' ' . $apellido;
        $_SESSION['cliente_email'] = $email;
        $_SESSION['rol'] = 'cliente';
    }
    
    header("Location: ../index.php");
    exit();
    
} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    error_log("Error Google Auth: " . $e->getMessage());
    header("Location: login.php?error=google_error");
    exit();
}
?>