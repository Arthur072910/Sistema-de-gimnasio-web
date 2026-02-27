<?php
require_once __DIR__ . '/../config/Database.php';

class Membresia {

    private $conn;
    private $table = "membresias";

    public $id_membresia;
    public $id_cliente;
    public $id_tipo_membresia;
    public $fecha_inicio;
    public $fecha_vencimiento;
    public $codigo_qr;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

  
    public function verificarAcceso($id_cliente) {

        $query = "SELECT m.*, tm.nombre as tipo_membresia,
                         DATEDIFF(m.fecha_vencimiento, CURDATE()) as dias_restantes
                  FROM {$this->table} m
                  INNER JOIN tipo_membresia tm 
                        ON m.id_tipo_membresia = tm.id_tipo_membresia
                  WHERE m.id_cliente = :id_cliente
                  AND m.estado = 'activa'
                  AND m.fecha_vencimiento >= CURDATE()
                  ORDER BY m.fecha_vencimiento DESC
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'acceso' => true,
                'datos' => $row
            ];
        }

        return ['acceso' => false];
    }

   
    public function verificarEstadoMembresia($id_cliente) {

        $query = "SELECT m.*, tm.nombre as tipo_membresia,
                         DATEDIFF(m.fecha_vencimiento, CURDATE()) as dias_restantes
                  FROM {$this->table} m
                  INNER JOIN tipo_membresia tm 
                        ON m.id_tipo_membresia = tm.id_tipo_membresia
                  WHERE m.id_cliente = :id_cliente
                  AND m.estado = 'activa'
                  AND m.fecha_vencimiento >= CURDATE()
                  ORDER BY m.fecha_vencimiento DESC
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function asignarMembresia($id_cliente, $id_tipo_membresia) {

     
        $check = "SELECT id_membresia FROM {$this->table}
                  WHERE id_cliente = :id_cliente
                  AND estado = 'activa'
                  AND fecha_vencimiento >= CURDATE()
                  LIMIT 1";

        $stmt_check = $this->conn->prepare($check);
        $stmt_check->bindParam(':id_cliente', $id_cliente);
        $stmt_check->execute();

        if($stmt_check->rowCount() > 0){
            return ['success' => false, 'message' => 'Ya tienes una membresía activa'];
        }

       
        $query_tipo = "SELECT duracion_dias 
                       FROM tipo_membresia 
                       WHERE id_tipo_membresia = :id_tipo
                       AND estado = 'activo'";

        $stmt_tipo = $this->conn->prepare($query_tipo);
        $stmt_tipo->bindParam(':id_tipo', $id_tipo_membresia);
        $stmt_tipo->execute();
        $tipo = $stmt_tipo->fetch(PDO::FETCH_ASSOC);

        if(!$tipo){
            return ['success' => false, 'message' => 'Tipo inválido'];
        }

        $fecha_inicio = date('Y-m-d');
        $fecha_vencimiento = date('Y-m-d', strtotime("+{$tipo['duracion_dias']} days"));
        $codigo_qr = uniqid("GYM-{$id_cliente}-");

        $query = "INSERT INTO {$this->table}
                  (id_cliente, id_tipo_membresia, fecha_inicio,
                   fecha_vencimiento, codigo_qr, estado)
                  VALUES
                  (:id_cliente, :id_tipo, :inicio,
                   :vencimiento, :qr, 'activa')";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':id_tipo', $id_tipo_membresia);
        $stmt->bindParam(':inicio', $fecha_inicio);
        $stmt->bindParam(':vencimiento', $fecha_vencimiento);
        $stmt->bindParam(':qr', $codigo_qr);

        if($stmt->execute()){

            $this->registrarHistorial(
                $id_cliente,
                'membresia_creada',
                $this->conn->lastInsertId(),
                "Membresía creada hasta {$fecha_vencimiento}"
            );

            return ['success' => true];
        }

        return ['success' => false];
    }

  
    public function renovarMembresia($id_membresia){

        $query = "SELECT * FROM {$this->table}
                  WHERE id_membresia = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_membresia);
        $stmt->execute();
        $membresia = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$membresia){
            return ['success' => false];
        }

        $query_tipo = "SELECT duracion_dias 
                       FROM tipo_membresia
                       WHERE id_tipo_membresia = :id_tipo";

        $stmt_tipo = $this->conn->prepare($query_tipo);
        $stmt_tipo->bindParam(':id_tipo', $membresia['id_tipo_membresia']);
        $stmt_tipo->execute();
        $tipo = $stmt_tipo->fetch(PDO::FETCH_ASSOC);

        $base = ($membresia['fecha_vencimiento'] < date('Y-m-d'))
                ? date('Y-m-d')
                : $membresia['fecha_vencimiento'];

        $nueva_fecha = date('Y-m-d', strtotime($base . " +{$tipo['duracion_dias']} days"));

        $update = "UPDATE {$this->table}
                   SET fecha_vencimiento = :fecha,
                       estado = 'activa'
                   WHERE id_membresia = :id";

        $stmt_up = $this->conn->prepare($update);
        $stmt_up->bindParam(':fecha', $nueva_fecha);
        $stmt_up->bindParam(':id', $id_membresia);

        if($stmt_up->execute()){

            $this->registrarHistorial(
                $membresia['id_cliente'],
                'membresia_renovada',
                $id_membresia,
                "Renovada hasta {$nueva_fecha}"
            );

            return ['success' => true];
        }

        return ['success' => false];
    }


    public function cancelarMembresia($id_cliente){

        $query = "SELECT id_membresia FROM {$this->table}
                  WHERE id_cliente = :id_cliente
                  AND estado = 'activa'
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        $membresia = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$membresia){
            return ['success' => false];
        }

        $update = "UPDATE {$this->table}
                   SET estado = 'cancelada'
                   WHERE id_membresia = :id";

        $stmt_up = $this->conn->prepare($update);
        $stmt_up->bindParam(':id', $membresia['id_membresia']);

        if($stmt_up->execute()){

            $this->registrarHistorial(
                $id_cliente,
                'membresia_cancelada',
                $membresia['id_membresia'],
                'Membresía cancelada'
            );

            return ['success' => true];
        }

        return ['success' => false];
    }


    private function registrarHistorial($id_cliente, $tipo, $referencia, $descripcion){

        $query = "INSERT INTO historial
                  (id_cliente, tipo_accion, id_referencia, descripcion)
                  VALUES (:cliente, :tipo, :ref, :desc)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente', $id_cliente);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':ref', $referencia);
        $stmt->bindParam(':desc', $descripcion);

        return $stmt->execute();
    }

    public function obtenerMembresiaActiva($id_cliente)
{
    $query = "SELECT m.*, 
                     tm.nombre AS tipo_membresia,
                     tm.precio,
                     tm.duracion_dias,
                     DATEDIFF(m.fecha_vencimiento, CURDATE()) AS dias_restantes
              FROM {$this->table} m
              INNER JOIN tipo_membresia tm 
                    ON m.id_tipo_membresia = tm.id_tipo_membresia
              WHERE m.id_cliente = :id_cliente
              AND m.estado = 'activa'
              AND m.fecha_vencimiento >= CURDATE()
              ORDER BY m.fecha_vencimiento DESC
              LIMIT 1";

    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id_cliente', $id_cliente);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}
}
?>