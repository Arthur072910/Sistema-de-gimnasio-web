<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/MiembroModel.php';

class MiembroController {
    private $db;
    private $miembro;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->miembro = new MiembroModel($this->db); // ¡Ahora sí funciona!
    }

    public function listar() {
        try {
            return $this->miembro->obtenerTodos();
        } catch(Exception $e) {
            error_log("Error en MiembroController->listar: " . $e->getMessage());
            return [];
        }
    }

    public function obtener($id) {
        try {
            if(empty($id) || !is_numeric($id)) {
                return ['success' => false, 'message' => 'ID inválido'];
            }
            
            $resultado = $this->miembro->obtenerPorId($id);
            if ($resultado) {
                return ['success' => true, 'data' => $resultado];
            }
            return ['success' => false, 'message' => 'Miembro no encontrado'];
            
        } catch(Exception $e) {
            error_log("Error en MiembroController->obtener: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al obtener miembro'];
        }
    }

    public function actualizar($datos) {
        try {
            if(empty($datos['id_usuario']) || !is_numeric($datos['id_usuario'])) {
                return ['success' => false, 'message' => 'ID inválido'];
            }

            // Actualizar datos de usuario
            $usuario_actualizado = $this->miembro->actualizarUsuario(
                $datos['id_usuario'],
                trim($datos['email']),
                $datos['rol'],
                $datos['estado']
            );

            // Si tiene id_cliente, actualizar datos de cliente
            $cliente_actualizado = true;
            if (!empty($datos['id_cliente'])) {
                $cliente_actualizado = $this->miembro->actualizarCliente(
                    $datos['id_cliente'],
                    trim($datos['nombre']),
                    trim($datos['apellido']),
                    $datos['telefono'] ?? null,
                    $datos['fecha_nacimiento'] ?? null,
                    $datos['genero'] ?? null
                );
            }

            if ($usuario_actualizado && $cliente_actualizado) {
                return ['success' => true, 'message' => 'Miembro actualizado correctamente'];
            }
            
            return ['success' => false, 'message' => 'Error al actualizar miembro'];
            
        } catch(Exception $e) {
            error_log("Error en MiembroController->actualizar: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()];
        }
    }

    public function cambiarContraseña($datos) {
        try {
            if(empty($datos['id_usuario']) || !is_numeric($datos['id_usuario'])) {
                return ['success' => false, 'message' => 'ID inválido'];
            }
            
            if(empty($datos['nueva_contraseña']) || strlen($datos['nueva_contraseña']) < 6) {
                return ['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres'];
            }

            if ($datos['nueva_contraseña'] !== $datos['confirmar_contraseña']) {
                return ['success' => false, 'message' => 'Las contraseñas no coinciden'];
            }

            if ($this->miembro->cambiarContraseña($datos['id_usuario'], $datos['nueva_contraseña'])) {
                return ['success' => true, 'message' => 'Contraseña actualizada correctamente'];
            }
            
            return ['success' => false, 'message' => 'Error al cambiar contraseña'];
            
        } catch(Exception $e) {
            error_log("Error en MiembroController->cambiarContraseña: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al cambiar contraseña: ' . $e->getMessage()];
        }
    }

    public function eliminar($id) {
        try {
            if(empty($id) || !is_numeric($id)) {
                return ['success' => false, 'message' => 'ID inválido'];
            }
            
            if ($this->miembro->eliminar($id)) {
                return ['success' => true, 'message' => 'Miembro eliminado correctamente'];
            }
            
            return ['success' => false, 'message' => 'Error al eliminar miembro'];
            
        } catch(Exception $e) {
            error_log("Error en MiembroController->eliminar: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()];
        }
    }

    public function estadisticas() {
        try {
            return $this->miembro->obtenerEstadisticas();
        } catch(Exception $e) {
            error_log("Error en MiembroController->estadisticas: " . $e->getMessage());
            return [
                'total' => 0,
                'por_rol' => [],
                'nuevos_mes' => 0
            ];
        }
    }
}
?>