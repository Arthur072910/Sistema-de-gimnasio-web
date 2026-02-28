<?php
session_start();

if(!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: Perfil.php");
    exit();
}

$cliente_id = $_SESSION['cliente_id'];
$id_clase = $_POST['id_clase'] ?? null;

if(!$id_clase){
    header("Location: Perfil.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

try {

    $stmtClase = $conn->prepare("
        SELECT id_clase, nombre 
        FROM clases 
        WHERE id_clase = ? AND estado = 'activa'
    ");
    $stmtClase->execute([$id_clase]);
    $claseInfo = $stmtClase->fetch(PDO::FETCH_ASSOC);

    if(!$claseInfo){
        header("Location: Perfil.php?error=clase_no_existe");
        exit();
    }

    $nombreClase = $claseInfo['nombre'];

    
    $stmtPlan = $conn->prepare("
        SELECT tm.nombre 
        FROM membresias m
        JOIN tipo_membresia tm ON m.id_tipo_membresia = tm.id_tipo_membresia
        WHERE m.id_cliente = ? AND m.estado = 'activa'
        LIMIT 1
    ");
    $stmtPlan->execute([$cliente_id]);
    $plan = $stmtPlan->fetch(PDO::FETCH_ASSOC);

    if(!$plan){
        header("Location: Perfil.php?error=sin_membresia");
        exit();
    }

    $tipoPlanNormalizado = mb_strtolower($plan['nombre'], 'UTF-8');
    $tipoPlanNormalizado = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $tipoPlanNormalizado);
    $tipoPlanNormalizado = trim($tipoPlanNormalizado);

  
    if($tipoPlanNormalizado === 'basico'){
        header("Location: Perfil.php?error=plan_basico");
        exit();
    }

   
    $stmtCount = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM inscripciones_clases 
        WHERE id_cliente = ?
        AND estado = 'activa'
    ");
    $stmtCount->execute([$cliente_id]);
    $totalClases = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

   
    if($tipoPlanNormalizado === 'intermedio' && $totalClases >= 1){
        header("Location: Perfil.php?error=limite_intermedio");
        exit();
    }

    
    $stmtCheck = $conn->prepare("
        SELECT id_inscripcion 
        FROM inscripciones_clases 
        WHERE id_cliente = ? 
        AND id_clase = ?
        AND estado = 'activa'
    ");
    $stmtCheck->execute([$cliente_id, $id_clase]);

    if($stmtCheck->rowCount() > 0){
        header("Location: Perfil.php?error=ya_inscrito");
        exit();
    }

    
    $stmtInsert = $conn->prepare("
        INSERT INTO inscripciones_clases 
        (id_cliente, id_clase, fecha_inscripcion, estado)
        VALUES (?, ?, NOW(), 'activa')
    ");
    $stmtInsert->execute([$cliente_id, $id_clase]);

    
    $tipoAccion = "inscripcion_clase";
    $descripcion = "Inscripción exitosa a la clase de: " . $nombreClase;
    
    $stmtHistorial = $conn->prepare("
        INSERT INTO historial (id_cliente, tipo_accion, descripcion, fecha_accion) 
        VALUES (?, ?, ?, NOW())
    ");
    $stmtHistorial->execute([$cliente_id, $tipoAccion, $descripcion]);

    header("Location: Perfil.php?success=inscrito");
    exit();

} catch(PDOException $e){
    header("Location: Perfil.php?error=bd_error");
    exit();
}