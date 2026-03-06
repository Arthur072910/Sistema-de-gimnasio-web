
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

    public function __construct($db){
        $this->conn = $db;
    }

  
    public function crear(){

        $query = "INSERT INTO " . $this->table_name . "
                SET
                nombre = :nombre,
                descripcion = :descripcion,
                cupo_maximo = :cupo_maximo,
                id_entrenador = :id_entrenador,
                estado = :estado,
                fecha_creacion = NOW()";

        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->cupo_maximo = htmlspecialchars(strip_tags($this->cupo_maximo));
        $this->id_entrenador = htmlspecialchars(strip_tags($this->id_entrenador));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":cupo_maximo", $this->cupo_maximo);
        $stmt->bindParam(":id_entrenador", $this->id_entrenador);
        $stmt->bindParam(":estado", $this->estado);

        return $stmt->execute();
    }

   public function obtenerTodos() {
    $query = "SELECT c.*, e.nombre as nombre_entrenador 
              FROM " . $this->table_name . " c
              LEFT JOIN entrenadores e ON c.id_entrenador = e.id_entrenador
              ORDER BY c.id_clase DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    
    public function listarTodos(){
        return $this->obtenerTodos();
    }

    
    public function obtenerPorId($id){

        $query = "SELECT *
                FROM " . $this->table_name . "
                WHERE id_clase = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar(){

        $query = "UPDATE " . $this->table_name . "
                SET
                nombre = :nombre,
                descripcion = :descripcion,
                cupo_maximo = :cupo_maximo,
                id_entrenador = :id_entrenador,
                estado = :estado
                WHERE id_clase = :id_clase";

        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->cupo_maximo = htmlspecialchars(strip_tags($this->cupo_maximo));
        $this->id_entrenador = htmlspecialchars(strip_tags($this->id_entrenador));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id_clase = htmlspecialchars(strip_tags($this->id_clase));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":cupo_maximo", $this->cupo_maximo);
        $stmt->bindParam(":id_entrenador", $this->id_entrenador);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id_clase", $this->id_clase);

        return $stmt->execute();
    }

  
    public function eliminar($id){

        $query = "DELETE FROM " . $this->table_name . "
                WHERE id_clase = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        return $stmt->execute();
    }

   
    public function obtenerEntrenadores(){

        $query = "SELECT id_entrenador, nombre
                FROM entrenadores
                ORDER BY nombre";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>

