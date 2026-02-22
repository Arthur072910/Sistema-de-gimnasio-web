<?php
require_once __DIR__ . "/../config/database.php";

class AsistenciaModel {

    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function obtenerUsuarioPorCodigo($codigo) {

        $sql = "SELECT * FROM usuarios WHERE email = :codigo LIMIT 1";
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