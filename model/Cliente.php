<?php
class Cliente {
    private $conn;
    private $table_usuarios = "usuarios";
    private $table_clientes = "clientes";

    public $id_cliente;
    public $id_usuario;
    public $nombre;
    public $apellido;
    public $email;
    public $contraseña;
    public $telefono;
    public $fecha_nacimiento;
    public $genero;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrar() {
        try {
            $this->conn->beginTransaction();
            
            // 1. Insertar en usuarios - SIN HASH
            $query1 = "INSERT INTO usuarios (email, contraseña, rol, estado) 
                      VALUES (:email, :password, 'cliente', 'activo')";
            
            $stmt1 = $this->conn->prepare($query1);
            
            // SIN password_hash - TEXTO PLANO
            $email_limpio = trim($this->email);
            $password_plano = $this->contraseña; // TEXTO PLANO
            
            $stmt1->bindParam(':email', $email_limpio, PDO::PARAM_STR);
            $stmt1->bindParam(':password', $password_plano, PDO::PARAM_STR);
            
            if(!$stmt1->execute()) {
                $error = $stmt1->errorInfo();
                throw new Exception("Error al insertar usuario: " . print_r($error, true));
            }
            
            $this->id_usuario = $this->conn->lastInsertId();
            
            // 2. Insertar en clientes
            $query2 = "INSERT INTO clientes 
                      (id_usuario, nombre, apellido, telefono, fecha_nacimiento, genero) 
                      VALUES (:id_usuario, :nombre, :apellido, :telefono, :fecha_nacimiento, :genero)";
            
            $stmt2 = $this->conn->prepare($query2);
            
            $nombre_limpio = trim($this->nombre);
            $apellido_limpio = trim($this->apellido);
            $telefono_limpio = $this->telefono;
            $fecha_limpia = $this->fecha_nacimiento;
            $genero_limpio = $this->genero;
            
            $stmt2->bindParam(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
            $stmt2->bindParam(':nombre', $nombre_limpio, PDO::PARAM_STR);
            $stmt2->bindParam(':apellido', $apellido_limpio, PDO::PARAM_STR);
            $stmt2->bindParam(':telefono', $telefono_limpio, PDO::PARAM_STR);
            $stmt2->bindParam(':fecha_nacimiento', $fecha_limpia, PDO::PARAM_STR);
            $stmt2->bindParam(':genero', $genero_limpio, PDO::PARAM_STR);
            
            if(!$stmt2->execute()) {
                $error = $stmt2->errorInfo();
                throw new Exception("Error al insertar cliente: " . print_r($error, true));
            }
            
            $this->id_cliente = $this->conn->lastInsertId();
            
            $this->conn->commit();
            return true;
            
        } catch(Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("Error en registro: " . $e->getMessage());
            throw $e;
        }
    }

    public function login() {
        $query = "SELECT u.id_usuario, u.email, u.contraseña, u.rol,
                         c.id_cliente, c.nombre, c.apellido
                  FROM usuarios u
                  LEFT JOIN clientes c ON u.id_usuario = c.id_usuario
                  WHERE u.email = :email AND u.estado = 'activo'
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        
        $email_limpio = trim($this->email);
        $stmt->bindParam(':email', $email_limpio, PDO::PARAM_STR);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // COMPARACIÓN DIRECTA - TEXTO PLANO
            if($this->contraseña === $row['contraseña']) {
                $this->id_usuario = $row['id_usuario'];
                $this->id_cliente = $row['id_cliente'];
                $this->nombre = $row['nombre'];
                $this->apellido = $row['apellido'];
                return true;
            }
        }
        return false;
    }

    public function emailExiste() {
        $query = "SELECT id_usuario FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        
        $email_limpio = trim($this->email);
        $stmt->bindParam(':email', $email_limpio, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function obtenerPorUsuario($id_usuario) {
        $query = "SELECT c.*, u.email 
                  FROM clientes c
                  INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
                  WHERE c.id_usuario = :id_usuario LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id_cliente = $row['id_cliente'];
            $this->id_usuario = $row['id_usuario'];
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->telefono = $row['telefono'];
            $this->email = $row['email'];
            return true;
        }
        return false;
    }

    public function obtenerPerfilCompleto($id_cliente) {
    try {
        $query = "SELECT u.email, u.rol, c.*, 
                         m.fecha_vencimiento, m.estado as estado_membresia, 
                         tm.nombre as plan 
                  FROM " . $this->table_clientes . " c
                  INNER JOIN " . $this->table_usuarios . " u ON c.id_usuario = u.id_usuario
                  LEFT JOIN membresias m ON c.id_cliente = m.id_cliente
                  LEFT JOIN tipo_membresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
                  WHERE c.id_cliente = :id
                  ORDER BY m.fecha_creacion DESC LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_cliente, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerPerfilCompleto: " . $e->getMessage());
            return false;
        }
    }
}
?>