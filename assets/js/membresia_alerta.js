fetch('/Sistema-de-gimnasio-web/view/correos/membresia_alerta.php')
    .then(response => response.json())
    .then(data => {
        console.log("Sistema Alerta:", data.message);
    })
    .catch(error => console.error("Error en el sistema de alertas:", error));