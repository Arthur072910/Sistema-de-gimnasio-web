// Inicializar el contador al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    actualizarContador();
    configurarBotones();
});

function configurarBotones() {
    // Seleccionamos todos los botones que dicen "COMPRAR"
    const botones = document.querySelectorAll('.btn-warning');
    
    botones.forEach(boton => {
        boton.addEventListener('click', (e) => {
            e.preventDefault();
            // Buscamos los datos de la tarjeta (Card)
            const card = e.target.closest('.card');
            const producto = {
                titulo: card.querySelector('.card-title').textContent,
                precio: card.querySelector('h5').textContent,
                imagen: card.querySelector('img').src,
                cantidad: 1
            };
            
            agregarAlCarrito(producto);
        });
    });
}

function agregarAlCarrito(producto) {
    let carrito = JSON.parse(localStorage.getItem('gym_cart')) || [];
    
    // Verificar si ya existe para sumar cantidad
    const existe = carrito.find(p => p.titulo === producto.titulo);
    if (existe) {
        existe.cantidad++;
    } else {
        carrito.push(producto);
    }
    
    localStorage.setItem('gym_cart', JSON.stringify(carrito));
    actualizarContador();
}

// assets/js/carrito.js
function actualizarContador() {
    const contador = document.getElementById('cart-count');
    if (contador) { // Verificación de seguridad
        let carrito = JSON.parse(localStorage.getItem('gym_cart')) || [];
        const total = carrito.reduce((acc, p) => acc + p.cantidad, 0);
        contador.innerText = total;
    }
}
// ... resto del código anterior

function renderizarCarrito() {
    const lista = document.getElementById('lista-carrito');
    let carrito = JSON.parse(localStorage.getItem('gym_cart')) || [];
    let totalFinal = 0;
    
    lista.innerHTML = ''; // Limpiar tabla para redibujar

    if (carrito.length === 0) {
        lista.innerHTML = '<tr><td colspan="5" class="text-center">El carrito está vacío</td></tr>';
    } else {
        carrito.forEach((p, index) => {
            const precioLimpio = parseFloat(p.precio.replace('$', ''));
            const subtotal = precioLimpio * p.cantidad;
            totalFinal += subtotal;

            lista.innerHTML += `
                <tr>
                    <td><img src="${p.imagen}" width="50" class="mr-2">${p.titulo}</td>
                    <td>${p.precio}</td>
                    <td>${p.cantidad}</td>
                    <td>$${subtotal.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-outline-danger btn-sm shadow-sm" onclick="eliminarProducto(${index})" title="Eliminar producto">
                            <i class="fa-solid fa-trash-can"></i> Eliminar
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    document.getElementById('gran-total').innerText = `Total: $${totalFinal.toFixed(2)}`;
    
    // Si tienes la función actualizarContador en carrito.js, llámala aquí también
    if (typeof actualizarContador === "function") {
        actualizarContador();
    }
}

// Nueva función para eliminar un producto específico
function eliminarProducto(index) {
    let carrito = JSON.parse(localStorage.getItem('gym_cart')) || [];
    
    // Eliminamos el elemento del array usando su posición
    carrito.splice(index, 1);
    
    // Guardamos el nuevo carrito en localStorage
    localStorage.setItem('gym_cart', JSON.stringify(carrito));
    
    // Volvemos a dibujar la tabla
    renderizarCarrito();
}

// Iniciar al cargar la página
document.addEventListener('DOMContentLoaded', renderizarCarrito);
