<div class="modal fade" id="crearProveedorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="material-icons me-2">local_shipping</i>Registrar Proveedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCrearProveedor" method="POST" action="{{ route('dashboard.proveedores.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Persona <span class="text-danger">*</span></label>
                            <select name="persona_id" class="form-select" required>
                                <option value="">Seleccione persona</option>
                                @foreach($personas as $persona)
                                    <option value="{{ $persona->id }}">{{ $persona->codigo }} - {{ $persona->nombre_completo }}</option>
                                @endforeach
                            </select>
                            <span class="validation-feedback validation-error" style="display: none;"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Categorías (múltiples)</label>
                            <div class="border rounded p-2" id="crearSelectorCategorias" data-target-input="#crearTiposSeleccionados" data-target-summary="#crearResumenCategorias">
                                <div class="d-flex flex-wrap gap-1 mb-2 categorias-seleccionadas"></div>
                                <div class="d-flex flex-wrap gap-1 categorias-disponibles">
                                    @foreach($tiposProveedores as $tipo)
                                        <button type="button" class="btn btn-sm btn-outline-primary categoria-item" data-id="{{ $tipo->id }}" data-nombre="{{ $tipo->nombre_tipo }}">{{ $tipo->nombre_tipo }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Especialización</label>
                            <input type="text" name="especializacion" class="form-control">
                            <span class="validation-feedback validation-error" style="display: none;"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Productos / Servicios</label>
                        <textarea name="productos_servicios" class="form-control" rows="2"></textarea>
                        <span class="validation-feedback validation-error" style="display: none;"></span>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Contacto comercial</label>
                            <input type="text" name="contacto_comercial" class="form-control">
                            <span class="validation-feedback validation-error" style="display: none;"></span>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Teléfono comercial</label>
                            <input type="text" name="telefono_comercial" class="form-control">
                            <span class="validation-feedback validation-error" style="display: none;"></span>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email comercial</label>
                            <input type="email" name="email_comercial" class="form-control">
                            <span class="validation-feedback validation-error" style="display: none;"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Calificación (1-5)</label>
                            <select name="calificacion" class="form-select">
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ $i === 5 ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado" class="form-select" required>
                                <option value="activo" selected>Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="suspendido">Suspendido</option>
                                <option value="en_revision">En revisión</option>
                                <option value="bloqueado">Bloqueado</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones de calificación</label>
                        <textarea name="observaciones_calificacion" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="material-icons me-2">close</i>Cancelar</button>
                    <button type="submit" class="btn btn-success" id="btnGuardarProveedor">
                        <span id="btnTextProveedor"><i class="material-icons me-2">save</i>Guardar</span>
                        <div class="spinner-border spinner-border-sm" id="spinnerProveedor" style="display: none;"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formCrearProveedor');
    const btnGuardar = document.getElementById('btnGuardarProveedor');
    const btnText = document.getElementById('btnTextProveedor');
    const spinner = document.getElementById('spinnerProveedor');
    
    // Elementos del formulario
    const personaSelect = form.querySelector('select[name="persona_id"]');
    const emailComercial = form.querySelector('input[name="email_comercial"]');
    const telefonoComercial = form.querySelector('input[name="telefono_comercial"]');
    const contactoComercial = form.querySelector('input[name="contacto_comercial"]');
    
    // Funciones de validación
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    function isValidPhone(phone) {
        const re = /^[\d\s\-\+\(\)]{7,15}$/;
        return re.test(phone.replace(/\s/g, ''));
    }
    
    function showFieldError(field, message) {
        const feedback = field.parentNode.querySelector('.validation-feedback');
        if (feedback) {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            feedback.textContent = message;
            feedback.style.display = 'block';
            feedback.classList.add('validation-error');
        }
    }
    
    function showFieldSuccess(field) {
        const feedback = field.parentNode.querySelector('.validation-feedback');
        if (feedback) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            feedback.style.display = 'none';
        }
    }
    
    function clearFieldError(field) {
        const feedback = field.parentNode.querySelector('.validation-feedback');
        if (feedback) {
            field.classList.remove('is-invalid', 'is-valid');
            feedback.style.display = 'none';
        }
    }
    
    // Validaciones en tiempo real
    personaSelect.addEventListener('change', function() {
        if (this.value) {
            showFieldSuccess(this);
        } else {
            showFieldError(this, 'Debe seleccionar una persona');
        }
    });
    
    emailComercial.addEventListener('input', function() {
        const email = this.value.trim();
        if (!email) {
            clearFieldError(this);
        } else if (!isValidEmail(email)) {
            showFieldError(this, 'Ingrese un email válido');
        } else {
            showFieldSuccess(this);
        }
    });
    
    telefonoComercial.addEventListener('input', function() {
        const phone = this.value.trim();
        if (!phone) {
            clearFieldError(this);
        } else if (!isValidPhone(phone)) {
            showFieldError(this, 'Ingrese un teléfono válido');
        } else {
            showFieldSuccess(this);
        }
    });
    
    contactoComercial.addEventListener('input', function() {
        const contacto = this.value.trim();
        if (contacto && contacto.length < 2) {
            showFieldError(this, 'El nombre debe tener al menos 2 caracteres');
        } else if (contacto) {
            showFieldSuccess(this);
        } else {
            clearFieldError(this);
        }
    });
    
    // Validación del formulario
    function validateForm() {
        let isValid = true;
        
        // Validar persona requerida
        if (!personaSelect.value) {
            showFieldError(personaSelect, 'Debe seleccionar una persona');
            isValid = false;
        }
        
        // Validar email si se ingresó
        if (emailComercial.value && !isValidEmail(emailComercial.value)) {
            showFieldError(emailComercial, 'Ingrese un email válido');
            isValid = false;
        }
        
        // Validar teléfono si se ingresó
        if (telefonoComercial.value && !isValidPhone(telefonoComercial.value)) {
            showFieldError(telefonoComercial, 'Ingrese un teléfono válido');
            isValid = false;
        }
        
        return isValid;
    }
    
    // Manejar envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            Swal.fire({
                icon: 'warning',
                title: 'Revisa los datos',
                text: 'Por favor corrige los errores marcados en rojo',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#28a745'
            });
            return;
        }
        
        // Mostrar loading
        btnGuardar.disabled = true;
        btnText.style.display = 'none';
        spinner.style.display = 'inline-block';
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Ocultar loading
            btnGuardar.disabled = false;
            btnText.style.display = 'inline-block';
            spinner.style.display = 'none';
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Proveedor guardado!',
                    text: 'El proveedor se registró correctamente',
                    timer: 2000,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(() => {
                    // Cerrar modal y recargar tabla
                    bootstrap.Modal.getInstance(document.getElementById('crearProveedorModal')).hide();
                    if (typeof tabla !== 'undefined') {
                        tabla.ajax.reload();
                    } else {
                        location.reload();
                    }
                });
            } else {
                // Mostrar errores de validación
                if (data.errors) {
                    let errorMessage = 'Por favor corrige los siguientes errores:\n';
                    Object.values(data.errors).flat().forEach(error => {
                        errorMessage += '• ' + error + '\n';
                    });
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: errorMessage,
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#28a745'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar',
                        text: data.message || 'No se pudo guardar el proveedor',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#28a745'
                    });
                }
            }
        })
        .catch(error => {
            // Ocultar loading
            btnGuardar.disabled = false;
            btnText.style.display = 'inline-block';
            spinner.style.display = 'none';
            
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#28a745'
            });
        });
    });
    
    // Limpiar formulario al cerrar modal
    document.getElementById('crearProveedorModal').addEventListener('hidden.bs.modal', function() {
        form.reset();
        form.querySelectorAll('.is-invalid, .is-valid').forEach(field => {
            field.classList.remove('is-invalid', 'is-valid');
        });
        form.querySelectorAll('.validation-feedback').forEach(feedback => {
            feedback.style.display = 'none';
        });
    });
});
</script>
