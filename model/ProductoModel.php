<?php
class Producto {
    private $conn;
    private $table = "productos";

    public $id_producto;
    public $nombre;
    public $descripcion;
    public $categoria;
    public $precio;
    public $stock;
    public $imagen_url;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los productos activos
    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table . " WHERE estado = 'activo' ORDER BY id_producto DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un producto por ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_producto = ? AND estado = 'activo' LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id_producto = $row['id_producto'];
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->categoria = $row['categoria'];
            $this->precio = $row['precio'];
            $this->stock = $row['stock'];
            $this->imagen_url = $row['imagen_url'];
            
            return $row;
        }
        return false;
    }

    // Insertar nuevo producto
    public function insertar() {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      (nombre, descripcion, categoria, precio, stock, imagen_url, estado) 
                      VALUES (:nombre, :descripcion, :categoria, :precio, :stock, :imagen_url, 'activo')";
            
            $stmt = $this->conn->prepare($query);
            
            // Limpiar datos
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->categoria = htmlspecialchars(strip_tags($this->categoria));
            $this->imagen_url = htmlspecialchars(strip_tags($this->imagen_url));
            
            // Bind de parámetros
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':categoria', $this->categoria);
            $stmt->bindParam(':precio', $this->precio);
            $stmt->bindParam(':stock', $this->stock);
            $stmt->bindParam(':imagen_url', $this->imagen_url);
            
            if($stmt->execute()) {
                $this->id_producto = $this->conn->lastInsertId();
                return true;
            }
            
            return false;
            
        } catch(Exception $e) {
            error_log("Error al insertar producto: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar producto
    public function actualizar() {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET nombre = :nombre, 
                          descripcion = :descripcion,
                          categoria = :categoria, 
                          precio = :precio, 
                          stock = :stock, 
                          imagen_url = :imagen_url
                      WHERE id_producto = :id_producto AND estado = 'activo'";
            
            $stmt = $this->conn->prepare($query);
            
            // Limpiar datos
            $this->nombre = htmlspecialchars(strip_tags($this->nombre));
            $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
            $this->categoria = htmlspecialchars(strip_tags($this->categoria));
            $this->imagen_url = htmlspecialchars(strip_tags($this->imagen_url));
            
            // Bind de parámetros
            $stmt->bindParam(':nombre', $this->nombre);
            $stmt->bindParam(':descripcion', $this->descripcion);
            $stmt->bindParam(':categoria', $this->categoria);
            $stmt->bindParam(':precio', $this->precio);
            $stmt->bindParam(':stock', $this->stock);
            $stmt->bindParam(':imagen_url', $this->imagen_url);
            $stmt->bindParam(':id_producto', $this->id_producto);
            
            return $stmt->execute();
            
        } catch(Exception $e) {
            error_log("Error al actualizar producto: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar producto (borrado lógico)
    public function eliminar() {
        try {
            $query = "UPDATE " . $this->table . " SET estado = 'inactivo' WHERE id_producto = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id_producto);
            
            return $stmt->execute();
            
        } catch(Exception $e) {
            error_log("Error al eliminar producto: " . $e->getMessage());
            return false;
        }
    }

    // Verificar si un producto existe
    public function existeProducto($nombre) {
        $query = "SELECT id_producto FROM " . $this->table . " WHERE nombre = ? AND estado = 'activo' LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nombre);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // Obtener productos con stock bajo
    public function obtenerStockBajo($limite = 10) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE stock <= ? AND estado = 'activo' 
                  ORDER BY stock ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limite);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener productos por categoría
    public function obtenerPorCategoria($categoria) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE categoria = ? AND estado = 'activo' 
                  ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $categoria);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar stock
    public function actualizarStock($id_producto, $cantidad) {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET stock = stock - ? 
                      WHERE id_producto = ? AND stock >= ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $cantidad);
            $stmt->bindParam(2, $id_producto);
            $stmt->bindParam(3, $cantidad);
            
            return $stmt->execute();
        } catch(Exception $e) {
            error_log("Error al actualizar stock: " . $e->getMessage());
            return false;
        }
    }
}
?>