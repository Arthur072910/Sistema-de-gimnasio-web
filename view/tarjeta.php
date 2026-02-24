<?php
session_start();
require_once "../config/database.php";

$database = new Database();
$conn = $database->getConnection();

if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

$id_cliente = $_SESSION['cliente_id'];

$sql = "SELECT m.codigo_qr, m.fecha_inicio, m.fecha_vencimiento, 
               t.nombre AS nombre_plan, 
               c.nombre AS nombre_cliente
        FROM membresias m
        INNER JOIN tipo_membresia t 
            ON m.id_tipo_membresia = t.id_tipo_membresia
        INNER JOIN clientes c 
            ON m.id_cliente = c.id_cliente
        WHERE m.id_cliente = ?
        AND m.estado = 'activa'
        ORDER BY m.id_membresia DESC
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->execute([$id_cliente]);
$datos = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$datos) {
    echo "No tienes una membresía activa.";
    exit();
}

$codigo_qr = $datos['codigo_qr'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Tarjeta - Delux Gym</title>
    <link rel="stylesheet" href="../assets/css/tarjeta.css">
</head>
<body>

<div class="tarjeta-container">

    <div class="tarjeta">

        <div class="lado-izq">
            <h2>DELUX GYM</h2>

            <p class="nombre"><?= htmlspecialchars($datos['nombre_cliente']) ?></p>

            <p><strong>Plan:</strong> <?= htmlspecialchars($datos['nombre_plan']) ?></p>
            <p><strong>Inicio:</strong> <?= $datos['fecha_inicio'] ?></p>
            <p><strong>Vence:</strong> <?= $datos['fecha_vencimiento'] ?></p>
        </div>

        <div class="lado-der">
    <img src="../assets/img/logo_deluxGym.png" class="logo" alt="Logo Gym">

    <div class="barcode-box">
        <svg id="barcode"></svg>
        <p class="codigo-texto"><?= htmlspecialchars($codigo_qr) ?></p>
    </div>
</div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<script>
   JsBarcode("#barcode", "<?= $codigo_qr ?>", {
    format: "CODE128",
    width: 2,
    height: 55,
    margin: 0,
    displayValue: false
});
</script>

</body>
</html>