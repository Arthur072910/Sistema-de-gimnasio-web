document.addEventListener('DOMContentLoaded', function() {
    const formContacto = document.getElementById('formContacto');

    if (formContacto) {
        formContacto.addEventListener('submit', function(e) {
            e.preventDefault();

            // Referencia al botón para deshabilitarlo temporalmente
            const btnPublicar = this.querySelector('.btn-submit');
            btnPublicar.disabled = true;

            // Alerta de carga estilo Delux Gym
            Swal.fire({
                title: 'Enviando mensaje...',
                text: 'Estamos conectando con el servidor de Delux Gym',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Mensaje Enviado!',
                        text: data.message,
                        confirmButtonColor: '#ffd700', // Dorado
                        background: '#1a1a1a', // Fondo oscuro
                        color: '#fff'
                    });
                    formContacto.reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo procesar el envío', 'error');
            })
            .finally(() => {
                // Reactivamos el botón al terminar
                btnPublicar.disabled = false;
            });
        });
    }
});