// pago.js - Manejo de la página de pago

document.addEventListener('DOMContentLoaded', function() {
    cargarResumenPedido();
    configurarFormulario();
});

function cargarResumenPedido() {
    // Verificar si hay datos de membresía (viene desde PHP)
    const tieneMembresia = document.getElementById('tiene-membresia')?.value === '1';
    
    if (tieneMembresia) {
        // Los datos de membresía ya se cargaron desde PHP
        return;
    }
    
    // Si no, buscar en sessionStorage (carrito)
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
    const btnPagar = document.getElementById('btn-pagar');
    
    if (resumenContainer) {
        resumenContainer.innerHTML = 
            '<p class="text-center text-muted">No hay productos para pagar</p>' +
            '<a href="productos.php" class="btn-gold-gym w-100 mt-3">IR A TIENDA</a>';
    }
    
    if (metodosContainer) {
        metodosContainer.innerHTML = 
            '<p class="text-center text-muted">Selecciona productos primero</p>';
    }
    
    if (btnPagar) {
        btnPagar.style.display = 'none';
    }
}

function mostrarProductos(items, total) {
    const tipoTransaccion = document.getElementById('tipo_transaccion');
    const montoTotal = document.getElementById('monto_total');
    const itemsField = document.getElementById('items');
    const metodosContainer = document.getElementById('metodos-container');
    const resumenContainer = document.getElementById('resumen-container');
    const btnPagar = document.getElementById('btn-pagar');
    
    if (tipoTransaccion) tipoTransaccion.value = 'producto';
    if (montoTotal) montoTotal.value = total;
    if (itemsField) itemsField.value = JSON.stringify(items);
    
    // Mostrar solo tarjeta como método de pago
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
    
    // Generar HTML del resumen
    let itemsHtml = '';
    items.forEach(item => {
        itemsHtml += `
            <div class="d-flex justify-content-between small mb-2">
                <span>${item.cantidad}x ${item.titulo}</span>
                <span class="text-warning">$${(item.precio * item.cantidad).toFixed(2)}</span>
            </div>
        `;
    });
    
    if (resumenContainer) {
        resumenContainer.innerHTML = `
            <div class="resumen-item">
                <span class="text-muted">Tipo:</span>
                <strong class="text-warning">Productos</strong>
            </div>
            <hr>
            ${itemsHtml}
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <span class="h5 mb-0">Total:</span>
                <span class="h4 mb-0 text-warning">$${total}</span>
            </div>
        `;
    }
    
    if (btnPagar) {
        btnPagar.innerHTML = `<i class="fas fa-lock mr-2"></i> PAGAR $${total}`;
        btnPagar.style.display = 'block';
    }
}

function mostrarMembresia(nombrePlan, precio) {
    const tipoTransaccion = document.getElementById('tipo_transaccion');
    const montoTotal = document.getElementById('monto_total');
    const metodosContainer = document.getElementById('metodos-container');
    const resumenContainer = document.getElementById('resumen-container');
    const btnPagar = document.getElementById('btn-pagar');
    
    if (tipoTransaccion) tipoTransaccion.value = 'membresia';
    if (montoTotal) montoTotal.value = precio;
    
    // Mostrar métodos de pago (tarjeta + efectivo)
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
    
    // Mostrar resumen
    if (resumenContainer) {
        resumenContainer.innerHTML = `
            <div class="resumen-item">
                <span class="text-muted">Tipo:</span>
                <strong class="text-warning">Membresía</strong>
            </div>
            <div class="resumen-item">
                <span class="text-muted">Plan:</span>
                <strong>${nombrePlan}</strong>
            </div>
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <span class="h5 mb-0">Total:</span>
                <span class="h4 mb-0 text-warning">$${parseFloat(precio).toFixed(2)}</span>
            </div>
        `;
    }
    
    if (btnPagar) {
        btnPagar.innerHTML = `<i class="fas fa-lock mr-2"></i> PAGAR $${parseFloat(precio).toFixed(2)}`;
        btnPagar.style.display = 'block';
    }
}

function cambiarMetodo(metodo) {
    // Actualizar items activos
    document.querySelectorAll('.metodo-item').forEach(item => {
        item.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
    
    // Actualizar paneles
    document.querySelectorAll('.metodo-panel').forEach(panel => {
        panel.classList.remove('active');
    });
    document.getElementById('metodo-' + metodo).classList.add('active');
    
    // Actualizar input oculto
    document.getElementById('metodo_pago').value = metodo;
}

function configurarFormulario() {
    const form = document.getElementById('form-pago');
    if (form) {
        form.addEventListener('submit', function() {
            // El formulario se envía normalmente
            console.log('Procesando pago...');
        });
    }
}

// Función para limpiar carrito después de pago exitoso
function limpiarCarritoDespuesPago() {
    localStorage.removeItem('gym_cart');
    sessionStorage.removeItem('pago_carrito');
    sessionStorage.removeItem('pago_total');
    console.log('✅ Carrito limpiado después del pago');
}