<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../controller/HorarioController.php';

$controller = new HorarioController();
$mensaje = '';
$tipo_mensaje = '';

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'agregar') {
        $resultado = $controller->agregar($_POST);
    } elseif ($_POST['accion'] == 'actualizar') {
        $resultado = $controller->actualizar($_POST);
    }
    $mensaje = $resultado['message'];
    $tipo_mensaje = $resultado['success'] ? 'success' : 'error';
}

if (isset($_GET['eliminar'])) {
    $resultado = $controller->eliminar($_GET['eliminar']);
    $mensaje = $resultado['message'];
    $tipo_mensaje = $resultado['success'] ? 'success' : 'error';
}

$horarios = $controller->listar();
$clases_disponibles = $controller->listarClases();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Horarios | DeluxGym</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar-global.css">
    <link rel="stylesheet" href="../assets/css/dashboard-global.css">
    <link rel="stylesheet" href="../assets/css/clasess.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Forzamos que el contenido use todo el ancho disponible */
        body, html {
            overflow-x: hidden;
            width: 100%;
        }
        .main-content {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
        .classes-section {
            width: 100% !important;
            margin: 0 !important;
        }
        .classes-table {
            width: 100% !important;
        }
        /* Ajuste para que el formulario no se vea pequeño */
        .form-row {
            width: 100%;
            margin: 0;
        }
    </style>
</head>
<body>
    <div style="display: flex; width: 100%;">
        <?php include dirname(__DIR__) . '/layout/siderbar.php'; ?>

        <div class="main-content">
            <?php 
                $dashboard_path = realpath(__DIR__ . '/../layout/dashboard.php');
                if ($dashboard_path) include $dashboard_path;
            ?>
            
            <div class="header d-flex justify-content-between align-items-center mb-4">
                <h1>Gestión de Horarios</h1>
                <div class="admin-profile">
                    <i class="fas fa-bell mr-3"></i>
                    <div class="admin-avatar">AD</div>
                </div>
            </div>

            <?php if ($mensaje): ?>
                <div class="alert alert-<?= $tipo_mensaje == 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($mensaje) ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <div class="classes-section">
                <div class="section-header">
                    <h3 class="chart-title">Configuración de Horarios Semanales</h3>
                </div>

                <div class="card mb-4" style="background-color: #1a1a1a; border: 1px solid #333;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: var(--accent-gold);">Asignar Nuevo Horario de Clase</h5>
                        <form method="POST">
                            <input type="hidden" name="accion" value="agregar">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Clase Disponible</label>
                                    <select name="id_clase" class="form-control" required>
                                        <option value="">Seleccione una clase...</option>
                                        <?php foreach ($clases_disponibles as $clase): ?>
                                            <option value="<?= $clase['id_clase'] ?>"><?= htmlspecialchars($clase['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Día</label>
                                    <select name="dia_semana" class="form-control" required>
                                        <option value="Lunes">Lunes</option>
                                        <option value="Martes">Martes</option>
                                        <option value="Miércoles">Miércoles</option>
                                        <option value="Jueves">Jueves</option>
                                        <option value="Viernes">Viernes</option>
                                        <option value="Sábado">Sábado</option>
                                        <option value="Domingo">Domingo</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Hora de Inicio</label>
                                    <input type="time" name="hora_inicio" class="form-control" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Hora de Fin</label>
                                    <input type="time" name="hora_fin" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn-add">
                                <i class="fas fa-save mr-2"></i>GUARDAR HORARIO
                            </button>
                        </form>
                    </div>
                </div>

                <table class="classes-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>CLASE</th>
                            <th>DÍA</th>
                            <th>HORARIO (INICIO - FIN)</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($horarios)): ?>
                            <?php foreach ($horarios as $h): ?>
                                <tr>
                                    <td><?= $h['id_horario'] ?></td>
                                    <td><strong style="color: #fff;"><?= htmlspecialchars($h['nombre_clase']) ?></strong></td>
                                    <td><?= htmlspecialchars($h['dia_semana']) ?></td>
                                    <td>
                                        <span class="badge-specialty">
                                            <?= date('H:i', strtotime($h['hora_inicio'])) ?> - <?= date('H:i', strtotime($h['hora_fin'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn-edit" style="background:none; border:none; color:var(--accent-gold);" 
                                            data-toggle="modal" data-target="#modalEditar"
                                            onclick="cargarDatosHorario('<?= $h['id_horario'] ?>', '<?= $h['id_clase'] ?>', '<?= $h['dia_semana'] ?>', '<?= $h['hora_inicio'] ?>', '<?= $h['hora_fin'] ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?eliminar=<?= $h['id_horario'] ?>" class="btn-delete" style="color:#ff4444; margin-left:15px;" 
                                           onclick="return confirm('¿Eliminar este horario?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-4">No hay horarios programados</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>