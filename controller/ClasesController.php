<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Clase.php';

class ClasesController {
    private $db;
    private $clase;

    public function __construct() {
        $database = new database();
        $this->db = $database->getConnection();
        $this->clase = new Clase($this->db);
    }

    public function agregar($datos) {
        $this->clase->nombre = $datos['nombre'];
        $this->clase->descripcion = $datos['descripcion'] ?? '';
        $this->clase->cupo_maximo = $datos['cupo_maximo'];
        $this->clase->id_entrenador = $datos['id_entrenador'];
        $this->clase->estado = $datos['estado'] ?? 'activo';
        $this->clase->fecha_creacion = date('Y-m-d H:i:s'); // fecha actual

        if ($this->clase->crear()) {
            return ['success' => true, 'message' => 'Clase registrada'];
        }
        return ['success' => false, 'message' => 'Error al registrar'];
    }

    public function listar() {
        return $this->clase->obtenerTodos();
    }

    public function obtener($id) {
        $resultado = $this->clase->obtenerPorId($id);
        if ($resultado) {
            return ['success' => true, 'data' => $resultado];
        }
        return ['success' => false, 'message' => 'Clase no encontrada'];
    }

    public function actualizar($datos) {
        $this->clase->id_clase = $datos['id_clase'];
        $this->clase->nombre = $datos['nombre'];
        $this->clase->descripcion = $datos['descripcion'] ?? '';
        $this->clase->cupo_maximo = $datos['cupo_maximo'];
        $this->clase->id_entrenador = $datos['id_entrenador'];
        $this->clase->estado = $datos['estado'];

        if ($this->clase->actualizar()) {
            return ['success' => true, 'message' => 'Clase actualizada'];
        }
        return ['success' => false, 'message' => 'Error al actualizar'];
    }

    public function eliminar($id) {
        if ($this->clase->eliminar($id)) {
            return ['success' => true, 'message' => 'Clase eliminada'];
        }
        return ['success' => false, 'message' => 'Error al eliminar'];
    }

    public function listarEntrenadores() {
        return $this->clase->obtenerEntrenadores();
    }
}
?>