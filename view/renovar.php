<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

$id_cliente = $_SESSION['cliente_id'];


$sql = "SELECT t.id_tipo_membresia, t.nombre, t.precio
        FROM membresias m
        INNER JOIN tipo_membresia t 
        ON m.id_tipo_membresia = t.id_tipo_membresia
        WHERE m.id_cliente = ?
        ORDER BY m.id_membresia DESC
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->execute([$id_cliente]);
$plan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$plan) {
    header("Location: plan.php");
    exit();
}


$_SESSION['id_membresia'] = $plan['id_tipo_membresia'];
$_SESSION['nombre_plan']  = $plan['nombre'];
$_SESSION['precio_plan']  = $plan['precio'];


try {
    $tipoAccion = "intento_renovacion";
    $descripcion = "El usuario inició el proceso de renovación del plan: " . $plan['nombre'];
    
    $stmtHistorial = $conn->prepare("
        INSERT INTO historial (id_cliente, tipo_accion, descripcion, fecha_accion) 
        VALUES (?, ?, ?, NOW())
    ");
    $stmtHistorial->execute([$id_cliente, $tipoAccion, $descripcion]);
} catch (Exception $e) {
    
}


header("Location: pago.php");
exit();