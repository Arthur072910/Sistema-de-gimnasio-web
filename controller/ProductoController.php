<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Producto.php';

class ProductoController {
    private $db;
    private $producto;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->producto = new Producto($this->db);
    }

    public function agregar($datos) {
        try {
            // Validaciones
            if(empty($datos['nombre'])) {
                return ['success' => false, 'message' => 'El nombre del producto es requerido'];
            }
            
            if(empty($datos['categoria'])) {
                return ['success' => false, 'message' => 'La categoría es requerida'];
            }
            
            if(!isset($datos['precio']) || !is_numeric($datos['precio']) || $datos['precio'] <= 0) {
                return ['success' => false, 'message' => 'El precio debe ser un número válido mayor a 0'];
            }
            
            if(!isset($datos['stock']) || !is_numeric($datos['stock']) || $datos['stock'] < 0) {
                return ['success' => false, 'message' => 'El stock debe ser un número válido'];
            }
            
            // Asignar valores al modelo
            $this->producto->nombre = trim($datos['nombre']);
            $this->producto->categoria = trim($datos['categoria']);
            $this->producto->precio = floatval($datos['precio']);
            $this->producto->stock = intval($datos['stock']);
            $this->producto->proveedor = !empty($datos['proveedor']) ? trim($datos['proveedor']) : null;

            if($this->producto->insertar()) {
                return ['success' => true, 'message' => 'Producto guardado exitosamente'];
            }
            
            return ['success' => false, 'message' => 'Error al guardar el producto'];
            
        } catch(Exception $e) {
            error_log("Error en ProductoController->agregar: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()];
        }
    }

    public function actualizar($id, $datos) {
        try {
            if(empty($id) || !is_numeric($id)) {
                return ['success' => false, 'message' => 'ID inválido'];
            }
            
            // Validaciones
            if(empty($datos['nombre'])) {
                return ['success' => false, 'message' => 'El nombre del producto es requerido'];
            }
            
            if(empty($datos['categoria'])) {
                return ['success' => false, 'message' => 'La categoría es requerida'];
            }
            
            if(!isset($datos['precio']) || !is_numeric($datos['precio']) || $datos['precio'] <= 0) {
                return ['success' => false, 'message' => 'El precio debe ser un número válido mayor a 0'];
            }
            
            if(!isset($datos['stock']) || !is_numeric($datos['stock']) || $datos['stock'] < 0) {
                return ['success' => false, 'message' => 'El stock debe ser un número válido'];
            }
            
            // Asignar valores al modelo
            $this->producto->id = $id;
            $this->producto->nombre = trim($datos['nombre']);
            $this->producto->categoria = trim($datos['categoria']);
            $this->producto->precio = floatval($datos['precio']);
            $this->producto->stock = intval($datos['stock']);
            $this->producto->proveedor = !empty($datos['proveedor']) ? trim($datos['proveedor']) : null;

            if($this->producto->actualizar()) {
                return ['success' => true, 'message' => 'Producto actualizado exitosamente'];
            }
            
            return ['success' => false, 'message' => 'Error al actualizar el producto'];
            
        } catch(Exception $e) {
            error_log("Error en ProductoController->actualizar: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()];
        }
    }

    public function eliminar($id) {
        try {
            if(empty($id) || !is_numeric($id)) {
                return ['success' => false, 'message' => 'ID inválido'];
            }
            
            $this->producto->id = $id;
            
            if($this->producto->eliminar()) {
                return ['success' => true, 'message' => 'Producto eliminado exitosamente'];
            }
            
            return ['success' => false, 'message' => 'Error al eliminar el producto'];
            
        } catch(Exception $e) {
            error_log("Error en ProductoController->eliminar: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()];
        }
    }

    public function listarTodos() {
        try {
            return $this->producto->obtenerTodos();
        } catch(Exception $e) {
            error_log("Error en ProductoController->listarTodos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId($id) {
        try {
            if(empty($id) || !is_numeric($id)) {
                return null;
            }
            
            return $this->producto->obtenerPorId($id);
            
        } catch(Exception $e) {
            error_log("Error en ProductoController->obtenerPorId: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerStockBajo($limite = 10) {
        try {
            return $this->producto->obtenerStockBajo($limite);
        } catch(Exception $e) {
            error_log("Error en ProductoController->obtenerStockBajo: " . $e->getMessage());
            return [];
        }
    }
}
?>