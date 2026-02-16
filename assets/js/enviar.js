document.getElementById('formPedido').addEventListener('submit', function(e) {
    e.preventDefault(); // Evita que la página se recargue

    const btn = document.getElementById('btnConfirmar');
    const mensaje = document.getElementById('mensajeEstado');
    const metodoPago = document.getElementById('metodo').value;

    btn.disabled = true;
    btn.innerText = 'Enviando...';

    // Creamos los datos para enviar
    const datos = new FormData();
    datos.append('metodo', metodoPago);

    // Enviamos al archivo PHP
    fetch('correos/procesar_pedido.php', {
    method: 'POST',
    body: datos
    })
    .then(async res => {
        const text = await res.text();
        console.log("Respuesta del servidor:", text);
        return JSON.parse(text);
    })
    .then(data => {
        if(data.status === 'success') {
            mensaje.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        } else {
            mensaje.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
    })
    .catch(error => {
        alert('Error técnico: ' + error.message);
        mensaje.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerText = 'Confirmar Pedido';
    });
});