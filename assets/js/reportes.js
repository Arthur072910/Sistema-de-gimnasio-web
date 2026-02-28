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