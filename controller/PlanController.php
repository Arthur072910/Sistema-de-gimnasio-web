<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/PlanModel.php';

class PlanController {
    private $db;
    private $plan;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->plan = new PlanModel($this->db);
    }

    public function agregar($datos) {
        try {
            
            if(empty($datos['nombre'])) {
                return ['success' => false, 'message' => 'El nombre del plan es requerido'];
            }
            
            if(empty($datos['precio']) || !is_numeric($datos['precio']) || $datos['precio'] <= 0) {
                return ['success' => false, 'message' => 'El precio debe ser un número válido mayor a 0'];
            }
            
            if(empty($datos['duracion_dias']) || !is_numeric($datos['duracion_dias']) || $datos['duracion_dias'] <= 0) {
                return ['success' => false, 'message' => 'La duración debe ser un número válido de días'];
            }

            
            $this->plan->nombre = trim($datos['nombre']);
            $this->plan->precio = floatval($datos['precio']);
            $this->plan->duracion_dias = intval($datos['duracion_dias']);
            $this->plan->descripcion = !empty($datos['descripcion']) ? trim($datos['descripcion']) : '';
            $this->plan->estado = $datos['estado'] ?? 'activo';

            if ($this->plan->crear()) {
                return ['success' => true, 'message' => 'Plan registrado exitosamente'];
            }
            
            return ['success' => false, 'message' => 'Error al registrar el plan'];
            
        } catch(Exception $e) {
            error_log("Error en PlanController->agregar: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()];
        }
    }

    public function listar() {
        try {
            return $this->plan->obtenerTodos();
        } catch(Exception $e) {
            error_log("Error en PlanController->listar: " . $e->getMessage());
            return [];
        }
    }

    public function obtener($id) {
        try {
            if(empty($id) || !is_numeric($id)) {
                return ['success' => false, 'message' => 'ID inválido'];
            }
            
            $resultado = $this->plan->obtenerPorId($id);
            if ($resultado) {
                return ['success' => true, 'data' => $resultado];
            }
            return ['success' => false, 'message' => 'Plan no encontrado'];
            
        } catch(Exception $e) {
            error_log("Error en PlanController->obtener: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al obtener el plan'];
        }
    }

    public function actualizar($datos) {
        try {
            if(empty($datos['id_tipo_membresia']) || !is_numeric($datos['id_tipo_membresia'])) {
                return ['success' => false, 'message' => 'ID inválido'];
            }
            
           
            if(empty($datos['nombre'])) {
                return ['success' => false, 'message' => 'El nombre del plan es requerido'];
            }
            
            if(empty($datos['precio']) || !is_numeric($datos['precio']) || $datos['precio'] <= 0) {
                return ['success' => false, 'message' => 'El precio debe ser un número válido mayor a 0'];
            }
            
            if(empty($datos['duracion_dias']) || !is_numeric($datos['duracion_dias']) || $datos['duracion_dias'] <= 0) {
                return ['success' => false, 'message' => 'La duración debe ser un número válido de días'];
            }

            
            $this->plan->id_tipo_membresia = intval($datos['id_tipo_membresia']);
            $this->plan->nombre = trim($datos['nombre']);
            $this->plan->precio = floatval($datos['precio']);
            $this->plan->duracion_dias = intval($datos['duracion_dias']);
            $this->plan->descripcion = !empty($datos['descripcion']) ? trim($datos['descripcion']) : '';
            $this->plan->estado = $datos['estado'] ?? 'activo';

            if ($this->plan->actualizar()) {
                return ['success' => true, 'message' => 'Plan actualizado correctamente'];
            }
            
            return ['success' => false, 'message' => 'Error al actualizar el plan'];
            
        } catch(Exception $e) {
            error_log("Error en PlanController->actualizar: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()];
        }
    }

    public function eliminar($id) {
        try {
            if(empty($id) || !is_numeric($id)) {
                return ['success' => false, 'message' => 'ID inválido'];
            }
            
            if ($this->plan->eliminar($id)) {
                return ['success' => true, 'message' => 'Plan eliminado correctamente'];
            }
            
            return ['success' => false, 'message' => 'Error al eliminar el plan'];
            
        } catch(Exception $e) {
            error_log("Error en PlanController->eliminar: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()];
        }
    }
}
?>