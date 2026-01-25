<!-- resources/views/users/modal-create.blade.php -->
<div class="modal fade" id="crearUsuarioModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">
                    <i class="material-icons me-2 text-white">person_add</i>Registrar Nuevo Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="material-icons opacity-10">check_circle</i></span>
                    <span class="alert-text">{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('verification_sent'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="material-icons opacity-10">mail</i></span>
                    <span class="alert-text">
                        Se ha enviado un código de verificación a: <strong>{{ session('email') }}</strong>
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="material-icons opacity-10">error</i></span>
                    <span class="alert-text">
                        <strong>Por favor corrija los siguientes errores:</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form method="POST" action="{{ route('users.store') }}" id="userForm">
                    @csrf

                    <div class="row">
                        <!-- Selección de Persona -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="persona_id" class="form-label fw-bold">Persona *</label>
                                <div class="input-group input-group-outline">
                                    <select class="form-control @error('persona_id') is-invalid @enderror" 
                                            id="persona_id" 
                                            name="persona_id" 
                                            required
                                            onchange="loadPersonaData(this.value)">
                                        <option value="">– Seleccione una persona –</option>
                                        @foreach($personas as $persona)
                                            <option value="{{ $persona['id'] }}" 
                                                    data-email="{{ $persona['email'] }}"
                                                    data-documento="{{ $persona['documento'] }}"
                                                    {{ old('persona_id') == $persona['id'] ? 'selected' : '' }}>
                                                {{ $persona['text'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('persona_id')
                                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Solo se muestran personas que no tienen usuario asignado y tienen email válido.</small>
                            </div>
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label fw-bold">Correo electrónico *</label>
                                <div class="input-group input-group-outline">
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required
                                           readonly>
                                    <span class="input-group-text">
                                        <button type="button" 
                                                class="btn btn-link text-primary p-0" 
                                                id="sendCodeBtn"
                                                disabled
                                                onclick="sendVerificationCode()">
                                            <i class="material-icons opacity-10">send</i> Enviar código
                                        </button>
                                    </span>
                                </div>
                                @error('email')
                                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">El email se auto-completa al seleccionar una persona.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Código de Verificación -->
                    <div class="row mt-3" id="verificationCodeGroup" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="verification_code" class="form-label fw-bold">Código de verificación *</label>
                                <div class="input-group input-group-outline">
                                    <input type="text" 
                                           class="form-control @error('verification_code') is-invalid @enderror" 
                                           id="verification_code" 
                                           name="verification_code" 
                                           placeholder="Ingrese el código de 8 dígitos"
                                           maxlength="8"
                                           value="{{ old('verification_code') }}"
                                           required>
                                    <span class="input-group-text">
                                        <button type="button" 
                                                class="btn btn-link text-secondary p-0" 
                                                onclick="resendCode()">
                                            <i class="material-icons opacity-10">refresh</i>
                                        </button>
                                    </span>
                                </div>
                                @error('verification_code')
                                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Se ha enviado un código al correo electrónico. Válido por 30 minutos.</small>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="button" class="btn btn-success" id="validateCodeBtn" onclick="validateCode()">
                                <i class="material-icons opacity-10 me-1">check_circle</i> Validar Código
                            </button>
                        </div>
                    </div>

                    <!-- Contraseña y Confirmar Contraseña (Autocompletadas con la cédula) -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label fw-bold">Contraseña *</label>
                                <div class="input-group input-group-outline">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           value="{{ old('password') }}" 
                                           required
                                           readonly
                                           placeholder="Se autocompletará con la cédula">
                                </div>
                                @error('password')
                                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">La contraseña será la cédula de la persona seleccionada.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label fw-bold">Confirmar contraseña *</label>
                                <div class="input-group input-group-outline">
                                    <input type="password" 
                                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required
                                           readonly
                                           placeholder="Se autocompletará con la cédula">
                                </div>
                                @error('password_confirmation')
                                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Información de Estado -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info" role="alert">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="material-icons opacity-10">info</i>
                                    </div>
                                    <div>
                                        <h6 class="alert-heading">Información del Usuario</h6>
                                        <p class="mb-1">
                                            <strong>Nota:</strong> La contraseña inicial será la cédula de la persona.
                                            El usuario deberá iniciar sesión con su cédula como contraseña y luego cambiar 
                                            su contraseña en su primer acceso al sistema.
                                        </p>
                                        <p class="mb-0 text-sm">
                                            <i class="material-icons opacity-10 me-1" style="font-size: 16px">hourglass_empty</i>
                                            Estado inicial: <span class="badge bg-gradient-warning">Pendiente</span>
                                            (Hasta que inicie sesión y cambie su contraseña)
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rol (Usando Spatie - guardamos el NAME del rol, no el ID) -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role_name" class="form-label fw-bold">Rol *</label>
                                <div class="input-group input-group-outline">
                                    <select class="form-control @error('role_name') is-invalid @enderror" 
                                            id="role_name" 
                                            name="role_name" 
                                            required>
                                        <option value="">– Seleccione un rol –</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role_name') == $role->name ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('role_name')
                                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="row mt-4">
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                                <i class="material-icons opacity-10 me-1">cancel</i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="material-icons opacity-10 me-1">save</i> Guardar Usuario
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Variable para controlar si el código fue validado
let isCodeValidated = false;
let currentDocumento = '';
let isCodeSent = false;

function loadPersonaData(personaId) {
    if (!personaId) {
        $('#email').val('');
        $('#password').val('');
        $('#password_confirmation').val('');
        currentDocumento = '';
        $('#sendCodeBtn').prop('disabled', true);
        $('#verificationCodeGroup').hide();
        resetForm();
        return;
    }

    const selectedOption = $(`#persona_id option[value="${personaId}"]`);
    const email = selectedOption.data('email');
    const documento = selectedOption.data('documento');
    
    $('#email').val(email || '');
    $('#password').val(documento || '');
    $('#password_confirmation').val(documento || '');
    currentDocumento = documento || '';
    $('#sendCodeBtn').prop('disabled', !email);
    resetForm();
}

function sendVerificationCode() {
    const email = $('#email').val();
    const personaId = $('#persona_id').val();
    
    if (!email || !personaId) {
        Swal.fire({
            icon: 'warning',
            title: 'Datos incompletos',
            text: 'Primero debe seleccionar una persona con email válido.'
        });
        return;
    }

    const btn = $('#sendCodeBtn');
    btn.prop('disabled', true);
    btn.html('<i class="material-icons opacity-10">hourglass_empty</i> Enviando...');

    $.ajax({
        url: '{{ route("users.send-verification-code") }}',
        method: 'POST',
        data: {
            email: email,
            persona_id: personaId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            btn.prop('disabled', false);
            btn.html('<i class="material-icons opacity-10">send</i> Enviar código');
            $('#verificationCodeGroup').show();
            $('#verification_code').focus();
            isCodeValidated = false;
            isCodeSent = true;
            $('#submitBtn').prop('disabled', true);
            
            Swal.fire({
                icon: 'success',
                title: 'Código enviado',
                text: response.message,
                timer: 3000,
                showConfirmButton: false
            });
        },
        error: function(xhr) {
            btn.prop('disabled', false);
            btn.html('<i class="material-icons opacity-10">send</i> Enviar código');
            
            let errorMessage = 'Error al enviar el código';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                confirmButtonText: 'Entendido'
            });
        }
    });
}

function validateCode() {
    const code = $('#verification_code').val();
    const email = $('#email').val();
    const personaId = $('#persona_id').val();
    
    if (!code || code.length !== 8) {
        Swal.fire({
            icon: 'error',
            title: 'Código inválido',
            text: 'Por favor ingrese un código de 8 dígitos.',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    const btn = $('#validateCodeBtn');
    btn.prop('disabled', true);
    btn.html('<i class="material-icons opacity-10">hourglass_empty</i> Validando...');

    $.ajax({
        url: '{{ route("users.validate-verification-code") }}',
        method: 'POST',
        data: {
            email: email,
            code: code,
            persona_id: personaId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            isCodeValidated = true;
            btn.html('<i class="material-icons opacity-10">check_circle</i> Código Validado');
            btn.removeClass('btn-success').addClass('btn-outline-success');
            btn.prop('disabled', true);
            checkFormCompletion();
            
            Swal.fire({
                icon: 'success',
                title: 'Código validado',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
            });
        },
        error: function(xhr) {
            btn.prop('disabled', false);
            btn.html('<i class="material-icons opacity-10">check_circle</i> Validar Código');
            
            let errorMessage = 'Error al validar el código';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                confirmButtonText: 'Entendido'
            });
        }
    });
}

function resendCode() {
    sendVerificationCode();
}

function resetForm() {
    isCodeValidated = false;
    isCodeSent = false;
    $('#verificationCodeGroup').hide();
    $('#verification_code').val('');
    $('#validateCodeBtn')
        .prop('disabled', false)
        .html('<i class="material-icons opacity-10 me-1">check_circle</i> Validar Código')
        .removeClass('btn-outline-success')
        .addClass('btn-success');
    $('#submitBtn').prop('disabled', true);
}

function checkFormCompletion() {
    const personaId = $('#persona_id').val();
    const email = $('#email').val();
    const password = $('#password').val();
    const passwordConfirm = $('#password_confirmation').val();
    const roleName = $('#role_name').val();
    
    let allFieldsComplete = personaId && 
                           email && 
                           password && 
                           passwordConfirm && 
                           roleName;
    
    const passwordsMatch = password === passwordConfirm;
    
    let codeValid = false;
    if (isCodeSent) {
        codeValid = isCodeValidated;
    }
    
    if (!isCodeSent && allFieldsComplete) {
        $('#submitBtn').prop('disabled', true);
        return false;
    }
    
    const shouldEnableButton = allFieldsComplete && passwordsMatch && codeValid;
    $('#submitBtn').prop('disabled', !shouldEnableButton);
    return shouldEnableButton;
}

$(document).ready(function() {
    // Resetear formulario cuando se abre el modal
    $('#crearUsuarioModal').on('show.bs.modal', function() {
        resetForm();
    });

    // Escuchar cambios en los campos clave
    $('#role_name, #persona_id, #email').on('change', function() {
        checkFormCompletion();
        if (isCodeSent && !$(this).is('#email')) {
            resetForm();
        }
    });
    
    // Sincronizar contraseñas
    $('#password, #password_confirmation').on('input', function() {
        if (currentDocumento) {
            $('#password').val(currentDocumento);
            $('#password_confirmation').val(currentDocumento);
        }
        checkFormCompletion();
    });
    
    // Cuando el código de verificación cambia
    $('#verification_code').on('input', function() {
        if (isCodeValidated) {
            isCodeValidated = false;
            $('#validateCodeBtn')
                .prop('disabled', false)
                .html('<i class="material-icons opacity-10 me-1">check_circle</i> Validar Código')
                .removeClass('btn-outline-success')
                .addClass('btn-success');
        }
        checkFormCompletion();
    });
    
    // Interceptar el envío del formulario
    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        
        const personaId = $('#persona_id').val();
        const email = $('#email').val();
        const password = $('#password').val();
        const passwordConfirm = $('#password_confirmation').val();
        const roleName = $('#role_name').val();
        
        if (!personaId || !email || !password || !passwordConfirm || !roleName) {
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Por favor complete todos los campos obligatorios.',
                confirmButtonText: 'Entendido'
            });
            return false;
        }
        
        if (!isCodeSent) {
            Swal.fire({
                icon: 'error',
                title: 'Código no enviado',
                text: 'Debe enviar y validar el código de verificación antes de guardar.',
                confirmButtonText: 'Entendido'
            });
            return false;
        }
        
        if (!isCodeValidated) {
            Swal.fire({
                icon: 'error',
                title: 'Código no validado',
                text: 'Por favor valide el código de verificación antes de guardar.',
                confirmButtonText: 'Entendido'
            });
            return false;
        }
        
        if (password !== passwordConfirm) {
            Swal.fire({
                icon: 'error',
                title: 'Contraseñas no coinciden',
                text: 'Las contraseñas ingresadas no coinciden.',
                confirmButtonText: 'Entendido'
            });
            return false;
        }
        
        Swal.fire({
            title: '¿Crear usuario?',
            html: `El usuario será creado con:<br>
                   <strong>Contraseña inicial:</strong> ${currentDocumento || 'Cédula de la persona'}<br>
                   <small class="text-muted">El usuario deberá cambiar su contraseña en su primer acceso.</small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, crear usuario',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#submitBtn').prop('disabled', true).html('<i class="material-icons opacity-10 me-1">hourglass_empty</i> Guardando...');
                $(this).off('submit').submit();
            }
        });
    });
    
    // Cargar datos si hay valores antiguos
    @if(old('persona_id'))
        loadPersonaData('{{ old('persona_id') }}');
    @endif
    
    @if(session('verification_sent'))
        $('#verificationCodeGroup').show();
        isCodeSent = true;
        isCodeValidated = false;
        checkFormCompletion();
    @endif
});
</script>
@endpush