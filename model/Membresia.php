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

    /**
     * VERIFICAR SI UN CLIENTE TIENE MEMBRESÍA ACTIVA
     * Esta es la función principal para controlar acceso al gym
     */
    public function verificarAcceso($id_cliente) {
        // Buscar membresía activa y no vencida para el cliente
        $query = "SELECT m.*, tm.nombre as tipo_membresia, tm.duracion_dias, 
                         c.nombre, c.apellido,
                         DATEDIFF(m.fecha_vencimiento, CURDATE()) as dias_restantes
                  FROM " . $this->table . " m
                  INNER JOIN tipo_membresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
                  INNER JOIN clientes c ON m.id_cliente = c.id_cliente
                  WHERE m.id_cliente = :id_cliente 
                  AND m.estado = 'activa'
                  AND m.fecha_vencimiento >= CURDATE()
                  ORDER BY m.fecha_vencimiento DESC 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id_membresia = $row['id_membresia'];
            $this->fecha_inicio = $row['fecha_inicio'];
            $this->fecha_vencimiento = $row['fecha_vencimiento'];
            $this->estado = $row['estado'];
            
            return [
                'acceso' => true,
                'mensaje' => 'Acceso permitido',
                'datos' => [
                    'cliente' => $row['nombre'] . ' ' . $row['apellido'],
                    'tipo_membresia' => $row['tipo_membresia'],
                    'dias_restantes' => $row['dias_restantes'],
                    'fecha_vencimiento' => $row['fecha_vencimiento']
                ]
            ];
        }
        
        return ['acceso' => false, 'mensaje' => 'Membresía no activa o vencida'];
    }

    /**
     * VERIFICAR SOLO EL ESTADO (sin registrar acceso)
     * Útil para mostrar en perfil o dashboard
     */
    public function verificarEstadoMembresia($id_cliente) {
        $query = "SELECT m.*, tm.nombre as tipo_membresia,
                         DATEDIFF(m.fecha_vencimiento, CURDATE()) as dias_restantes
                  FROM " . $this->table . " m
                  INNER JOIN tipo_membresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
                  WHERE m.id_cliente = :id_cliente 
                  AND m.estado = 'activa'
                  ORDER BY m.fecha_vencimiento DESC 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($row['fecha_vencimiento'] >= date('Y-m-d')) {
                $row['estado_real'] = 'activa';
                $row['color_estado'] = 'success';
            } else {
                $row['estado_real'] = 'vencida';
                $row['color_estado'] = 'danger';
            }
            
            return $row;
        }
        
        return null;
    }

    /**
     * OBTENER MEMBRESÍAS PRÓXIMAS A VENCER
     */
    public function getProximasVencer($dias = 5) {
        $fecha_limite = date('Y-m-d', strtotime('+' . $dias . ' days'));
        
        $query = "SELECT m.*, c.nombre, c.apellido, c.telefono, 
                         u.email, tm.nombre as tipo_membresia,
                         DATEDIFF(m.fecha_vencimiento, CURDATE()) as dias_restantes
                  FROM " . $this->table . " m
                  INNER JOIN clientes c ON m.id_cliente = c.id_cliente
                  INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
                  INNER JOIN tipo_membresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
                  WHERE m.estado = 'activa'
                  AND m.fecha_vencimiento BETWEEN CURDATE() AND :fecha_limite
                  ORDER BY m.fecha_vencimiento ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fecha_limite', $fecha_limite);
        $stmt->execute();
        return $stmt;
    }

    /**
     * ASIGNAR NUEVA MEMBRESÍA A UN CLIENTE
     */
    public function asignarMembresia($id_cliente, $id_tipo_membresia) {
        // Obtener duración del tipo de membresía
        $query_tipo = "SELECT duracion_dias, precio FROM tipo_membresia 
                       WHERE id_tipo_membresia = :id_tipo AND estado = 'activo'";
        $stmt_tipo = $this->conn->prepare($query_tipo);
        $stmt_tipo->bindParam(':id_tipo', $id_tipo_membresia);
        $stmt_tipo->execute();
        $tipo = $stmt_tipo->fetch(PDO::FETCH_ASSOC);

        if(!$tipo) {
            return ['success' => false, 'message' => 'Tipo de membresía no válido'];
        }

        $fecha_inicio = date('Y-m-d');
        $fecha_vencimiento = date('Y-m-d', strtotime('+' . $tipo['duracion_dias'] . ' days'));
        
        // Generar código QR único
        $codigo_qr = uniqid('GYM-' . $id_cliente . '-');

        // Insertar nueva membresía
        $query = "INSERT INTO " . $this->table . " 
                  (id_cliente, id_tipo_membresia, fecha_inicio, fecha_vencimiento, codigo_qr, estado) 
                  VALUES (:id_cliente, :id_tipo, :fecha_inicio, :fecha_vencimiento, :codigo_qr, 'activa')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':id_tipo', $id_tipo_membresia);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_vencimiento', $fecha_vencimiento);
        $stmt->bindParam(':codigo_qr', $codigo_qr);

        if($stmt->execute()) {
            // Registrar en historial
            $this->registrarHistorial($id_cliente, 'membresia_creada', 
                $this->conn->lastInsertId(), 
                'Membresía ' . $tipo['duracion_dias'] . ' días asignada');

            return ['success' => true, 'message' => 'Membresía asignada correctamente'];
        }
        
        return ['success' => false, 'message' => 'Error al asignar membresía'];
    }

    /**
     * RENOVAR MEMBRESÍA
     */
    public function renovarMembresia($id_membresia) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE id_membresia = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_membresia);
        $stmt->execute();
        $membresia = $stmt->fetch(PDO::FETCH_ASSOC);

        if($membresia) {
            // Obtener duración del tipo de membresía
            $query_tipo = "SELECT duracion_dias FROM tipo_membresia 
                           WHERE id_tipo_membresia = :id_tipo";
            $stmt_tipo = $this->conn->prepare($query_tipo);
            $stmt_tipo->bindParam(':id_tipo', $membresia['id_tipo_membresia']);
            $stmt_tipo->execute();
            $tipo = $stmt_tipo->fetch(PDO::FETCH_ASSOC);

            // Si está vencida, renovar desde hoy
            if($membresia['fecha_vencimiento'] < date('Y-m-d')) {
                $nueva_fecha_vencimiento = date('Y-m-d', strtotime('+' . $tipo['duracion_dias'] . ' days'));
            } else {
                // Si está activa, extender desde fecha de vencimiento actual
                $nueva_fecha_vencimiento = date('Y-m-d', strtotime($membresia['fecha_vencimiento'] . ' +' . $tipo['duracion_dias'] . ' days'));
            }
            
            $query_update = "UPDATE " . $this->table . " 
                            SET fecha_vencimiento = :fecha_vencimiento,
                                estado = 'activa'
                            WHERE id_membresia = :id";
            $stmt_update = $this->conn->prepare($query_update);
            $stmt_update->bindParam(':fecha_vencimiento', $nueva_fecha_vencimiento);
            $stmt_update->bindParam(':id', $id_membresia);
            
            if($stmt_update->execute()) {
                // Registrar en historial
                $this->registrarHistorial($membresia['id_cliente'], 'membresia_renovada', 
                    $id_membresia, 'Membresía renovada hasta ' . $nueva_fecha_vencimiento);
                
                return ['success' => true, 'message' => 'Membresía renovada correctamente'];
            }
        }
        return ['success' => false, 'message' => 'Error al renovar membresía'];
    }

    /**
     * REGISTRAR EN HISTORIAL
     */
    private function registrarHistorial($id_cliente, $tipo_accion, $id_referencia, $descripcion) {
        $query = "INSERT INTO historial (id_cliente, tipo_accion, id_referencia, descripcion) 
                  VALUES (:id_cliente, :tipo_accion, :id_referencia, :descripcion)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':tipo_accion', $tipo_accion);
        $stmt->bindParam(':id_referencia', $id_referencia);
        $stmt->bindParam(':descripcion', $descripcion);
        return $stmt->execute();
    }
}
?>