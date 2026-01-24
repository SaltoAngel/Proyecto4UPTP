<!-- resources/views/users/edit.blade.php -->
<style>
    .bg-gray-100 {
        background-color: #f8f9fa;
    }
    .badge.py-2 {
        line-height: 1.5;
    }
    
    .form-control[readonly] {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        cursor: not-allowed;
    }
</style>

<div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">
                    <i class="material-icons me-2">edit</i>Editar Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formEditarUsuario">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Mensaje de éxito -->
                    <div id="exitoModal" class="alert alert-success alert-dismissible fade show d-none" role="alert">
                        <span class="alert-icon"><i class="material-icons opacity-10">check_circle</i></span>
                        <span class="alert-text" id="exitoMensaje"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <!-- Mensaje de errores -->
                    <div id="erroresModal" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
                        <span class="alert-icon"><i class="material-icons opacity-10">error</i></span>
                        <span class="alert-text">
                            <strong>Por favor corrija los siguientes errores:</strong>
                            <ul id="listaErrores" class="mb-0"></ul>
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <div class="row">
                        <!-- Información de la Persona (solo lectura) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Persona</label>
                                <div class="input-group input-group-outline">
                                    <input type="text" 
                                           class="form-control" 
                                           id="personaInfo"
                                           readonly>
                                </div>
                                <small class="text-muted">La persona no puede ser modificada</small>
                            </div>
                        </div>

                        <!-- Email (solo lectura) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Correo electrónico</label>
                                <div class="input-group input-group-outline">
                                    <input type="email" 
                                           class="form-control" 
                                           id="emailUsuario"
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Solo Rol -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role" class="form-label">Rol *</label>
                                <div class="input-group input-group-outline">
                                    <select class="form-control" 
                                            id="role" 
                                            name="role" 
                                            required>
                                        <option value="">– Seleccione un rol –</option>
                                        <!-- Roles se cargarán dinámicamente -->
                                    </select>
                                </div>
                                <div class="text-danger text-sm mt-1 d-none" id="role-error"></div>
                            </div>
                        </div>
                        
                        <!-- Estado (solo visual, no editable) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Estado Actual</label>
                                <div class="input-group input-group-outline">
                                    <div id="estadoBadge" class="badge py-2 px-3 w-100 text-center" style="font-size: 0.9rem;"></div>
                                </div>
                                <small class="text-muted">
                                    El estado se gestiona desde la tabla principal usando los botones de activar/desactivar
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card bg-gray-100">
                                <div class="card-body">
                                    <h6 class="mb-3">
                                        <i class="material-icons opacity-10 me-2">info</i>
                                        Información del Usuario
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="text-sm mb-1"><strong>Fecha de creación:</strong></p>
                                            <p class="text-sm" id="fechaCreacion">-</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="text-sm mb-1"><strong>Última actualización:</strong></p>
                                            <p class="text-sm" id="ultimaActualizacion">-</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="text-sm mb-1"><strong>Último acceso:</strong></p>
                                            <p class="text-sm" id="ultimoAcceso">-</p>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <p class="text-sm mb-1"><strong>Rol actual:</strong></p>
                                            <span id="rolActual" class="badge bg-secondary">Sin rol</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="material-icons me-2">close</i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-icons me-2">save</i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('editarUsuarioModal');
    const form = document.getElementById('formEditarUsuario');
    const erroresModal = document.getElementById('erroresModal');
    const listaErrores = document.getElementById('listaErrores');
    const exitoModal = document.getElementById('exitoModal');
    const exitoMensaje = document.getElementById('exitoMensaje');
    const roleSelect = document.getElementById('role');
    const roleError = document.getElementById('role-error');
    
    if (!modalEl || !form) {
        console.error('Modal elements not found');
        return;
    }

    // Obtener token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Ocultar alertas al inicio
    erroresModal.classList.add('d-none');
    exitoModal.classList.add('d-none');

    // Función para mostrar errores
    const mostrarErrores = (errores) => {
        listaErrores.innerHTML = '';
        errores.forEach(error => {
            const li = document.createElement('li');
            li.textContent = error;
            listaErrores.appendChild(li);
        });
        erroresModal.classList.remove('d-none');
    };

    // Función para mostrar éxito
    const mostrarExito = (mensaje) => {
        exitoMensaje.textContent = mensaje;
        exitoModal.classList.remove('d-none');
    };

    // Función para ocultar alertas
    const ocultarAlertas = () => {
        erroresModal.classList.add('d-none');
        exitoModal.classList.add('d-none');
        listaErrores.innerHTML = '';
        roleError.classList.add('d-none');
        roleError.textContent = '';
    };

    // Función para actualizar el estado visual
    const actualizarEstadoBadge = (status) => {
        const badge = document.getElementById('estadoBadge');
        if (!badge) return;
        
        badge.innerHTML = '';
        
        switch(status) {
            case 'activo':
                badge.className = 'badge bg-success py-2 px-3 w-100 text-center';
                badge.innerHTML = '<i class="material-icons opacity-10 me-1">check_circle</i> Activo';
                break;
            case 'pendiente':
                badge.className = 'badge bg-warning py-2 px-3 w-100 text-center';
                badge.innerHTML = '<i class="material-icons opacity-10 me-1">pending</i> Pendiente';
                break;
            case 'inactivo':
                badge.className = 'badge bg-danger py-2 px-3 w-100 text-center';
                badge.innerHTML = '<i class="material-icons opacity-10 me-1">block</i> Inactivo';
                break;
            default:
                badge.className = 'badge bg-secondary py-2 px-3 w-100 text-center';
                badge.innerHTML = '<i class="material-icons opacity-10 me-1">help</i> Desconocido';
        }
    };

    // Función para cargar roles en el select
    const cargarRoles = (roles, rolActual = null) => {
        if (!roleSelect) return;
        
        roleSelect.innerHTML = '<option value="">– Seleccione un rol –</option>';
        
        if (!roles || roles.length === 0) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No hay roles disponibles';
            roleSelect.appendChild(option);
            roleSelect.disabled = true;
            return;
        }
        
        roleSelect.disabled = false;
        
        roles.forEach(role => {
            const option = document.createElement('option');
            option.value = role.name || role;
            option.textContent = role.name || role;
            option.selected = rolActual === (role.name || role);
            roleSelect.appendChild(option);
        });
    };

    // Validar formulario
    const validarFormulario = () => {
        let valido = true;
        
        // Validar rol
        if (!roleSelect.value || roleSelect.value === '') {
            roleError.textContent = 'Debe seleccionar un rol';
            roleError.classList.remove('d-none');
            roleSelect.classList.add('is-invalid');
            roleSelect.classList.remove('is-valid');
            valido = false;
        } else {
            roleError.classList.add('d-none');
            roleSelect.classList.remove('is-invalid');
            roleSelect.classList.add('is-valid');
        }
        
        return valido;
    };

    // Manejar envío del formulario - Versión mejorada
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        ocultarAlertas();
        
        if (!validarFormulario()) {
            return;
        }
        
        const formData = new FormData(form);
        const url = form.action;
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData)
            });
            
            let data;
            
            // Intentar parsear la respuesta
            try {
                data = await response.json();
            } catch (jsonError) {
                // Si no es JSON, podría ser una redirección HTML
                // Solo cerrar y mostrar SweetAlert
                
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    
                    // Mostrar SweetAlert después de cerrar el modal
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Usuario actualizado correctamente',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        window.location.reload();
                    }
                }, 100);
                return;
            }
            
            // Manejar respuesta JSON
            if (!response.ok) {
                if (data.errors) {
                    const errores = Object.values(data.errors).flat();
                    mostrarErrores(errores);
                } else {
                    mostrarErrores([data.message || 'Error al actualizar el usuario']);
                }
                return;
            }
            
            // Éxito
            if (data.success || data.message) {
                // Actualizar el rol actual en el badge
                const rolActualSpan = document.getElementById('rolActual');
                if (rolActualSpan) {
                    rolActualSpan.textContent = roleSelect.options[roleSelect.selectedIndex].text;
                    rolActualSpan.className = 'badge bg-primary';
                }
                
                // Cerrar modal y mostrar SweetAlert
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    
                    // Mostrar SweetAlert si está disponible
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.message || 'Usuario actualizado correctamente',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        window.location.reload();
                    }
                }, 100);
            }
            
        } catch (error) {
            console.error('Error:', error);
            
            // Mostrar SweetAlert de error si está disponible
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al actualizar el usuario. Por favor, intente nuevamente.'
                });
            } else {
                mostrarErrores(['Error de conexión. Por favor, intente nuevamente.']);
            }
        }
    });

    // Evento al mostrar el modal
    modalEl.addEventListener('show.bs.modal', function(event) {
        ocultarAlertas();
        
        const button = event.relatedTarget;
        if (!button) return;
        
        // Recoger todos los atributos data-*
        const usuarioId = button.getAttribute('data-id');
        const personaInfo = button.getAttribute('data-persona-info') || '';
        const email = button.getAttribute('data-email') || '';
        const status = button.getAttribute('data-status') || 'inactivo';
        const fechaCreacion = button.getAttribute('data-created') || '-';
        const fechaActualizacion = button.getAttribute('data-updated') || '-';
        const ultimoAcceso = button.getAttribute('data-last-login') || 'Nunca';
        const rolActual = button.getAttribute('data-rol-actual') || '';
        
        // Obtener roles desde el botón
        let roles = [];
        try {
            const rolesJson = button.getAttribute('data-roles');
            roles = rolesJson ? JSON.parse(rolesJson) : [];
        } catch (error) {
            console.error('Error al parsear roles:', error);
            roles = [];
        }
        
        if (!usuarioId) {
            console.error('No se encontró ID de usuario');
            mostrarErrores(['No se pudo cargar los datos del usuario']);
            return;
        }
        
        // Establecer la URL del formulario
        const updateBaseUrl = "{{ url('usuarios') }}";
        const updateUrl = `${updateBaseUrl}/${usuarioId}`;
        form.action = updateUrl;
        
        // Llenar los campos de solo lectura
        const personaInput = document.getElementById('personaInfo');
        const emailInput = document.getElementById('emailUsuario');
        
        if (personaInput) personaInput.value = personaInfo || 'No disponible';
        if (emailInput) emailInput.value = email || 'No disponible';
        
        // Cargar roles en el select
        cargarRoles(roles, rolActual);
        
        // Actualizar información adicional
        document.getElementById('fechaCreacion').textContent = fechaCreacion;
        document.getElementById('ultimaActualizacion').textContent = fechaActualizacion;
        document.getElementById('ultimoAcceso').textContent = ultimoAcceso;
        
        // Actualizar rol actual
        const rolActualSpan = document.getElementById('rolActual');
        if (rolActualSpan) {
            rolActualSpan.textContent = rolActual || 'Sin rol';
            rolActualSpan.className = rolActual ? 'badge bg-primary' : 'badge bg-secondary';
        }
        
        // Actualizar estado
        actualizarEstadoBadge(status);
        
        // Limpiar clases de validación
        if (roleSelect) {
            roleSelect.classList.remove('is-valid', 'is-invalid');
        }
    });

    // Limpiar el formulario al cerrar el modal
    modalEl.addEventListener('hidden.bs.modal', function() {
        form.reset();
        ocultarAlertas();
        
        // Limpiar clases de validación
        if (roleSelect) {
            roleSelect.classList.remove('is-valid', 'is-invalid');
            roleSelect.disabled = false;
        }
        
        // Restablecer valores por defecto
        document.getElementById('personaInfo').value = '';
        document.getElementById('emailUsuario').value = '';
        document.getElementById('fechaCreacion').textContent = '-';
        document.getElementById('ultimaActualizacion').textContent = '-';
        document.getElementById('ultimoAcceso').textContent = '-';
        document.getElementById('rolActual').textContent = 'Sin rol';
        document.getElementById('rolActual').className = 'badge bg-secondary';
        document.getElementById('estadoBadge').innerHTML = '';
        document.getElementById('estadoBadge').className = 'badge py-2 px-3 w-100 text-center';
    });

    // Validación en tiempo real
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            if (this.value && this.value !== '') {
                roleError.classList.add('d-none');
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    }
});
</script>
@endpush