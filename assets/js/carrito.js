// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    actualizarContador();
    configurarBotones();
    
    // Si estamos en la página del carrito, renderizarlo
    if (window.location.pathname.includes('carrito.php')) {
        renderizarCarrito();
    }
});

function configurarBotones() {
    // Seleccionamos todos los botones que dicen "COMPRAR"
    const botones = document.querySelectorAll('.btn-comprar');
    
    botones.forEach(boton => {
        boton.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Obtener datos del producto desde los atributos data-
            const producto = {
                id: boton.dataset.id,
                titulo: boton.dataset.nombre,
                precio: parseFloat(boton.dataset.precio),
                imagen: obtenerImagenProducto(boton),
                cantidad: 1
            };
            
            agregarAlCarrito(producto);
        });
    });
}

function obtenerImagenProducto(boton) {
    // Buscar la imagen dentro de la misma card
    const card = boton.closest('.card');
    const img = card.querySelector('img');
    return img ? img.src : '../assets/img/default-product.jpg';
}

function agregarAlCarrito(producto) {
    let carrito = JSON.parse(localStorage.getItem('gym_cart')) || [];
    
    // Verificar si ya existe por ID
    const existe = carrito.find(p => p.id === producto.id);
    if (existe) {
        existe.cantidad++;
    } else {
        carrito.push(producto);
    }
    
    localStorage.setItem('gym_cart', JSON.stringify(carrito));
    actualizarContador();
    
    // Animar el badge cuando se agrega un producto
    animarBadge();
}

function actualizarContador() {
    const contador = document.getElementById('cart-count');
    if (contador) {
        let carrito = JSON.parse(localStorage.getItem('gym_cart')) || [];
        const total = carrito.reduce((acc, p) => acc + p.cantidad, 0);
        contador.innerText = total;
        
        // Ocultar si es cero
        if (total === 0) {
            contador.style.display = 'none';
        } else {
            contador.style.display = 'inline-flex';
        }
    }
}

function animarBadge() {
    const badge = document.getElementById('cart-count');
    if (badge && badge.style.display !== 'none') {
        badge.style.transform = 'scale(1.3)';
        setTimeout(() => {
            badge.style.transform = 'scale(1)';
        }, 200);
    }
}

function renderizarCarrito() {
    const lista = document.getElementById('lista-carrito');
    if (!lista) return;
    
    let carrito = JSON.parse(localStorage.getItem('gym_cart')) || [];
    let totalFinal = 0;
    
    lista.innerHTML = '';

    if (carrito.length === 0) {
        lista.innerHTML = '<tr><td colspan="5" class="text-center py-4"><i class="fas fa-shopping-cart fa-3x mb-3" style="color: #333;"></i><br>El carrito está vacío</td></tr>';
        document.getElementById('gran-total').innerText = '$0.00';
    } else {
        carrito.forEach((p, index) => {
            const subtotal = p.precio * p.cantidad;
            totalFinal += subtotal;

            lista.innerHTML += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${p.imagen}" width="50" height="50" style="object-fit: cover; border-radius: 8px; margin-right: 10px;">
                            ${p.titulo}
                        </div>
                    </td>
                    <td>$${p.precio.toFixed(2)}</td>
                    <td>
                        <input type="number" class="form-control cantidad-input" value="${p.cantidad}" min="1" data-index="${index}" style="width: 80px;" onkeydown="return false;">
                    </td>
                    <td>$${subtotal.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-outline-danger btn-sm" onclick="eliminarProducto(${index})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        document.getElementById('gran-total').innerText = `$${totalFinal.toFixed(2)}`;
        
        // Agregar eventos a los inputs de cantidad
        document.querySelectorAll('.cantidad-input').forEach(input => {
            input.addEventListener('change', actualizarCantidad);
        });
    }
}

function eliminarProducto(index) {
    let carrito = JSON.parse(localStorage.getItem('gym_cart')) || [];
    carrito.splice(index, 1);
    localStorage.setItem('gym_cart', JSON.stringify(carrito));
    renderizarCarrito();
    actualizarContador();
}

function actualizarCantidad(e) {
    const index = e.target.dataset.index;
    const nuevaCantidad = parseInt(e.target.value);
    
    if (nuevaCantidad < 1) {
        e.target.value = 1;
        return;
    }
    
    let carrito = JSON.parse(localStorage.getItem('gym_cart')) || [];
    carrito[index].cantidad = nuevaCantidad;
    localStorage.setItem('gym_cart', JSON.stringify(carrito));
    renderizarCarrito();
    actualizarContador();
}

// Función para vaciar carrito (opcional)
function vaciarCarrito() {
    if (confirm('¿Estás seguro de vaciar el carrito?')) {
        localStorage.removeItem('gym_cart');
        actualizarContador();
        if (window.location.pathname.includes('carrito.php')) {
            renderizarCarrito();
        }
    }
}