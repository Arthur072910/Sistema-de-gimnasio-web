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

    /**
     * REGISTRAR NUEVO CLIENTE (en dos tablas)
     */
    public function registrar() {
        try {
            $this->conn->beginTransaction();
            
            // 1. Insertar en usuarios
            $query1 = "INSERT INTO " . $this->table_usuarios . " 
                      (email, contraseña, rol, estado) 
                      VALUES (:email, :contraseña, 'cliente', 'activo')";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->bindParam(':email', $this->email);
            $stmt1->bindParam(':contraseña', $this->contraseña);
            $stmt1->execute();
            
            // Obtener el ID del usuario creado
            $this->id_usuario = $this->conn->lastInsertId();
            
            // 2. Insertar en clientes
            $query2 = "INSERT INTO " . $this->table_clientes . " 
                      (id_usuario, nombre, apellido, telefono, fecha_nacimiento, genero) 
                      VALUES (:id_usuario, :nombre, :apellido, :telefono, :fecha_nacimiento, :genero)";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':id_usuario', $this->id_usuario);
            $stmt2->bindParam(':nombre', $this->nombre);
            $stmt2->bindParam(':apellido', $this->apellido);
            $stmt2->bindParam(':telefono', $this->telefono);
            $stmt2->bindParam(':fecha_nacimiento', $this->fecha_nacimiento);
            $stmt2->bindParam(':genero', $this->genero);
            $stmt2->execute();
            
            $this->id_cliente = $this->conn->lastInsertId();
            
            $this->conn->commit();
            return true;
            
        } catch(Exception $e) {
            $this->conn->rollBack();
            error_log("Error en registro: " . $e->getMessage());
            return false;
        }
    }

    /**
     * LOGIN (busca en usuarios)
     */
    public function login() {
        $query = "SELECT u.id_usuario, u.email, u.contraseña, u.rol,
                         c.id_cliente, c.nombre, c.apellido
                  FROM " . $this->table_usuarios . " u
                  LEFT JOIN " . $this->table_clientes . " c ON u.id_usuario = c.id_usuario
                  WHERE u.email = :email AND u.estado = 'activo'
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Comparar contraseña (texto plano por ahora)
            if($this->contraseña === $row['contraseña']) {
                $this->id_usuario = $row['id_usuario'];
                $this->id_cliente = $row['id_cliente'];
                $this->nombre = $row['nombre'];
                $this->apellido = $row['apellido'];
                $this->email = $row['email'];
                return true;
            }
        }
        return false;
    }

    /**
     * VERIFICAR SI EMAIL YA EXISTE
     */
    public function emailExiste() {
        $query = "SELECT id_usuario FROM " . $this->table_usuarios . " 
                  WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * OBTENER CLIENTE POR ID DE USUARIO
     */
    public function obtenerPorUsuario($id_usuario) {
        $query = "SELECT c.*, u.email 
                  FROM " . $this->table_clientes . " c
                  INNER JOIN " . $this->table_usuarios . " u ON c.id_usuario = u.id_usuario
                  WHERE c.id_usuario = :id_usuario LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
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
}
?>