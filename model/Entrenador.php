<?php
class Entrenador {
    private $conn;
    private $table = "entrenadores";

    public $id_entrenador;
    public $nombre;
    public $especialidad;
    public $telefono;
    public $email;
    public $estado;
    public $fecha_registro;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * CREAR ENTRENADOR
     */
    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      (nombre, especialidad, telefono, email, fecha_registro)
                      VALUES (:nombre, :especialidad, :telefono, :email, :fecha_registro)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':especialidad', $this->especialidad);
            $stmt->bindParam(':telefono', $this->telefono);
            $stmt->bindParam(':email', $this->email);
            
            $stmt->bindParam(':fecha_registro', $this->fecha_registro);

            if(!$stmt->execute()){
    print_r($stmt->errorInfo());
    exit();
}
return true;


        } catch(Exception $e) {
            error_log("Error al crear entrenador: " . $e->getMessage());
            return false;
        }
    }

    /**
     * OBTENER TODOS
     */
    public function obtenerTodos() {
    $query = "SELECT * FROM " . $this->table . " ORDER BY id_entrenador DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    /**
     * OBTENER POR ID
     */
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE id_entrenador = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * ACTUALIZAR
     */
    public function actualizar() {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET nombre = :nombre,
                          especialidad = :especialidad,
                          telefono = :telefono,
                          email = :email,
                          fecha_registro = :fecha_registro
                      WHERE id_entrenador = :id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':especialidad', $this->especialidad);
            $stmt->bindParam(':telefono', $this->telefono);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':fecha_registro', $this->fecha_registro);
            $stmt->bindParam(':id', $this->id_entrenador);

            return $stmt->execute();

        } catch(Exception $e) {
            error_log("Error al actualizar entrenador: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ELIMINAR
     */
    public function eliminar($id) {
        try {
            $query = "DELETE FROM " . $this->table . " 
                      WHERE id_entrenador = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);

            return $stmt->execute();

        } catch(Exception $e) {
            error_log("Error al eliminar entrenador: " . $e->getMessage());
            return false;
        }
    }
}
?>
