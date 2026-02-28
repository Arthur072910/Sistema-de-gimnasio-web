<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Clase.php';

class ClasesController {
    private $db;
    private $clase;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->clase = new Clase($this->db);
    }

    public function agregar($datos) {
        // Validar datos requeridos
        if(empty($datos['nombre']) || empty($datos['cupo_maximo']) || empty($datos['id_entrenador'])) {
            return ['success' => false, 'message' => 'Todos los campos son requeridos'];
        }

        $this->clase->nombre = $datos['nombre'];
        $this->clase->descripcion = $datos['descripcion'] ?? '';
        $this->clase->cupo_maximo = $datos['cupo_maximo'];
        $this->clase->id_entrenador = $datos['id_entrenador'];
        $this->clase->estado = $datos['estado'] ?? 'activo';

        if ($this->clase->crear()) {
            return ['success' => true, 'message' => 'Clase registrada exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al registrar la clase'];
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
            return ['success' => true, 'message' => 'Clase actualizada exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al actualizar la clase'];
    }

    public function eliminar($id) {
        if ($this->clase->eliminar($id)) {
            return ['success' => true, 'message' => 'Clase eliminada exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al eliminar la clase'];
    }

    public function listarEntrenadores() {
        return $this->clase->obtenerEntrenadores();
    }
}
?>