<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['cliente_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: Perfil.php");
    exit();
}

$cliente_id = $_SESSION['cliente_id'];
$id_membresia = $_POST['id_membresia'] ?? null;

$database = new Database();
$conn = $database->getConnection();

try {
    $conn->beginTransaction();


    $stmtInfo = $conn->prepare("
        SELECT tm.nombre 
        FROM membresias m
        JOIN tipo_membresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
        WHERE m.id_cliente = ? AND m.estado = 'activa'
        LIMIT 1
    ");
    $stmtInfo->execute([$cliente_id]);
    $membresia = $stmtInfo->fetch(PDO::FETCH_ASSOC);

    if ($membresia) {
        $nombrePlan = $membresia['nombre'];

       
        $stmtDelClases = $conn->prepare("DELETE FROM inscripciones_clases WHERE id_cliente = ?");
        $stmtDelClases->execute([$cliente_id]);

       
        $stmtCancel = $conn->prepare("UPDATE membresias SET estado = 'cancelada' WHERE id_cliente = ? AND estado = 'activa'");
        $stmtCancel->execute([$cliente_id]);

      
        $stmtLog = $conn->prepare("INSERT INTO historial (id_cliente, tipo_accion, descripcion, fecha_accion) VALUES (?, 'cancelacion_total', ?, NOW())");
        $stmtLog->execute([$cliente_id, "Cancelación inmediata de plan: $nombrePlan"]);

       
        unset($_SESSION['id_membresia']);
        unset($_SESSION['nombre_plan']);
       

        $conn->commit();
        
        
        header("Location: Perfil.php?success=cancelado_ok&t=" . time()); 
        exit();
    } else {
        $conn->rollBack();
        header("Location: Perfil.php?error=no_active_plan");
        exit();
    }

} catch (Exception $e) {
    $conn->rollBack();
    header("Location: Perfil.php?error=error_db");
    exit();
}