function cargarDatos(id, nombre, especialidad, telefono, email, fecha) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nombre').value = nombre;
        document.getElementById('edit_especialidad').value = especialidad;
        document.getElementById('edit_telefono').value = telefono || '';
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_fecha').value = fecha;
    }
    
    // Auto-ocultar alertas después de 5 segundos
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.style.display = 'none', 500);
        });
    }, 5000);