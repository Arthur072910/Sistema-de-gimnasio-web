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

    
    public function estado($id_cliente) {
        return $this->membresia->verificarEstadoMembresia($id_cliente);
    }

    
    public function obtenerActiva($id_cliente) {
        return $this->membresia->verificarEstadoMembresia($id_cliente);
    }

    
    public function comprar($id_cliente, $id_tipo_membresia) {

        $resultado = $this->membresia->asignarMembresia($id_cliente, $id_tipo_membresia);

        if($resultado['success']){
            return true;
        } else {
            return false;
        }
    }

    
    public function renovar($id_membresia){

        $resultado = $this->membresia->renovarMembresia($id_membresia);

        if($resultado['success']){
            return true;
        } else {
            return false;
        }
    }

  
    public function cancelar($id_cliente){

        $resultado = $this->membresia->cancelarMembresia($id_cliente);

        if($resultado['success']){
            return true;
        } else {
            return false;
        }
    }
}
?>