<?php
// Require composer autoload
require_once __DIR__ . '../../../vendor/autoload.php';

// Crear la instancia de mPDF
$mpdf = new \Mpdf\Mpdf();

// Definimos la estructura HTML en una variable
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Previsualización de Reporte</title>
</head>
<body>
    <div style="width: 100%; border-bottom: 2px solid #333; padding-bottom: 10px;">
    <img src="../../assets/img/delux gym.png" alt="Logo Gym" width="60" style="vertical-align: middle;">
    <span style="font-size: 24px; font-weight: bold; margin-left: 20px; vertical-align: middle;">
        REPORTE DE ASISTENCIAS - DELUX GYM
    </span>
</div>

    <table class="info-adicional">
        <tr>
            <td class="fecha-emision">Fecha de Reporte: 12 de Febrero, 2026</td>
        </tr>
    </table>
    <br>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Cliente</th>
                <th>Clase / Actividad</th>
                <th>Hora Ingreso</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>001</td>
                <td>Carlos Rodríguez</td>
                <td>Crossfit - Nivel Avanzado</td>
                <td>07:05 AM</td>
                <td><span class="status presente">Presente</span></td>
            </tr>
            <tr>
                <td>002</td>
                <td>Ana María Silva</td>
                <td>Yoga Matutino</td>
                <td>08:12 AM</td>
                <td><span class="status presente">Presente</span></td>
            </tr>
            <tr>
                <td>003</td>
                <td>Roberto Gómez</td>
                <td>Zumba Kids</td>
                <td>-- : --</td>
                <td><span class="status ausente">Ausente</span></td>
            </tr>
            <tr>
                <td>004</td>
                <td>Lucía Fernández</td>
                <td>Spinning Intensivo</td>
                <td>06:55 AM</td>
                <td><span class="status presente">Presente</span></td>
            </tr>
            <tr>
                <td>005</td>
                <td>Marcos Peña</td>
                <td>Entrenamiento Funcional</td>
                <td>-- : --</td>
                <td><span class="status ausente">Ausente</span></td>
            </tr>
        </tbody>
    </table>
<br>
    <div class="footer">
        <p>Reporte de asistenci9as febrero 2026x.<br>
        © 2026 Delux Gym - Todos los derechos reservados.</p>
    </div>

</body>
</html>
';

// Escribir el HTML al PDF
$mpdf->WriteHTML($html);

// Salida al navegador
$mpdf->Output('reporte_memes.pdf', 'I');