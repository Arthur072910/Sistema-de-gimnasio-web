document.addEventListener("DOMContentLoaded", function() {
    fetch('/Sistema-de-gimnasio-web/controller/get_stats.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('stat-miembros').innerText = data.miembros;
            document.getElementById('stat-entrenadores').innerText = data.entrenadores;
            document.getElementById('stat-clases').innerText = data.clases;
            document.getElementById('stat-planes').innerText = data.planes;
            document.getElementById('stat-productos').innerText = data.productos;
            document.getElementById('stat-ingresos').innerText = '$' + data.ingresos;
        })
        .catch(error => console.error('Error al cargar estadísticas:', error));
});