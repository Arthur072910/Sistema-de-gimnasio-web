<?php
class PlanModel {
    private $conn;
    private $table_name = "tipo_membresia";

    public $id_tipo_membresia;
    public $nombre;
    public $precio;
    public $duracion_dias;  // en la tabla es duracion_dias (int)
    public $descripcion;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear nuevo plan
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET nombre=:nombre, precio=:precio, duracion_dias=:duracion_dias,
                      descripcion=:descripcion, estado=:estado";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->duracion_dias = htmlspecialchars(strip_tags($this->duracion_dias));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        // Vincular
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":duracion_dias", $this->duracion_dias);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":estado", $this->estado);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obtener todos los planes
    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_tipo_membresia DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un plan por ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_tipo_membresia = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar plan
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . "
                  SET nombre=:nombre, precio=:precio, duracion_dias=:duracion_dias,
                      descripcion=:descripcion, estado=:estado
                  WHERE id_tipo_membresia = :id_tipo_membresia";
        $stmt = $this->conn->prepare($query);

        // Limpiar
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->duracion_dias = htmlspecialchars(strip_tags($this->duracion_dias));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id_tipo_membresia = htmlspecialchars(strip_tags($this->id_tipo_membresia));

        // Vincular
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":duracion_dias", $this->duracion_dias);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id_tipo_membresia", $this->id_tipo_membresia);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar plan
    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_tipo_membresia = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>