<?php
require_once __DIR__ . "/../config/database.php";

class AsistenciaModel {

    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerMembresiaPorCodigo($codigo) {
    $sql = "SELECT m.id_membresia, m.id_cliente, m.estado, c.nombre, c.apellido
            FROM membresias m
            INNER JOIN clientes c ON m.id_cliente = c.id_cliente
            WHERE m.codigo_qr = :codigo
            ORDER BY m.fecha_inicio DESC
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":codigo", $codigo);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}



    public function registrarAsistencia($id_usuario) {

        $sql = "INSERT INTO asistencias (id_cliente, validado_con_qr) 
                VALUES (:id_cliente, 1)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_cliente", $id_usuario);

        return $stmt->execute();
    }
}