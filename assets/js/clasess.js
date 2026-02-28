function cargarDatos(id, nombre, descripcion, cupo, id_entrenador, estado) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_descripcion').value = descripcion || '';
            document.getElementById('edit_cupo').value = cupo;
            document.getElementById('edit_entrenador').value = id_entrenador;
            document.getElementById('edit_estado').value = estado;
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