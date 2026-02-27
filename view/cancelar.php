<?php
session_start();
require_once "../config/Database.php"; 

if (!isset($_SESSION['cliente_id'])) {
    header("Location: ../index.php");
    exit();
}


$database = new Database();
$pdo = $database->getConnection();

$id_membresia = $_POST['id_membresia'] ?? null;

if($id_membresia) {
    $sql = "UPDATE membresias 
            SET estado = 'cancelada' 
            WHERE id_membresia = :id_membresia 
            AND estado = 'activa'";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id_membresia", $id_membresia, PDO::PARAM_INT);
    $stmt->execute();
}

header("Location: Perfil.php");
exit();
