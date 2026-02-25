<?php
class Producto {
    private $conn;
    private $table = "productos";

    public $id;
    public $nombre;
    public $categoria;
    public $precio;
    public $stock;
    public $proveedor;
    public $fecha_registro;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los productos
    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table . " WHERE estado = 1 ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un producto por ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ? AND estado = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->categoria = $row['categoria'];
            $this->precio = $row['precio'];
            $this->stock = $row['stock'];
            $this->proveedor = $row['proveedor'];
            
            return $row;
        }
        return false;
    }

    // Insertar nuevo producto
    public function insertar() {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      (nombre, categoria, precio, stock, proveedor) 
                      VALUES (:nombre, :categoria, :precio, :stock, :proveedor)";
            
            $stmt = $this->conn->prepare($query);
            
            // Limpiar datos
            $this->nombre = trim($this->nombre);
            $this->categoria = trim($this->categoria);
            $this->proveedor = trim($this->proveedor);
            
            // Bind de parámetros
            $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
            $stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $this->precio);
            $stmt->bindParam(':stock', $this->stock, PDO::PARAM_INT);
            $stmt->bindParam(':proveedor', $this->proveedor, PDO::PARAM_STR);
            
            if($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
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
                          categoria = :categoria, 
                          precio = :precio, 
                          stock = :stock, 
                          proveedor = :proveedor 
                      WHERE id = :id AND estado = 1";
            
            $stmt = $this->conn->prepare($query);
            
            // Limpiar datos
            $this->nombre = trim($this->nombre);
            $this->categoria = trim($this->categoria);
            $this->proveedor = trim($this->proveedor);
            
            // Bind de parámetros
            $stmt->bindParam(':nombre', $this->nombre, PDO::PARAM_STR);
            $stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $this->precio);
            $stmt->bindParam(':stock', $this->stock, PDO::PARAM_INT);
            $stmt->bindParam(':proveedor', $this->proveedor, PDO::PARAM_STR);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch(Exception $e) {
            error_log("Error al actualizar producto: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar producto (borrado lógico)
    public function eliminar() {
        try {
            $query = "UPDATE " . $this->table . " SET estado = 0 WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch(Exception $e) {
            error_log("Error al eliminar producto: " . $e->getMessage());
            return false;
        }
    }

    // Verificar si un producto existe
    public function existeProducto($nombre) {
        $query = "SELECT id FROM " . $this->table . " WHERE nombre = ? AND estado = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // Obtener productos con stock bajo
    public function obtenerStockBajo($limite = 10) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE stock <= ? AND estado = 1 
                  ORDER BY stock ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limite, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>