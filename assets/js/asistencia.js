
const dias  = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

function pad(n){ return String(n).padStart(2,'0'); }

function actualizarReloj(){
    const now = new Date();
    document.getElementById('reloj').textContent =
        `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
    document.getElementById('fecha').textContent =
        `${dias[now.getDay()]} ${now.getDate()} de ${meses[now.getMonth()]} de ${now.getFullYear()}`;
}

actualizarReloj();
setInterval(actualizarReloj, 1000);


let buffer = '';
let timer  = null;

document.addEventListener('keydown', function(e){
    if (['Shift','Control','Alt','Meta'].includes(e.key)) return;

    if (e.key === 'Enter'){
        if (buffer.length > 0) buscarCodigo(buffer.trim());
        buffer = '';
        clearTimeout(timer);
        return;
    }

    buffer += e.key;
    clearTimeout(timer);
    timer = setTimeout(() => { buffer = ''; }, 500);
});

let closeTimer = null;

function buscarCodigo(codigo){

    console.log("Código enviado:", codigo); 

    fetch("http://localhost/Sistema-de-gimnasio-web/controller/AsistenciaController.php?action=validar_codigo&codigo=" + encodeURIComponent(codigo))
        .then(r => r.json())
        .then(data => {

           if (data.status === "activo") {

    let mensajeTipo = data.tipo === "entrada" 
        ? "Entrada registrada"
        : "Salida registrada";

    mostrarModal(
        'bienvenido',
        '✔',
        'ACCESO PERMITIDO',
        data.nombre,
        mensajeTipo
    );
}else if (data.status === "vencido") {

                mostrarModal(
                    'desconocido',
                    '⚠',
                    'MEMBRESÍA VENCIDA',
                    data.nombre,
                    'Pase a administración'
                );

            } else if (data.status === "no_encontrado") {

                mostrarModal(
                    'desconocido',
                    '✖',
                    'NO REGISTRADO',
                    '',
                    'Código no encontrado'
                );

            } else {

                mostrarModal(
                    'desconocido',
                    '✖',
                    'ERROR',
                    '',
                    'Respuesta inválida'
                );

            }

        })
        .catch(() => {

            mostrarModal(
                'desconocido',
                '✖',
                'ERROR',
                '',
                'Error de conexión'
            );

        });
}

// Modal
function mostrarModal(tipo, icono, estado, nombreTexto, mensaje){

    clearTimeout(closeTimer);

    const modal = document.getElementById('modal');
    modal.className = `modal ${tipo}`;

    document.getElementById('icono').textContent  = icono;
    document.getElementById('estado').textContent = estado;
    document.getElementById('nombre').textContent = nombreTexto;
    document.getElementById('codigoTexto').textContent = mensaje;

   
    document.body.classList.remove('exito', 'error');

    if(tipo === 'bienvenido'){
        document.body.classList.add('exito');
    } else {
        document.body.classList.add('error');
    }

    document.getElementById('overlay').classList.add('show');

    closeTimer = setTimeout(() => {

        document.getElementById('overlay').classList.remove('show');

       
        document.body.classList.remove('exito', 'error');

    }, 3000);
}