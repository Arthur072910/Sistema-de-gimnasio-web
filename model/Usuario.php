<?php
class Usuario {
    private $conn;
    private $table = "usuarios";

    public $id_usuario;
    public $email;
    public $contraseña;
    public $rol;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Login - aca verifica las credenciales para logearse
    public function login() {
        $query = "SELECT id_usuario, email, contraseña, rol, estado 
                  FROM " . $this->table . " 
                  WHERE email = ? AND estado = 'activo' 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // aca se verifica la contraseña
            if(password_verify($this->contraseña, $row['contraseña'])) {
                $this->id_usuario = $row['id_usuario'];
                $this->email = $row['email'];
                $this->rol = $row['rol'];
                $this->estado = $row['estado'];
                return true;
            }
        }
        return false;
    }

    // esta funcion es para registrar un nuevo usuario 
    public function registrar() {
        $query = "INSERT INTO " . $this->table . " 
                  (email, contraseña, rol, estado) 
                  VALUES (?, ?, ?, 'activo')";
        
        $stmt = $this->conn->prepare($query);

        // limpia dato
        $email_limpio = htmlspecialchars(strip_tags($this->email));
        $hash = password_hash($this->contraseña, PASSWORD_BCRYPT);
        $rol_limpio = htmlspecialchars(strip_tags($this->rol));

        $stmt->bindParam(1, $email_limpio);
        $stmt->bindParam(2, $hash);
        $stmt->bindParam(3, $rol_limpio);

        try {
            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            echo "Error al registrar: " . $e->getMessage();
            return false;
        }
    }

    // Verificar si el email ya existe
    public function emailExiste() {
        $query = "SELECT id_usuario FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // Obtener todos los usuarios
    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>