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

    public function asignar($id_cliente, $id_tipo_membresia) {
        return $this->membresia->asignarMembresia($id_cliente, $id_tipo_membresia);
    }
}
?>