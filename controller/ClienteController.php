<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Cliente.php';

class ClienteController {
    private $db;
    private $cliente;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->cliente = new Cliente($this->db);
    }

    // Agregar cliente
    public function agregar($datos) {
        $this->cliente->nombre = $datos['nombre'];
        $this->cliente->apellido = $datos['apellido'];
        $this->cliente->email = $datos['email'];
        $this->cliente->contraseña = $datos['contraseña']; // texto plano
        $this->cliente->telefono = $datos['telefono'] ?? '';

        if($this->cliente->emailExiste()) {
            return ['success' => false, 'message' => 'El email ya está registrado'];
        }

        if($this->cliente->registrar()) {
            return ['success' => true, 'message' => 'Cliente registrado exitosamente'];
        }

        return ['success' => false, 'message' => 'Error al registrar'];
    }

    // Login
    public function login($email, $password) {
        $this->cliente->email = $email;
        $this->cliente->contraseña = $password;
        
        if($this->cliente->login()) {
            return [
                'success' => true,
                'id_cliente' => $this->cliente->id_cliente,
                'nombre' => $this->cliente->nombre
            ];
        }
        return ['success' => false, 'message' => 'Email o contraseña incorrectos'];
    }
}
?>