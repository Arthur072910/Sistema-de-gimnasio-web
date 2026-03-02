<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Membresia.php';

class MembresiaController {
    private $db;
    private $membresia;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->membresia = new Membresia($this->db);
    }

    // ============================================
    // MÉTODOS PARA OBTENER DATOS
    // ============================================
    
    public function obtenerActiva($id_cliente) {
        return $this->membresia->obtenerActiva($id_cliente);
    }

    public function obtenerMembresiaActiva($id_cliente) {
        return $this->membresia->obtenerMembresiaActiva($id_cliente);
    }

    public function listarPorCliente($id_cliente) {
        return $this->membresia->obtenerPorCliente($id_cliente);
    }

    public function estado($id_cliente) {
        return $this->membresia->verificarEstadoMembresia($id_cliente);
    }

    public function verificarAcceso($id_cliente) {
        return $this->membresia->verificarAcceso($id_cliente);
    }

    // ============================================
    // MÉTODOS PARA ACCIONES
    // ============================================
    
    public function comprar($id_cliente, $id_tipo_membresia) {
        return $this->membresia->asignarMembresia($id_cliente, $id_tipo_membresia);
    }

    public function renovar($id_membresia) {
        return $this->membresia->renovarMembresia($id_membresia);
    }

    public function cancelar($id_cliente) {
        return $this->membresia->cancelarMembresia($id_cliente);
    }
}
?>