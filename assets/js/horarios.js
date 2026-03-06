document.addEventListener('DOMContentLoaded', () => {

    actualizarEstadoGym();
    

    setInterval(actualizarEstadoGym, 1000);
});
   
function actualizarEstadoGym() {
    const ahora = new Date();
    const dia = ahora.getDay(); 
    const hora = ahora.getHours();
    const minutos = ahora.getMinutes();
    
    const minutosActuales = (hora * 60) + minutos;
    const elementoEstado = document.getElementById('estado-gym');

    if (!elementoEstado) return; 

    let estaAbierto = false;
   
    const semanaInicio = 6 * 60;
    const semanaFin = 22 * 60;

  
    const sabadoInicio = 8 * 60;
    const sabadoFin = 16 * 60;

    
    if (dia >= 1 && dia <= 5) {
        
        if (minutosActuales >= semanaInicio && minutosActuales < semanaFin) {
            estaAbierto = true;
        }
    } else if (dia === 6) {
        
        if (minutosActuales >= sabadoInicio && minutosActuales < sabadoFin) {
            estaAbierto = true;
        }
    }
   
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