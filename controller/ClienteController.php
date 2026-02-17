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

    public function agregar($datos) {
        try {
            // Validar email
            if(empty($datos['email']) || !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Email inválido'];
            }
            
            // Validar contraseña
            if(empty($datos['contraseña']) || strlen($datos['contraseña']) < 6) {
                return ['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres'];
            }
            
            // Asignar datos
            $this->cliente->nombre = trim($datos['nombre']);
            $this->cliente->apellido = trim($datos['apellido']);
            $this->cliente->email = trim($datos['email']);
            $this->cliente->contraseña = $datos['contraseña'];
            $this->cliente->telefono = !empty($datos['telefono']) ? trim($datos['telefono']) : null;
            $this->cliente->fecha_nacimiento = !empty($datos['fecha_nacimiento']) ? $datos['fecha_nacimiento'] : null;
            $this->cliente->genero = !empty($datos['genero']) ? $datos['genero'] : null;

            // Verificar si email existe
            if($this->cliente->emailExiste()) {
                return ['success' => false, 'message' => 'El email ya está registrado'];
            }

            // Registrar
            $this->cliente->registrar();
            return ['success' => true, 'message' => 'Cliente registrado exitosamente'];
            
        } catch(Exception $e) {
            return ['success' => false, 'message' => 'Error al registrar: ' . $e->getMessage()];
        }
    }

    public function login($email, $password) {
        $this->cliente->email = trim($email);
        $this->cliente->contraseña = $password;
        
        if($this->cliente->login()) {
            return [
                'success' => true,
                'id_cliente' => $this->cliente->id_cliente,
                'nombre' => $this->cliente->nombre,
                'email' => $this->cliente->email
            ];
        }
        return ['success' => false, 'message' => 'Email o contraseña incorrectos'];
    }

    public function verPerfil($id_cliente) {
        if(empty($id_cliente)) return null;
        return $this->cliente->obtenerPerfilCompleto($id_cliente);
    }

    public function obtenerDatosCompletos($id_cliente) {
        if (empty($id_cliente)) {
            return null;
        }
        return $this->cliente->obtenerPerfilCompleto($id_cliente);
    }
}
?>