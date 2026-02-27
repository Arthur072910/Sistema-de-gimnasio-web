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
        
        $sql = "SELECT m.id_membresia, m.id_cliente, m.estado, c.nombre, c.apellido
                FROM membresias m
                INNER JOIN clientes c ON m.id_cliente = c.id_cliente
                WHERE m.codigo_qr = ?
                ORDER BY m.fecha_inicio DESC
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$codigo]);
        $membresia = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$membresia) {
            echo json_encode(["status" => "no_encontrado"]);
            exit;
        }

        if ($membresia['estado'] !== 'activa') {
            echo json_encode([
                "status" => "vencido",
                "nombre" => $membresia['nombre'] . " " . $membresia['apellido']
            ]);
            exit;
        }

        $id_cliente = $membresia['id_cliente'];

       
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
                "nombre" => $membresia['nombre'] . " " . $membresia['apellido'],
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
                "nombre" => $membresia['nombre'] . " " . $membresia['apellido'],
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
