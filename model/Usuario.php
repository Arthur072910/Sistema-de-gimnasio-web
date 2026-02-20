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

    public function emailExiste() {
        $query = "SELECT id_usuario FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
?>