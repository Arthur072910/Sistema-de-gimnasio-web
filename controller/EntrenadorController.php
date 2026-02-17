<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Entrenador.php';

class EntrenadorController {
    private $db;
    private $entrenador;

    public function __construct() {
        $database = new database();
        $this->db = $database->getConnection();
        $this->entrenador = new Entrenador($this->db);
    }

    /**
     * AGREGAR ENTRENADOR
     */
    public function agregar($datos) {
        $this->entrenador->nombre = $datos['nombre'];
        $this->entrenador->especialidad = $datos['especialidad'];
        $this->entrenador->telefono = $datos['telefono'] ?? '';
        $this->entrenador->email = $datos['email'];
        $this->entrenador->estado = "activo";
        $this->entrenador->fecha_registro = $datos['fecha_registro'];

        if($this->entrenador->crear()) {
            return ['success' => true, 'message' => 'Entrenador registrado exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al registrar entrenador'];
    }

    /**
     * OBTENER TODOS
     */
    public function listar() {
    return $this->entrenador->obtenerTodos();
}


    /**
     * OBTENER POR ID
     */
    public function obtener($id) {
        $resultado = $this->entrenador->obtenerPorId($id);
        if($resultado) {
            return ['success' => true, 'data' => $resultado];
        }
        return ['success' => false, 'message' => 'Entrenador no encontrado'];
    }

    /**
     * ACTUALIZAR
     */
    public function actualizar($datos) {
        $this->entrenador->id_entrenador = $datos['id_entrenador'];
        $this->entrenador->nombre = $datos['nombre'];
        $this->entrenador->especialidad = $datos['especialidad'];
        $this->entrenador->telefono = $datos['telefono'];
        $this->entrenador->email= $datos['email'];
        $this->entrenador->fecha_registro = $datos['fecha_registro'];

        if($this->entrenador->actualizar()) {
            return ['success' => true, 'message' => 'Entrenador actualizado correctamente'];
        }

        return ['success' => false, 'message' => 'Error al actualizar'];
    }

    /**
     * ELIMINAR
     */
    public function eliminar($id) {
        if($this->entrenador->eliminar($id)) {
            return ['success' => true, 'message' => 'Entrenador eliminado correctamente'];
        }

        return ['success' => false, 'message' => 'Error al eliminar'];
    }
}
?>
