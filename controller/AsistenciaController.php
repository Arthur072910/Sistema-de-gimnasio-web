<?php

require_once __DIR__ . "/../config/database.php";

$database = new Database();
$conn = $database->getConnection();

if (isset($_GET['action']) && $_GET['action'] === 'validar_codigo') {

    $codigo = trim($_GET['codigo']);

    if (!$codigo) {
        echo json_encode(["status" => "no_encontrado"]);
        exit;
    }

    try {

        
        $sql = "SELECT id_usuario, email, estado 
                FROM usuarios 
                WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$codigo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            echo json_encode(["status" => "no_encontrado"]);
            exit;
        }

        if ($usuario['estado'] !== 'activo') {
            echo json_encode([
                "status" => "vencido",
                "nombre" => $usuario['email']
            ]);
            exit;
        }

        $id_usuario = $usuario['id_usuario'];

        $sql = "SELECT id_cliente 
                FROM clientes 
                WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_usuario]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cliente) {
            echo json_encode(["status" => "no_encontrado"]);
            exit;
        }

        $id_cliente = $cliente['id_cliente'];

       
        $sql = "SELECT id_asistencia 
                FROM asistencias 
                WHERE id_cliente = ?
                AND DATE(fecha_entrada) = CURDATE()
                AND fecha_salida IS NULL";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_cliente]);
        $asistencia = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$asistencia) {

           
            $sql = "INSERT INTO asistencias 
                    (id_cliente, fecha_entrada, validado_con_qr)
                    VALUES (?, NOW(), 1)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id_cliente]);

            echo json_encode([
                "status" => "activo",
                "nombre" => $usuario['email'],
                "tipo"   => "entrada"
            ]);

        } else {

          
            $sql = "UPDATE asistencias
                    SET fecha_salida = NOW()
                    WHERE id_asistencia = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$asistencia['id_asistencia']]);

            echo json_encode([
                "status" => "activo",
                "nombre" => $usuario['email'],
                "tipo"   => "salida"
            ]);
        }

    } catch (PDOException $e) {

        echo json_encode([
            "status" => "error",
            "mensaje" => $e->getMessage()
        ]);
    }
}