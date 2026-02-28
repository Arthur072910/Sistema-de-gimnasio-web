document.getElementById('tipo_reporte').addEventListener('change', function() {
    const tipo = this.value;
    const tituloTabla = document.querySelector('.section-header .chart-title');
    const thead = document.querySelector('.classes-table thead tr');
    
    switch(tipo) {
        case 'pagos':
            tituloTabla.textContent = 'Tabla de Pagos';
            thead.innerHTML = `
                <th>ID</th>
                <th>Cliente</th>
                <th>Monto</th>
                <th>Método de Pago</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Fecha</th>
            `;
            break;
        case 'miembros':
            tituloTabla.textContent = 'Lista de Miembros';
            thead.innerHTML = `
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Membresía</th>
                <th>Vencimiento</th>
                <th>Estado</th>
                <th>Teléfono</th>
            `;
            break;
        case 'ingresos':
            tituloTabla.textContent = 'Resumen de Ingresos';
            thead.innerHTML = `
                <th>Fecha</th>
                <th>Total Ingresos</th>
                <th>Membresías</th>
                <th>Productos</th>
                <th>Transacciones</th>
            `;
            break;
    }
    
    cargarDatosTabla();
});

function cargarDatosTabla() {
    const desde = document.getElementById('desde').value;
    const hasta = document.getElementById('hasta').value;
    const tipo = document.getElementById('tipo_reporte').value;
    
    if (!desde || !hasta) return;
    
    fetch(`reportes/obtener_datos_tabla.php?desde=${desde}&hasta=${hasta}&tipo_reporte=${tipo}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('.classes-table tbody');
            
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center" style="color: var(--text-secondary);">No hay registros para el período seleccionado</td></tr>`;
                return;
            }
            
            let html = '';
            data.forEach(row => {
                html += '<tr>';
                for (let key in row) {
                    html += `<td>${row[key]}</td>`;
                }
                html += '</tr>';
            });
            tbody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

document.querySelector('.btn-add').addEventListener('click', function(e) {
    e.preventDefault();
    
    const desde = document.getElementById('desde').value;
    const hasta = document.getElementById('hasta').value;
    const tipo = document.getElementById('tipo_reporte').value;
    
    if (!desde || !hasta) {
        alert('Por favor selecciona las fechas de inicio y fin');
        return;
    }
    
    window.open(`reportes/generar_reporte.php?desde=${desde}&hasta=${hasta}&tipo_reporte=${tipo}`, '_blank');
});

document.getElementById('desde').addEventListener('change', cargarDatosTabla);
document.getElementById('hasta').addEventListener('change', cargarDatosTabla);

window.addEventListener('load', function() {
    const hoy = new Date().toISOString().split('T')[0];
    const primerDia = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
    
    if (!document.getElementById('desde').value) {
        document.getElementById('desde').value = primerDia;
    }
    if (!document.getElementById('hasta').value) {
        document.getElementById('hasta').value = hoy;
    }
    
    cargarDatosTabla();
});