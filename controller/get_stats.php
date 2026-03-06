<?php
require_once "../config/Database.php";
require_once "../model/Dashboard.php";

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();
$dashboard = new Dashboard($db);

echo json_encode($dashboard->obtenerEstadisticas());