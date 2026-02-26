<?php
class MiembroModel {
    private $conn;
    private $table_usuarios = "usuarios";
    private $table_clientes = "clientes";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los miembros (clientes + admins/recepcionistas)
    public function obtenerTodos() {
        $query = "SELECT 
                    u.id_usuario,
                    u.email,
                    u.rol,
                    u.estado,
                    u.fecha_creacion,
                    u.google_id,
                    c.id_cliente,
                    c.nombre,
                    c.apellido,
                    c.telefono,
                    c.fecha_nacimiento,
                    c.genero,
                    c.fecha_registro
                  FROM " . $this->table_usuarios . " u
                  LEFT JOIN " . $this->table_clientes . " c ON u.id_usuario = c.id_usuario
                  WHERE u.estado = 'activo'
                  ORDER BY 
                    CASE 
                        WHEN u.rol = 'administrador' THEN 1
                        WHEN u.rol = 'recepcionista' THEN 2
                        ELSE 3
                    END, 
                    COALESCE(c.nombre, u.email) ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un miembro por ID
    public function obtenerPorId($id_usuario) {
        $query = "SELECT 
                    u.id_usuario,
                    u.email,
                    u.rol,
                    u.estado,
                    u.fecha_creacion,
                    u.google_id,
                    c.id_cliente,
                    c.nombre,
                    c.apellido,
                    c.telefono,
                    c.fecha_nacimiento,
                    c.genero,
                    c.fecha_registro
                  FROM " . $this->table_usuarios . " u
                  LEFT JOIN " . $this->table_clientes . " c ON u.id_usuario = c.id_usuario
                  WHERE u.id_usuario = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Si es admin o usuario sin cliente, establecer valores por defecto
        if ($resultado && !$resultado['id_cliente']) {
            $resultado['nombre'] = $this->extractNameFromEmail($resultado['email']);
            $resultado['apellido'] = '';
            $resultado['telefono'] = null;
            $resultado['fecha_nacimiento'] = null;
            $resultado['genero'] = null;
        }
        
        return $resultado;
    }

    // Extraer nombre del email (para admins)
    private function extractNameFromEmail($email) {
        $parte = explode('@', $email)[0];
        // Reemplazar puntos y guiones bajos por espacios
        $nombre = str_replace(['.', '_', '-'], ' ', $parte);
        // Capitalizar
        return ucwords($nombre);
    }

    // Actualizar datos de usuario
    public function actualizarUsuario($id_usuario, $email, $rol, $estado) {
        $query = "UPDATE " . $this->table_usuarios . " 
                  SET email = :email, rol = :rol, estado = :estado 
                  WHERE id_usuario = :id_usuario";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Actualizar datos de cliente (SOLO si existe el cliente)
    public function actualizarCliente($id_cliente, $nombre, $apellido, $telefono, $fecha_nacimiento, $genero) {
        if (!$id_cliente) {
            // Si no es cliente, no se actualiza nada y se considera exitoso
            return true;
        }
        
        // Verificar si el cliente existe
        $query_check = "SELECT id_cliente FROM " . $this->table_clientes . " WHERE id_cliente = :id_cliente";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        $stmt_check->execute();
        
        if ($stmt_check->rowCount() == 0) {
            // El cliente no existe, no se puede actualizar
            return false;
        }
        
        $query = "UPDATE " . $this->table_clientes . " 
                  SET nombre = :nombre, apellido = :apellido, 
                      telefono = :telefono, fecha_nacimiento = :fecha_nacimiento, 
                      genero = :genero 
                  WHERE id_cliente = :id_cliente";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento, PDO::PARAM_STR);
        $stmt->bindParam(':genero', $genero, PDO::PARAM_STR);
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Cambiar contraseña
    public function cambiarContraseña($id_usuario, $nueva_contraseña) {
        $hash = password_hash($nueva_contraseña, PASSWORD_BCRYPT);
        
        $query = "UPDATE " . $this->table_usuarios . " 
                  SET contraseña = :contraseña 
                  WHERE id_usuario = :id_usuario";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':contraseña', $hash, PDO::PARAM_STR);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // Eliminar miembro (borrado lógico)
    public function eliminar($id_usuario) {
        $query = "UPDATE " . $this->table_usuarios . " SET estado = 'inactivo' WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Obtener estadísticas
    public function obtenerEstadisticas() {
        $stats = [];
        
        // Total de miembros
        $query = "SELECT COUNT(*) as total FROM " . $this->table_usuarios . " WHERE estado = 'activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Por rol
        $query = "SELECT rol, COUNT(*) as cantidad FROM " . $this->table_usuarios . " WHERE estado = 'activo' GROUP BY rol";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['por_rol'] = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stats['por_rol'][$row['rol']] = $row['cantidad'];
        }
        
        // Nuevos este mes
        $query = "SELECT COUNT(*) as nuevos FROM " . $this->table_usuarios . " 
                  WHERE estado = 'activo' AND MONTH(fecha_creacion) = MONTH(CURDATE()) 
                  AND YEAR(fecha_creacion) = YEAR(CURDATE())";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['nuevos_mes'] = $stmt->fetch(PDO::FETCH_ASSOC)['nuevos'];
        
        return $stats;
    }
}
?>