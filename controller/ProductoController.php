<?php
require_once __DIR__ . '/../model/ProductoModel.php';
require_once __DIR__ . '/../config/Database.php';

class ProductoController {
    private $db;
    private $producto;
    private $upload_dir;
    private $upload_url;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->producto = new Producto($this->db);
        
        // Configurar directorio de subida
        $this->upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/Sistema-de-gimnasio-web/assets/uploads/productos/';
        $this->upload_url = '/Sistema-de-gimnasio-web/assets/uploads/productos/';
        
        // Crear directorio si no existe
        if (!file_exists($this->upload_dir)) {
            mkdir($this->upload_dir, 0777, true);
        }
    }

    /**
     * Subir imagen al servidor
     */
    private function subirImagen($archivo) {
        // Si no hay archivo o hubo error
        if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Validar tipo de archivo
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($archivo['type'], $allowed_types)) {
            throw new Exception('Tipo de archivo no permitido. Solo JPG, PNG, WEBP y GIF');
        }

        // Validar tamaño (máximo 2MB)
        if ($archivo['size'] > 2 * 1024 * 1024) {
            throw new Exception('La imagen no puede ser mayor a 2MB');
        }

        // Validar que no haya errores
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error al subir el archivo. Código: ' . $archivo['error']);
        }

        // Generar nombre único
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombre_archivo = uniqid('prod_') . '_' . date('Ymd_His') . '.' . $extension;
        $ruta_destino = $this->upload_dir . $nombre_archivo;

        // Mover archivo
        if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            return $this->upload_url . $nombre_archivo;
        }

        throw new Exception('Error al mover el archivo al directorio de destino');
    }

    /**
     * Eliminar imagen del servidor
     */
    private function eliminarImagen($imagen_url) {
        if (empty($imagen_url)) {
            return true;
        }

        $ruta_imagen = $_SERVER['DOCUMENT_ROOT'] . $imagen_url;
        if (file_exists($ruta_imagen)) {
            return unlink($ruta_imagen);
        }
        return true;
    }

    /**
     * Agregar nuevo producto
     */
    public function agregar($datos, $archivo = null) {
        try {
            // Validaciones básicas
            if (empty($datos['nombre'])) {
                return ['success' => false, 'message' => 'El nombre del producto es requerido'];
            }
            
            if (empty($datos['categoria'])) {
                return ['success' => false, 'message' => 'La categoría es requerida'];
            }
            
            if (!isset($datos['precio']) || !is_numeric($datos['precio']) || $datos['precio'] <= 0) {
                return ['success' => false, 'message' => 'El precio debe ser un número válido mayor a 0'];
            }
            
            if (!isset($datos['stock']) || !is_numeric($datos['stock']) || $datos['stock'] < 0) {
                return ['success' => false, 'message' => 'El stock debe ser un número válido'];
            }

            // Subir imagen si se proporcionó
            $imagen_url = null;
            if ($archivo && isset($archivo['error']) && $archivo['error'] === UPLOAD_ERR_OK) {
                try {
                    $imagen_url = $this->subirImagen($archivo);
                } catch (Exception $e) {
                    return ['success' => false, 'message' => 'Error con la imagen: ' . $e->getMessage()];
                }
            }
            
            // Asignar valores al modelo
            $this->producto->nombre = trim($datos['nombre']);
            $this->producto->descripcion = !empty($datos['descripcion']) ? trim($datos['descripcion']) : null;
            $this->producto->categoria = trim($datos['categoria']);
            $this->producto->precio = floatval($datos['precio']);
            $this->producto->stock = intval($datos['stock']);
            $this->producto->imagen_url = $imagen_url;

            if ($this->producto->insertar()) {
                return ['success' => true, 'message' => 'Producto guardado exitosamente'];
            }
            
            return ['success' => false, 'message' => 'Error al guardar el producto en la base de datos'];
            
        } catch (Exception $e) {
            error_log("Error en ProductoController->agregar: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()];
        }
    }

    /**
     * Actualizar producto existente
     */
    public function actualizar($id, $datos, $archivo = null) {
        try {
            if (empty($id) || !is_numeric($id)) {
                return ['success' => false, 'message' => 'ID inválido'];
            }
            
            // Validaciones
            if (empty($datos['nombre'])) {
                return ['success' => false, 'message' => 'El nombre del producto es requerido'];
            }
            
            if (empty($datos['categoria'])) {
                return ['success' => false, 'message' => 'La categoría es requerida'];
            }
            
            if (!isset($datos['precio']) || !is_numeric($datos['precio']) || $datos['precio'] <= 0) {
                return ['success' => false, 'message' => 'El precio debe ser un número válido mayor a 0'];
            }
            
            if (!isset($datos['stock']) || !is_numeric($datos['stock']) || $datos['stock'] < 0) {
                return ['success' => false, 'message' => 'El stock debe ser un número válido'];
            }

            // Obtener producto actual para mantener imagen si no se sube nueva
            $producto_actual = $this->producto->obtenerPorId($id);
            if (!$producto_actual) {
                return ['success' => false, 'message' => 'Producto no encontrado'];
            }
            
            // Subir nueva imagen si se proporcionó
            $imagen_url = $producto_actual['imagen_url'];
            if ($archivo && isset($archivo['error']) && $archivo['error'] === UPLOAD_ERR_OK) {
                try {
                    // Eliminar imagen anterior si existe
                    if ($producto_actual['imagen_url']) {
                        $this->eliminarImagen($producto_actual['imagen_url']);
                    }
                    $imagen_url = $this->subirImagen($archivo);
                } catch (Exception $e) {
                    return ['success' => false, 'message' => 'Error con la imagen: ' . $e->getMessage()];
                }
            }
            
            // Asignar valores al modelo
            $this->producto->id_producto = $id;
            $this->producto->nombre = trim($datos['nombre']);
            $this->producto->descripcion = !empty($datos['descripcion']) ? trim($datos['descripcion']) : null;
            $this->producto->categoria = trim($datos['categoria']);
            $this->producto->precio = floatval($datos['precio']);
            $this->producto->stock = intval($datos['stock']);
            $this->producto->imagen_url = $imagen_url;

            if ($this->producto->actualizar()) {
                return ['success' => true, 'message' => 'Producto actualizado exitosamente'];
            }
            
            return ['success' => false, 'message' => 'Error al actualizar el producto'];
            
        } catch (Exception $e) {
            error_log("Error en ProductoController->actualizar: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()];
        }
    }

    /**
     * Eliminar producto (borrado lógico)
     */
    public function eliminar($id) {
        try {
            if (empty($id) || !is_numeric($id)) {
                return ['success' => false, 'message' => 'ID inválido'];
            }
            
            // Obtener producto para eliminar imagen
            $producto = $this->producto->obtenerPorId($id);
            
            $this->producto->id_producto = $id;
            
            if ($this->producto->eliminar()) {
                // Eliminar imagen si existe
                if ($producto && !empty($producto['imagen_url'])) {
                    $this->eliminarImagen($producto['imagen_url']);
                }
                return ['success' => true, 'message' => 'Producto eliminado exitosamente'];
            }
            
            return ['success' => false, 'message' => 'Error al eliminar el producto'];
            
        } catch (Exception $e) {
            error_log("Error en ProductoController->eliminar: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()];
        }
    }

    /**
     * Listar todos los productos activos
     */
    public function listarTodos() {
        try {
            return $this->producto->obtenerTodos();
        } catch (Exception $e) {
            error_log("Error en ProductoController->listarTodos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener producto por ID
     */
    public function obtenerPorId($id) {
        try {
            if (empty($id) || !is_numeric($id)) {
                return null;
            }
            
            return $this->producto->obtenerPorId($id);
            
        } catch (Exception $e) {
            error_log("Error en ProductoController->obtenerPorId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener productos con stock bajo
     */
    public function obtenerStockBajo($limite = 10) {
        try {
            return $this->producto->obtenerStockBajo($limite);
        } catch (Exception $e) {
            error_log("Error en ProductoController->obtenerStockBajo: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener productos por categoría
     */
    public function obtenerPorCategoria($categoria) {
        try {
            if (empty($categoria)) {
                return [];
            }
            return $this->producto->obtenerPorCategoria($categoria);
        } catch (Exception $e) {
            error_log("Error en ProductoController->obtenerPorCategoria: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Verificar si un producto existe por nombre
     */
    public function existeProducto($nombre) {
        try {
            if (empty($nombre)) {
                return false;
            }
            return $this->producto->existeProducto($nombre);
        } catch (Exception $e) {
            error_log("Error en ProductoController->existeProducto: " . $e->getMessage());
            return false;
        }
    }
}
?>