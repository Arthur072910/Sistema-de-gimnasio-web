// ========================================
// REGISTRO - DELUX GYM
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Enfocar el primer campo
    const nombreInput = document.getElementById('nombre');
    if (nombreInput) {
        nombreInput.focus();
    }
    
    // 2. Referencias a elementos
    const registroForm = document.getElementById('registroForm');
    const password = document.getElementById('password');
    const confirmar = document.getElementById('confirmar');
    const email = document.getElementById('email');
    const telefono = document.getElementById('telefono');
    
    // 3. Mostrar/ocultar contraseñas
    function setupPasswordToggle(inputId, iconId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        
        const toggle = document.createElement('i');
        toggle.className = 'fas fa-eye toggle-password';
        toggle.setAttribute('data-target', inputId);
        
        const wrapper = input.closest('.input-wrapper');
        if (wrapper) {
            wrapper.appendChild(toggle);
            
            toggle.addEventListener('click', function() {
                const target = document.getElementById(this.dataset.target);
                if (target.type === 'password') {
                    target.type = 'text';
                    this.className = 'fas fa-eye-slash toggle-password';
                } else {
                    target.type = 'password';
                    this.className = 'fas fa-eye toggle-password';
                }
            });
        }
    }
    
    setupPasswordToggle('password');
    setupPasswordToggle('confirmar');
    
    // 4. Validación en tiempo real
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
            validatePhone(this);
        });
    }
    
    // 5. Funciones de validación
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
        const re = /^[0-9]{8,15}$/;
        
        if (input.value && !re.test(input.value.replace(/[-\s]/g, ''))) {
            showError(errorDiv, 'Teléfono inválido (solo números)');
            return false;
        } else {
            hideError(errorDiv);
            return true;
        }
    }
    
    // 6. Funciones auxiliares
    function getOrCreateError(input, id) {
        let errorDiv = document.getElementById(id);
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = id;
            errorDiv.className = 'validation-error';
            errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> <span></span>';
            input.parentNode.insertAdjacentElement('afterend', errorDiv);
        }
        return errorDiv;
    }
    
    function showError(errorDiv, message) {
        errorDiv.querySelector('span').textContent = message;
        errorDiv.style.display = 'block';
        errorDiv.previousElementSibling.classList.add('error');
    }
    
    function hideError(errorDiv) {
        errorDiv.style.display = 'none';
        errorDiv.previousElementSibling.classList.remove('error');
    }
    
    // 7. Validación al enviar
    if (registroForm) {
        registroForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validar todos los campos
            if (!validateEmail(email)) isValid = false;
            if (!validatePassword(password)) isValid = false;
            if (!validateConfirmPassword(password, confirmar)) isValid = false;
            if (telefono.value && !validatePhone(telefono)) isValid = false;
            
            // Validar campos obligatorios
            const nombre = document.getElementById('nombre');
            const apellido = document.getElementById('apellido');
            
            if (!nombre.value) {
                showError(getOrCreateError(nombre, 'nombre-error'), 'El nombre es obligatorio');
                isValid = false;
            }
            
            if (!apellido.value) {
                showError(getOrCreateError(apellido, 'apellido-error'), 'El apellido es obligatorio');
                isValid = false;
            }
            
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
    
    // 8. Animaciones
    const btnRegistro = document.querySelector('.btn-registro');
    if (btnRegistro) {
        btnRegistro.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btnRegistro.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    }
    
    // 9. Formato de teléfono
    if (telefono) {
        telefono.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 4) {
                value = value.slice(0,4) + '-' + value.slice(4,8);
            }
            if (value.length > 9) {
                value = value.slice(0,9);
            }
            this.value = value;
        });
    }
});