// pago.js - Manejo de la página de pago (VERSIÓN MEJORADA)

document.addEventListener('DOMContentLoaded', function() {
    cargarResumenPedido();
});

function cargarResumenPedido() {
    const tieneMembresia = document.getElementById('tiene-membresia')?.value === '1';
    
    if (tieneMembresia) {
        return;
    }
    
    const carrito = sessionStorage.getItem('pago_carrito');
    const total = sessionStorage.getItem('pago_total');
    
    if (carrito && total && carrito !== '[]' && carrito !== 'null' && total !== '0.00') {
        try {
            const items = JSON.parse(carrito);
            mostrarProductos(items, total);
        } catch(e) {
            mostrarVacio();
        }
    } else {
        mostrarVacio();
    }
}

function mostrarVacio() {
    const resumenContainer = document.getElementById('resumen-container');
    const metodosContainer = document.getElementById('metodos-container');
    const totalFooter = document.getElementById('total-footer');
    
    if (resumenContainer) {
        resumenContainer.innerHTML = 
            '<p class="text-center text-muted">No hay productos para pagar</p>' +
            '<a href="productos.php" class="btn-gold-gym w-100 mt-3">IR A TIENDA</a>';
    }
    
    if (metodosContainer) {
        metodosContainer.innerHTML = 
            '<p class="text-center text-muted">Selecciona productos primero</p>';
    }
    
    if (totalFooter) {
        totalFooter.style.display = 'none';
    }
}

function mostrarProductos(items, total) {
    document.getElementById('tipo_transaccion').value = 'producto';
    document.getElementById('monto_total').value = total;
    document.getElementById('items').value = JSON.stringify(items);
    
    const metodosContainer = document.getElementById('metodos-container');
    if (metodosContainer) {
        metodosContainer.innerHTML = `
            <div class="row">
                <div class="col-12">
                    <div class="metodo-item active" onclick="cambiarMetodo('tarjeta')">
                        <i class="fas fa-credit-card"></i>
                        <span>Tarjeta</span>
                    </div>
                </div>
            </div>
        `;
    }
    
    let itemsHtml = '';
    items.forEach(item => {
        itemsHtml += `
            <div class="d-flex justify-content-between small mb-2">
                <span>${item.cantidad}x ${item.titulo}</span>
                <span class="text-warning">$${(item.precio * item.cantidad).toFixed(2)}</span>
            </div>
        `;
    });
    
    const resumenContainer = document.getElementById('resumen-container');
    if (resumenContainer) {
        resumenContainer.innerHTML = `
            <div class="resumen-item">
                <span class="text-muted">Tipo:</span>
                <strong class="text-warning">Productos</strong>
            </div>
            <hr>
            ${itemsHtml}
        `;
    }
    
    // Mostrar total en el footer (MEJORA)
    const totalFooter = document.getElementById('total-footer');
    const totalUnico = document.getElementById('total-unico');
    if (totalFooter && totalUnico) {
        totalUnico.innerText = `$${total}`;
        totalFooter.style.display = 'block';
    }
}

function mostrarMembresia(nombrePlan, precio) {
    document.getElementById('tipo_transaccion').value = 'membresia';
    document.getElementById('monto_total').value = precio;
    
    const metodosContainer = document.getElementById('metodos-container');
    if (metodosContainer) {
        metodosContainer.innerHTML = `
            <div class="row">
                <div class="col-6">
                    <div class="metodo-item active" onclick="cambiarMetodo('tarjeta')">
                        <i class="fas fa-credit-card"></i>
                        <span>Tarjeta</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="metodo-item" onclick="cambiarMetodo('efectivo')">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Efectivo</span>
                    </div>
                </div>
            </div>
        `;
    }
    
    const resumenContainer = document.getElementById('resumen-container');
    if (resumenContainer) {
        resumenContainer.innerHTML = `
            <div class="resumen-item">
                <span class="text-muted">Tipo:</span>
                <strong class="text-warning">Membresía</strong>
            </div>
            <hr>
            <div class="d-flex justify-content-between small mb-2">
                <span>${nombrePlan}</span>
                <span class="text-warning">$${parseFloat(precio).toFixed(2)}</span>
            </div>
        `;
    }
    
    // Mostrar total en el footer (MEJORA)
    const totalFooter = document.getElementById('total-footer');
    const totalUnico = document.getElementById('total-unico');
    if (totalFooter && totalUnico) {
        totalUnico.innerText = `$${parseFloat(precio).toFixed(2)}`;
        totalFooter.style.display = 'block';
    }
}

function cambiarMetodo(metodo) {
    document.querySelectorAll('.metodo-item').forEach(item => {
        item.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
    
    document.querySelectorAll('.metodo-panel').forEach(panel => {
        panel.classList.remove('active');
    });
    document.getElementById('metodo-' + metodo).classList.add('active');
    
    document.getElementById('metodo_pago').value = metodo;
}