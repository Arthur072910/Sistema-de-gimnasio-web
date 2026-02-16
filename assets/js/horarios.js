document.addEventListener('DOMContentLoaded', () => {
    // este comando hace que el estado este autocarganose cada segundo al cargar la página
    actualizarEstadoGym();
    
    // cambia el estado del gimnasio de abierto a cerrado sin la necesidad de recargar la página.
    setInterval(actualizarEstadoGym, 1000);
});
    // la funcion para autocargar  la página
function actualizarEstadoGym() {
    const ahora = new Date();
    const dia = ahora.getDay(); 
    const hora = ahora.getHours();
    const minutos = ahora.getMinutes();
    
    const minutosActuales = (hora * 60) + minutos;
    const elementoEstado = document.getElementById('estado-gym');

    // Si por alguna razón el HTML no ha cargado el div, salimos de la función para no dar error
    if (!elementoEstado) return; 

    let estaAbierto = false;
    // ... resto de tu lógica igual ...

    // --- CONFIGURACIÓN DE HORARIOS EN MINUTOS ---
    // Lunes a Viernes: 06:00 (360 min) a 22:00 (1320 min)
    const semanaInicio = 6 * 60;
    const semanaFin = 22 * 60;

    // Sábados: 08:00 a 4:00 pm
    const sabadoInicio = 8 * 60;
    const sabadoFin = 16 * 60;

    // --- validar dias abiertos y cerrados ---
    if (dia >= 1 && dia <= 5) {
        // Entre Lunes y Viernes
        if (minutosActuales >= semanaInicio && minutosActuales < semanaFin) {
            estaAbierto = true;
        }
    } else if (dia === 6) {
        // Sábado
        if (minutosActuales >= sabadoInicio && minutosActuales < sabadoFin) {
            estaAbierto = true;
        }
    }
    // El domingo (dia === 0) permanece como false

    // --- mensajes arriba del horario ---
    if (estaAbierto) {
        elementoEstado.innerHTML = '<i class="fas fa-door-open mr-2"></i> ¡ESTAMOS ABIERTOS! ENTRENAMOS HASTA LAS 10:00 PM';
        elementoEstado.className = "alert alert-success shadow-sm p-4 mb-4 font-weight-bold h4";
    } else {
        let mensajeCerrado = "LO SENTIMOS, ESTAMOS CERRADOS";
        if (dia === 0) {
            mensajeCerrado = '<i class="fas fa-moon mr-2"></i> HOY DOMINGO ESTAMOS CERRADOS - TE ESPERAMOS MAÑANA A LAS 4:00 AM';
        } else {
            mensajeCerrado = '<i class="fas fa-moon mr-2"></i> CERRADO POR AHORA - REVISA NUESTROS HORARIOS ABAJO';
        }
        elementoEstado.innerHTML = mensajeCerrado;
        elementoEstado.className = "alert alert-danger shadow-sm p-4 mb-4 font-weight-bold h4";
    }
}