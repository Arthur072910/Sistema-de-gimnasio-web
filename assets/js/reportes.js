document.getElementById('tipo_reporte').addEventListener('change', function() {
    const tipo = this.value;
    const tituloTabla = document.querySelector('.section-header .chart-title');
    const thead = document.querySelector('.classes-table thead tr');
    
    switch(tipo) {
        case 'pagos':
            tituloTabla.textContent = 'Reporte de Pagos';
            thead.innerHTML = `
                <th>ID</th>
                <th>Cliente</th>
                <th>Monto</th>
                <th>Método</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Fecha</th>
            `;
            break;
        case 'miembros':
            tituloTabla.textContent = 'Reporte de Miembros';
            thead.innerHTML = `
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Membresía</th>
                <th>Vencimiento</th>
                <th>Estado</th>
                <th>Teléfono</th>
            `;
            break;
        case 'ingresos':
            tituloTabla.textContent = 'Reporte de Ingresos';
            thead.innerHTML = `
                <th>Fecha</th>
                <th>Transacciones</th>
                <th>Total</th>
                <th>Membresías</th>
                <th>Productos</th>
            `;
            break;
        case 'entrenadores':
            tituloTabla.textContent = 'Reporte de Entrenadores';
            thead.innerHTML = `
                <th>ID</th>
                <th>Nombre</th>
                <th>Especialidad</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Fecha Registro</th>
                <th>Estado</th>
            `;
            break;
        case 'clases':
            tituloTabla.textContent = 'Reporte de Clases';
            thead.innerHTML = `
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cupo</th>
                <th>Inscritos</th>
                <th>Entrenador</th>
                <th>Estado</th>
                <th>Fecha Creación</th>
            `;
            break;
        case 'productos':
            tituloTabla.textContent = 'Reporte de Productos';
            thead.innerHTML = `
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Descripción</th>
                <th>Estado</th>
            `;
            break;
        case 'planes':
            tituloTabla.textContent = 'Reporte de Planes';
            thead.innerHTML = `
                <th>ID</th>
                <th>Plan</th>
                <th>Duración</th>
                <th>Precio</th>
                <th>Miembros Activos</th>
                <th>Descripción</th>
                <th>Estado</th>
            `;
            break;
    }
    
    // Cargar datos en la tabla
    cargarDatosTabla();
});

// Función para cargar datos en la tabla (vista previa)
function cargarDatosTabla() {
    const desde = document.getElementById('desde').value;
    const hasta = document.getElementById('hasta').value;
    const tipo = document.getElementById('tipo_reporte').value;
    
    if (!desde || !hasta) return;
    
    // Mostrar loading
    document.querySelector('.classes-table tbody').innerHTML = `
        <tr>
            <td colspan="10" class="text-center">
                <i class="fas fa-spinner fa-spin"></i> Cargando...
            </td>
        </tr>
    `;
    
    fetch(`reportes/obtener_datos_tabla.php?desde=${desde}&hasta=${hasta}&tipo_reporte=${tipo}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('.classes-table tbody');
            
            if (data.length === 0) {
                // Obtener el número de columnas según el tipo de reporte
                let columnCount = 7; // default
                switch(tipo) {
                    case 'clases': columnCount = 8; break;
                    case 'pagos': columnCount = 7; break;
                    case 'miembros': columnCount = 7; break;
                    case 'ingresos': columnCount = 5; break;
                    case 'entrenadores': columnCount = 7; break;
                    case 'productos': columnCount = 7; break;
                    case 'planes': columnCount = 7; break;
                }
                
                tbody.innerHTML = `<tr><td colspan="${columnCount}" class="text-center" style="color: var(--text-secondary);">No hay registros para el período seleccionado</td></tr>`;
                return;
            }
            
            let html = '';
            data.forEach(row => {
                html += '<tr>';
                // Iterar en el orden correcto según las columnas definidas
                for (let key in row) {
                    let valor = row[key];
                    // Formatear montos si es necesario
                    if (typeof valor === 'number' || (!isNaN(parseFloat(valor)) && valor.toString().includes('$'))) {
                        html += `<td>${valor}</td>`;
                    } else {
                        html += `<td>${valor}</td>`;
                    }
                }
                html += '</tr>';
            });
            tbody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('.classes-table tbody').innerHTML = `
                <tr>
                    <td colspan="10" class="text-center text-danger">
                        Error al cargar los datos
                    </td>
                </tr>
            `;
        });
}

// Función para generar PDF
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

// Cargar datos iniciales
document.getElementById('desde').addEventListener('change', cargarDatosTabla);
document.getElementById('hasta').addEventListener('change', cargarDatosTabla);

// Cargar datos al iniciar la página
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