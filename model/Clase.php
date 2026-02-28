<?php
class Clase {
    private $conn;
    private $table_name = "clases";

    public $id_clase;
    public $nombre;
    public $descripcion;
    public $cupo_maximo;
    public $id_entrenador;
    public $estado;
    public $fecha_creacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                  (nombre, descripcion, cupo_maximo, id_entrenador, estado, fecha_creacion)
                  VALUES (:nombre, :descripcion, :cupo_maximo, :id_entrenador, :estado, NOW())";
        
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->cupo_maximo = htmlspecialchars(strip_tags($this->cupo_maximo));
        $this->id_entrenador = htmlspecialchars(strip_tags($this->id_entrenador));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        // Bindear parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":cupo_maximo", $this->cupo_maximo);
        $stmt->bindParam(":id_entrenador", $this->id_entrenador);
        $stmt->bindParam(":estado", $this->estado);

        if($stmt->execute()) {
            return true;
        }
        
        // Mostrar error si hay
        print_r($stmt->errorInfo());
        return false;
    }

    public function obtenerTodos() {
        // Consulta más simple primero para ver si hay datos
        $query = "SELECT c.*, 
                         e.nombre as nombre_entrenador,
                         (SELECT COUNT(*) FROM inscripciones_clases ic WHERE ic.id_clase = c.id_clase AND ic.estado = 'activa') as inscritos
                  FROM " . $this->table_name . " c
                  LEFT JOIN entrenadores e ON c.id_entrenador = e.id_entrenador
                  ORDER BY c.id_clase DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_clase = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar() {
        $query = "UPDATE " . $this->table_name . "
                  SET nombre = :nombre, 
                      descripcion = :descripcion, 
                      cupo_maximo = :cupo_maximo,
                      id_entrenador = :id_entrenador, 
                      estado = :estado
                  WHERE id_clase = :id_clase";
        
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->cupo_maximo = htmlspecialchars(strip_tags($this->cupo_maximo));
        $this->id_entrenador = htmlspecialchars(strip_tags($this->id_entrenador));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id_clase = htmlspecialchars(strip_tags($this->id_clase));

        // Bindear parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":cupo_maximo", $this->cupo_maximo);
        $stmt->bindParam(":id_entrenador", $this->id_entrenador);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id_clase", $this->id_clase);

        return $stmt->execute();
    }

    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_clase = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function obtenerEntrenadores() {
        $query = "SELECT id_entrenador, nombre, especialidad 
                  FROM entrenadores 
                  WHERE estado = 'activo' 
                  ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>