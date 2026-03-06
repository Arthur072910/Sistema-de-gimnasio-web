<?php
require_once __DIR__ . "/../config/Database.php"; 

if (!isset($db)) {
    $database = new Database();
    $db = $database->getConnection();
}

try {
    $sql_miembros = $db->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
    $sql_entrenadores = $db->query("SELECT COUNT(*) FROM entrenadores")->fetchColumn();
    
    // CONTEO SIN FILTRO DE ESTADO PARA QUE NO SALGA 0
    $sql_clases = $db->query("SELECT COUNT(*) FROM clases")->fetchColumn();
    
    $sql_planes = $db->query("SELECT COUNT(*) FROM tipo_membresia")->fetchColumn();
    $sql_productos = $db->query("SELECT COUNT(*) FROM productos")->fetchColumn();
    
    $sql_ingresos = $db->query("SELECT SUM(monto_total) FROM pagos WHERE estado_pago = 'completado'")->fetchColumn();
    $ingresos_formateados = number_format($sql_ingresos ?? 0, 2);

} catch (PDOException $e) {
    $sql_miembros = $sql_entrenadores = $sql_clases = $sql_planes = $sql_productos = 0;
    $ingresos_formateados = "0.00";
}
?>

<div id="dashboard-header" class="mb-4">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card-mini shadow-sm" style="border-left: 5px solid #ffd700; background: #1a1a1a; color: white;">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-users fa-3x gold-icon mr-4" style="color: #ffd700;"></i>
                    <div>
                        <div class="stat-value-mini" style="font-size: 2.2rem; font-weight: bold;"><?php echo $sql_miembros; ?></div>
                        <div style="color: #ffd700; text-transform: uppercase; font-size: 0.8rem;">Miembros</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card-mini shadow-sm" style="border-left: 5px solid #ffd700; background: #1a1a1a; color: white;">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-user-tie fa-3x gold-icon mr-4" style="color: #ffd700;"></i>
                    <div>
                        <div class="stat-value-mini" style="font-size: 2.2rem; font-weight: bold;"><?php echo $sql_entrenadores; ?></div>
                        <div style="color: #ffd700; text-transform: uppercase; font-size: 0.8rem;">Entrenadores</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card-mini shadow-sm" style="border-left: 5px solid #ffd700; background: #1a1a1a; color: white;">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-calendar-alt fa-3x gold-icon mr-4" style="color: #ffd700;"></i>
                    <div>
                        <div class="stat-value-mini" style="font-size: 2.2rem; font-weight: bold;"><?php echo $sql_clases; ?></div>
                        <div style="color: #ffd700; text-transform: uppercase; font-size: 0.8rem;">Clases</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card stat-card-mini shadow-sm" style="border-left: 5px solid #ffd700; background: #1a1a1a; color: white;">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-id-card fa-3x gold-icon mr-4" style="color: #ffd700;"></i>
                    <div>
                        <div class="stat-value-mini" style="font-size: 2.2rem; font-weight: bold;"><?php echo $sql_planes; ?></div>
                        <div style="color: #ffd700; text-transform: uppercase; font-size: 0.8rem;">Planes</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card-mini shadow-sm" style="border-left: 5px solid #ffd700; background: #1a1a1a; color: white;">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-dumbbell fa-3x gold-icon mr-4" style="color: #ffd700;"></i>
                    <div>
                        <div class="stat-value-mini" style="font-size: 2.2rem; font-weight: bold;"><?php echo $sql_productos; ?></div>
                        <div style="color: #ffd700; text-transform: uppercase; font-size: 0.8rem;">Productos</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card-mini shadow-sm" style="border-left: 5px solid #ffd700; background: #ffd700; color: #000;">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-dollar-sign fa-3x mr-4" style="color: #ffd700;"></i>
                    <div>
                        <div class="stat-value-mini" style="font-size: 2.2rem; font-weight: bold;">$<?php echo $ingresos_formateados; ?></div>
                        <div style="color: #ffd700; text-transform: uppercase; font-size: 0.8rem; font-weight: bold;">Ingresos Totales</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<hr style="border: none; height: 2px; background-color: #ffd700; margin: 30px 0; opacity: 1;">