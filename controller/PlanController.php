<?php
require_once __DIR__ . '../../config/database.php';
require_once __DIR__ . '../../model/PlanModel.php';

class PlanController {
    private $db;
    private $plan;

    public function __construct() {
        $database = new database();
        $this->db = $database->getConnection();
        $this->plan = new PlanModel($this->db);
    }

    public function agregar($datos) {
        $this->plan->nombre = $datos['nombre'];
        $this->plan->precio = $datos['precio'];
        $this->plan->duracion_dias = $datos['duracion_dias'];
        $this->plan->descripcion = $datos['descripcion'] ?? '';
        $this->plan->estado = $datos['estado'] ?? 'activo';

        if ($this->plan->crear()) {
            return ['success' => true, 'message' => 'Plan registrado exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al registrar plan'];
    }

    public function listar() {
        return $this->plan->obtenerTodos();
    }

    public function obtener($id) {
        $resultado = $this->plan->obtenerPorId($id);
        if ($resultado) {
            return ['success' => true, 'data' => $resultado];
        }
        return ['success' => false, 'message' => 'Plan no encontrado'];
    }

    public function actualizar($datos) {
        $this->plan->id_tipo_membresia = $datos['id_tipo_membresia'];
        $this->plan->nombre = $datos['nombre'];
        $this->plan->precio = $datos['precio'];
        $this->plan->duracion_dias = $datos['duracion_dias'];
        $this->plan->descripcion = $datos['descripcion'] ?? '';
        $this->plan->estado = $datos['estado'];

        if ($this->plan->actualizar()) {
            return ['success' => true, 'message' => 'Plan actualizado correctamente'];
        }
        return ['success' => false, 'message' => 'Error al actualizar'];
    }

    public function eliminar($id) {
        if ($this->plan->eliminar($id)) {
            return ['success' => true, 'message' => 'Plan eliminado correctamente'];
        }
        return ['success' => false, 'message' => 'Error al eliminar'];
    }
}
?>