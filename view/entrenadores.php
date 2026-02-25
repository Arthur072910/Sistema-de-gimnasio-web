<?php
require_once __DIR__ . '/../controller/EntrenadorController.php';
$controller = new EntrenadorController();

// ... (Manten tu lógica de POST/GET igual) ...
$entrenadores = $controller->listar();
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Gestión de Entrenadores | DeluxGym</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/sidebar-global.css">
    <link rel="stylesheet" href="../assets/css/dashboard-global.css">
    <link rel="stylesheet" href="../assets/css/entrenadores.css">
</head>

<body>

    <?php include '../layout/siderbar.php'; ?>

    <main class="main-content">
        <?php include __DIR__ . '/../layout/dashboard.php'; ?>
        <header class="header-section mb-4">
            <h1 class="title-gym">Gestión de Entrenadores</h1>
            <p class="subtitle-gym">Administra el personal técnico de DeluxGym</p>
        </header>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <section class="card-gym mb-4">
                        <div class="card-body">
                            <h4 class="card-title-gym"><i class="fas fa-user-plus"></i> Nuevo Entrenador</h4>
                            <form method="POST" action="">
                                <input type="hidden" name="accion" value="agregar">
                                <div class="form-group">
                                    <label>Nombre completo</label>
                                    <input type="text" name="nombre" class="form-control-gym" placeholder="Ej. Juan Pérez" required>
                                </div>
                                <div class="form-group">
                                    <label>Especialidad</label>
                                    <input type="text" name="especialidad" class="form-control-gym" placeholder="Ej. CrossFit" required>
                                </div>
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" name="telefono" class="form-control-gym" placeholder="7000-0000">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control-gym" required>
                                </div>
                                <div class="form-group">
                                    <label>Fecha de Registro</label>
                                    <input type="date" name="fecha_registro" class="form-control-gym" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <button type="submit" class="btn-gold-gym btn-block">Registrar Entrenador</button>
                            </form>
                        </div>
                    </section>
                </div>

                <div class="col-lg-8">
                    <section class="card-gym">
                        <div class="card-body">
                            <h4 class="card-title-gym"><i class="fas fa-list"></i> Personal Activo</h4>
                            <div class="table-responsive">
                                <table class="table table-gym">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Especialidad</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($entrenadores as $e): ?>
                                        <tr>
                                            <td><?= $e['id_entrenador']; ?></td>
                                            <td><strong><?= htmlspecialchars($e['nombre']); ?></strong><br><small><?= htmlspecialchars($e['email']); ?></small></td>
                                            <td><span class="badge-specialty"><?= htmlspecialchars($e['especialidad']); ?></span></td>
                                            <td>
                                                <button class="btn-action edit" onclick="cargarDatos('<?= $e['id_entrenador']; ?>', '<?= addslashes($e['nombre']); ?>', '<?= addslashes($e['especialidad']); ?>', '<?= $e['telefono']; ?>', '<?= $e['email']; ?>', '<?= $e['fecha_registro']; ?>')" data-toggle="modal" data-target="#modalEditar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="?eliminar=<?= $e['id_entrenador']; ?>" class="btn-action delete" onclick="return confirm('¿Eliminar entrenador?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>

    </body>
</html>