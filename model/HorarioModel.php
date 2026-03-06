<?php
require_once __DIR__ . "/../config/database.php";

class HorarioModel {

    private $conn;

    public function __construct() {

        $database = new Database();
        $this->conn = $database->getConnection();

    }

 
    public function listarTodos() {

        $sql = "SELECT h.*, 
                       c.nombre AS nombre_clase
                FROM horarios_clases h
                INNER JOIN clases c 
                ON h.id_clase = c.id_clase
                ORDER BY FIELD(h.dia_semana,
                'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'),
                h.hora_inicio";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function agregar($data) {

        $sql = "INSERT INTO horarios_clases
                (id_clase, dia_semana, hora_inicio, hora_fin)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['id_clase'],
            $data['dia_semana'],
            $data['hora_inicio'],
            $data['hora_fin']
        ]);
    }

   
    public function actualizar($data) {

        $sql = "UPDATE horarios_clases
                SET id_clase=?,
                    dia_semana=?,
                    hora_inicio=?,
                    hora_fin=?
                WHERE id_horario=?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['id_clase'],
            $data['dia_semana'],
            $data['hora_inicio'],
            $data['hora_fin'],
            $data['id_horario']
        ]);
    }

    // ELIMINAR
    public function eliminar($id) {

        $sql = "DELETE FROM horarios_clases WHERE id_horario=?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$id]);
    }

    // OBTENER CLASES PARA SELECT
    public function obtenerClasesSimple() {

        $sql = "SELECT id_clase, nombre
                FROM clases
                WHERE estado='activa'";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
     public function getConnection(){
        return $this->conn;
    }

}