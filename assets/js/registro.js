document.addEventListener('DOMContentLoaded', function() {
    
    // Referencias a elementos
    const registroForm = document.getElementById('registroForm');
    const password = document.getElementById('password');
    const confirmar = document.getElementById('confirmar');
    const email = document.getElementById('email');
    const telefono = document.getElementById('telefono');
    const nombre = document.getElementById('nombre');
    const apellido = document.getElementById('apellido');
    
    // Enfocar primer campo
    if (nombre) {
        nombre.focus();
    }
    
    // Mostrar/ocultar contraseñas
    function setupPasswordToggle(input) {
        if (!input) return;
        
        const toggle = document.createElement('i');
        toggle.className = 'fas fa-eye toggle-password';
        toggle.style.cssText = 'position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;';
        
        const wrapper = input.closest('.input-wrapper');
        if (wrapper) {
            wrapper.style.position = 'relative';
            wrapper.appendChild(toggle);
            
            toggle.addEventListener('click', function() {
                if (input.type === 'password') {
                    input.type = 'text';
                    this.className = 'fas fa-eye-slash toggle-password';
                } else {
                    input.type = 'password';
                    this.className = 'fas fa-eye toggle-password';
                }
            });
        }
    }
    
    setupPasswordToggle(password);
    setupPasswordToggle(confirmar);
    
    // Validación en tiempo real
    if (email) {
        email.addEventListener('blur', function() {
            validateEmail(this);
        });
    }
    
    if (password) {
        password.addEventListener('input', function() {
            validatePassword(this);
            if (confirmar && confirmar.value) {
                validateConfirmPassword(password, confirmar);
            }
        });
    }
    
    if (confirmar) {
        confirmar.addEventListener('input', function() {
            validateConfirmPassword(password, this);
        });
    }
    
    if (telefono) {
        telefono.addEventListener('input', function() {
            // Formato automático: 0000-0000
            let value = this.value.replace(/\D/g, '');
            if (value.length > 4) {
                value = value.slice(0,4) + '-' + value.slice(4,8);
            }
            if (value.length > 9) {
                value = value.slice(0,9);
            }
            this.value = value;
            
            validatePhone(this);
        });
    }
    
    // Funciones de validación
    function validateEmail(input) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const errorDiv = getOrCreateError(input, 'email-error');
        
        if (!input.value) {
            showError(errorDiv, 'El email es obligatorio');
            return false;
        } else if (!re.test(input.value)) {
            showError(errorDiv, 'Ingresa un email válido');
            return false;
        } else {
            hideError(errorDiv);
            return true;
        }
    }
    
    function validatePassword(input) {
        const errorDiv = getOrCreateError(input, 'password-error');
        
        if (!input.value) {
            showError(errorDiv, 'La contraseña es obligatoria');
            return false;
        } else if (input.value.length < 6) {
            showError(errorDiv, 'Mínimo 6 caracteres');
            return false;
        } else {
            hideError(errorDiv);
            return true;
        }
    }
    
    function validateConfirmPassword(passInput, confirmInput) {
        const errorDiv = getOrCreateError(confirmInput, 'confirm-error');
        
        if (!confirmInput.value) {
            showError(errorDiv, 'Confirma tu contraseña');
            return false;
        } else if (confirmInput.value !== passInput.value) {
            showError(errorDiv, 'Las contraseñas no coinciden');
            return false;
        } else {
            hideError(errorDiv);
            return true;
        }
    }
    
    function validatePhone(input) {
        const errorDiv = getOrCreateError(input, 'phone-error');
        const re = /^[0-9]{4}-?[0-9]{4}$/;
        
        if (input.value && !re.test(input.value)) {
            showError(errorDiv, 'Formato: 0000-0000');
            return false;
        } else {
            hideError(errorDiv);
            return true;
        }
    }
    
    // Funciones auxiliares
    function getOrCreateError(input, id) {
        let errorDiv = document.getElementById(id);
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = id;
            errorDiv.className = 'validation-error';
            errorDiv.style.cssText = 'color: #e74c3c; font-size: 12px; margin-top: 5px; display: none;';
            errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span></span>';
            input.closest('.input-wrapper').insertAdjacentElement('afterend', errorDiv);
        }
        return errorDiv;
    }
    
    function showError(errorDiv, message) {
        errorDiv.querySelector('span').textContent = message;
        errorDiv.style.display = 'block';
        const inputWrapper = errorDiv.previousElementSibling;
        if (inputWrapper) {
            inputWrapper.style.borderColor = '#e74c3c';
        }
    }
    
    function hideError(errorDiv) {
        errorDiv.style.display = 'none';
        const inputWrapper = errorDiv.previousElementSibling;
        if (inputWrapper) {
            inputWrapper.style.borderColor = '';
        }
    }
    
    // Validación al enviar formulario
    if (registroForm) {
        registroForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validar campos obligatorios
            if (!nombre || !nombre.value.trim()) {
                showError(getOrCreateError(nombre, 'nombre-error'), 'El nombre es obligatorio');
                isValid = false;
            }
            
            if (!apellido || !apellido.value.trim()) {
                showError(getOrCreateError(apellido, 'apellido-error'), 'El apellido es obligatorio');
                isValid = false;
            }
            
            if (!validateEmail(email)) isValid = false;
            if (!validatePassword(password)) isValid = false;
            if (!validateConfirmPassword(password, confirmar)) isValid = false;
            
            if (telefono && telefono.value && !validatePhone(telefono)) isValid = false;
            
            if (!isValid) {
                e.preventDefault();
                
                // Scroll al primer error
                const firstError = document.querySelector('.validation-error[style*="block"]');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
    
    // Animaciones del botón
    const btnRegistro = document.querySelector('.btn-registro');
    if (btnRegistro) {
        btnRegistro.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'all 0.3s ease';
        });
        
        btnRegistro.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    }
});