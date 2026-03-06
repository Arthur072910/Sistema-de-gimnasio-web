<?php
require_once __DIR__ . '/../config/Database.php';

class Membresia {
    private $conn;
    private $table = "membresias";

    public function __construct($db) {
        $this->conn = $db;
    }

    // ============================================
    // MÉTODOS PARA OBTENER DATOS
    // ============================================
    
    public function obtenerActiva($id_cliente) {
        $query = "SELECT m.*, tm.nombre as tipo_membresia 
                  FROM " . $this->table . " m
                  JOIN tipo_membresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
                  WHERE m.id_cliente = :id_cliente 
                  AND m.estado = 'activa'
                  ORDER BY m.fecha_creacion DESC
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerMembresiaActiva($id_cliente) {
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

    public function obtenerPorCliente($id_cliente) {
        $query = "SELECT m.*, tm.nombre as tipo_membresia 
                  FROM " . $this->table . " m
                  JOIN tipo_membresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
                  WHERE m.id_cliente = :id_cliente
                  ORDER BY m.fecha_creacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        return $this->obtenerMembresiaActiva($id_cliente);
    }

    // ============================================
    // MÉTODOS PARA CREAR/MODIFICAR
    // ============================================

    public function asignarMembresia($id_cliente, $id_tipo_membresia) {
        // Verificar si ya tiene membresía activa
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

        // Obtener duración del plan
        $query_tipo = "SELECT duracion_dias, nombre, precio 
                       FROM tipo_membresia 
                       WHERE id_tipo_membresia = :id_tipo
                       AND estado = 'activo'";

        $stmt_tipo = $this->conn->prepare($query_tipo);
        $stmt_tipo->bindParam(':id_tipo', $id_tipo_membresia);
        $stmt_tipo->execute();
        $tipo = $stmt_tipo->fetch(PDO::FETCH_ASSOC);

        if(!$tipo){
            return ['success' => false, 'message' => 'Tipo de membresía inválido'];
        }

        $fecha_inicio = date('Y-m-d');
        $fecha_vencimiento = date('Y-m-d', strtotime("+{$tipo['duracion_dias']} days"));
        $codigo_qr = uniqid("GYM-{$id_cliente}-");

        $query = "INSERT INTO {$this->table}
                  (id_cliente, id_tipo_membresia, fecha_inicio, fecha_vencimiento, codigo_qr, estado)
                  VALUES (:id_cliente, :id_tipo, :inicio, :vencimiento, :qr, 'activa')";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':id_tipo', $id_tipo_membresia);
        $stmt->bindParam(':inicio', $fecha_inicio);
        $stmt->bindParam(':vencimiento', $fecha_vencimiento);
        $stmt->bindParam(':qr', $codigo_qr);

        if($stmt->execute()){
            $id_membresia = $this->conn->lastInsertId();
            
            $this->registrarHistorial(
                $id_cliente,
                'membresia_creada',
                $id_membresia,
                "Membresía creada hasta {$fecha_vencimiento}"
            );

            return [
                'success' => true, 
                'id_membresia' => $id_membresia,
                'fecha_vencimiento' => $fecha_vencimiento
            ];
        }

        return ['success' => false, 'message' => 'Error al crear membresía'];
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

    public function obtenerDatosParaAlerta($id_usuario) {
        $query = "SELECT m.id_membresia, m.id_cliente, m.fecha_vencimiento, 
                  tm.nombre AS tipo_membresia, u.email, c.nombre AS nombre_usuario,
                  DATEDIFF(m.fecha_vencimiento, CURDATE()) AS dias_restantes
                  FROM membresias m
                  INNER JOIN tipo_membresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
                  INNER JOIN clientes c ON m.id_cliente = c.id_cliente
                  INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
                  WHERE u.id_usuario = :id_usuario AND m.estado = 'activa' LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // FUNCIÓN PROFESIONAL: Revisa si ya se envió la alerta hoy
    // En model/Membresia.php

    public function yaSeEnvioAlertaHoy($id_cliente, $id_membresia) {
        // Ahora buscamos si ya existe una notificación para ESE cliente y ESA membresía hoy
        $query = "SELECT COUNT(*) FROM notificaciones 
                WHERE id_cliente = :id_c 
                AND mensaje LIKE :id_m
                AND tipo = 'vencimiento_proximo' 
                AND DATE(fecha_envio) = CURDATE()";
        
        $stmt = $this->conn->prepare($query);
        // Usamos el ID de membresía dentro del mensaje para identificarla
        $stmt->execute([
            ':id_c' => $id_cliente, 
            ':id_m' => "%ID_MEMB: " . $id_membresia . "%"
        ]);
        return $stmt->fetchColumn() > 0;
    }

    public function registrarNotificacion($id_cliente, $id_membresia, $dias) {
        $mensaje = "Alerta de $dias dias enviada. ID_MEMB: $id_membresia";
        $query = "INSERT INTO notificaciones (id_cliente, tipo, mensaje) 
                VALUES (:id_c, 'vencimiento_proximo', :msg)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_c' => $id_cliente,
            ':msg' => $mensaje
        ]);
    }
}
?>