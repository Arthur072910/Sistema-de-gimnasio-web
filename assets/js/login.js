// ========================================
// LOGIN - DELUX GYM
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Enfocar el campo email automáticamente
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.focus();
    }
    
    // 2. Validar formulario antes de enviar
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!email || !password) {
                e.preventDefault();
                showError('Todos los campos son obligatorios');
                return false;
            }
            
            if (!isValidEmail(email)) {
                e.preventDefault();
                showError('Ingresa un email válido');
                return false;
            }
        });
    }
    
    // 3. Función para validar email
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    // 4. Función para mostrar errores
    function showError(message) {
        // Buscar si ya existe un mensaje de error
        let errorDiv = document.querySelector('.error-message');
        
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            
            const icon = document.createElement('i');
            icon.className = 'fas fa-exclamation-circle';
            
            const text = document.createElement('span');
            text.textContent = message;
            
            errorDiv.appendChild(icon);
            errorDiv.appendChild(text);
            
            // Insertar después del logo
            const logo = document.querySelector('.logo');
            logo.insertAdjacentElement('afterend', errorDiv);
        } else {
            errorDiv.querySelector('span').textContent = message;
        }
        
        // Ocultar después de 3 segundos
        setTimeout(() => {
            if (errorDiv) {
                errorDiv.remove();
            }
        }, 3000);
    }
    
    // 5. Mostrar/ocultar contraseña (opcional)
    const togglePassword = document.createElement('i');
    togglePassword.className = 'fas fa-eye';
    togglePassword.style.cssText = `
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #ffd700;
        cursor: pointer;
        z-index: 10;
    `;
    
    const passwordWrapper = document.querySelector('.input-wrapper');
    if (passwordWrapper) {
        passwordWrapper.appendChild(togglePassword);
        
        togglePassword.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePassword.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                togglePassword.className = 'fas fa-eye';
            }
        });
    }
    
    // 6. Animación para el botón
    const btnLogin = document.querySelector('.btn-login');
    if (btnLogin) {
        btnLogin.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btnLogin.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    }
    
    // 7. Recordar email (localStorage)
    const rememberCheck = document.getElementById('remember');
    const savedEmail = localStorage.getItem('delux_email');
    
    if (savedEmail && emailInput) {
        emailInput.value = savedEmail;
        if (rememberCheck) rememberCheck.checked = true;
    }
    
    if (rememberCheck) {
        rememberCheck.addEventListener('change', function() {
            if (this.checked && emailInput.value) {
                localStorage.setItem('delux_email', emailInput.value);
            } else {
                localStorage.removeItem('delux_email');
            }
        });
    }
    
    // 8. Guardar email cuando se escribe
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            if (rememberCheck && rememberCheck.checked && this.value) {
                localStorage.setItem('delux_email', this.value);
            }
        });
    }
});