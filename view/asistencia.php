<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Asistencia</title>
    <link rel="stylesheet" href="../assets/css/asistencia.css">
</head>
<body>

    <div class="contenedor">

      
        <div class="logo">
            <img src="../assets/img/logo_deluxGym.png" alt="Delux Gym">
        </div>

        <div class="titulo">Control de Asistencia</div>

       
        <div class="reloj" id="reloj">00:00:00</div>
        <div class="fecha" id="fecha"></div>

        <div class="hint">Escanea tu código para ingresar</div>

    </div>

   
    <div class="overlay" id="overlay">
        <div class="modal" id="modal">
            <div class="modal-icono" id="icono"></div>
            <div class="modal-estado" id="estado"></div>
            <div class="modal-nombre" id="nombre"></div>
            <div class="modal-codigo" id="codigoTexto"></div>
        </div>
    </div>

    <script src="../assets/js/asistencia.js"></script>

</body>
</html>