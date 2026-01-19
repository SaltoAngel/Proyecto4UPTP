@extends('layouts.material')

@section('title', 'Registrar Nuevo Usuario')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-link text-dark p-0 me-3" onclick="window.history.back()">
                            <i class="material-icons opacity-10">arrow_back</i>
                        </button>
                        <div>
                            <h5 class="mb-0">
                                <i class="material-icons opacity-10 me-2">person_add</i>
                                Registrar Nuevo Usuario
                            </h5>
                            <p class="text-sm mb-0">Complete el formulario para crear un nuevo usuario</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
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
                                    <label for="persona_id" class="form-label">Persona *</label>
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
                                    <label for="email" class="form-label">Correo electrónico *</label>
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
                                    <label for="verification_code" class="form-label">Código de verificación *</label>
                                    <div class="input-group input-group-outline">
                                        <input type="text" 
                                               class="form-control @error('verification_code') is-invalid @enderror" 
                                               id="verification_code" 
                                               name="verification_code" 
                                               placeholder="Ingrese el código de 8 dígitos"
                                               maxlength="8"
                                               value="{{ old('verification_code') }}">
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

                        <!-- Contraseña y Confirmar Contraseña -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">Contraseña *</label>
                                    <div class="input-group input-group-outline">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               value="{{ old('password') }}" 
                                               required
                                               minlength="8">
                                    </div>
                                    @error('password')
                                        <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="lengthCheck" disabled>
                                            <label class="form-check-label" for="lengthCheck">Mínimo 8 caracteres</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="uppercaseCheck" disabled>
                                            <label class="form-check-label" for="uppercaseCheck">Al menos una mayúscula</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="numberCheck" disabled>
                                            <label class="form-check-label" for="numberCheck">Al menos un número</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Confirmar contraseña *</label>
                                    <div class="input-group input-group-outline">
                                        <input type="password" 
                                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               required>
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
                                                <strong>Nota:</strong> La contraseña debe cumplir con los requisitos de seguridad.
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

                        <!-- Rol -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role_id" class="form-label">Rol *</label>
                                    <div class="input-group input-group-outline">
                                        <select class="form-control @error('role_id') is-invalid @enderror" 
                                                id="role_id" 
                                                name="role_id" 
                                                required>
                                            <option value="">– Seleccione un rol –</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('role_id')
                                        <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-end">
                                <a href="{{ route('users.user') }}" class="btn btn-outline-secondary me-2">
                                    <i class="material-icons opacity-10 me-1">cancel</i> Cancelar
                                </a>
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
</div>
@endsection

@push('scripts')
<script>
// Variable para controlar si el código fue validado
let isCodeValidated = false;

function loadPersonaData(personaId) {
    if (!personaId) {
        $('#email').val('');
        $('#sendCodeBtn').prop('disabled', true);
        $('#verificationCodeGroup').hide();
        resetForm();
        return;
    }

    const selectedOption = $(`#persona_id option[value="${personaId}"]`);
    const email = selectedOption.data('email');
    
    $('#email').val(email || '');
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
            text: 'Primero debe seleccionar una persona.'
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
    $('#verificationCodeGroup').hide();
    $('#verification_code').val('');
    $('#password').val('');
    $('#password_confirmation').val('');
    $('#validateCodeBtn')
        .prop('disabled', false)
        .html('<i class="material-icons opacity-10 me-1">check_circle</i> Validar Código')
        .removeClass('btn-outline-success')
        .addClass('btn-success');
    $('#submitBtn').prop('disabled', true);
}

// Función para verificar el estado de validación
function checkVerificationStatus() {
    const email = document.getElementById('email').value;
    const personaId = document.getElementById('persona_id').value;
    
    if (!email || !personaId) return;
    
    fetch('/check-verification-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            email: email,
            persona_id: personaId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.verified) {
            // Habilitar botón de guardar
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('submitBtn').classList.remove('disabled');
            
            // Mostrar mensaje de verificación exitosa
            const statusElement = document.getElementById('verificationStatus');
            if (statusElement) {
                statusElement.innerHTML = '<span class="text-success">✓ CÓDIGO VALIDADO</span>';
                statusElement.style.display = 'block';
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

// Verificar cada 3 segundos después de validar el código
let verificationCheckInterval = null;

function startVerificationCheck() {
    if (verificationCheckInterval) {
        clearInterval(verificationCheckInterval);
    }
    verificationCheckInterval = setInterval(checkVerificationStatus, 3000);
}

// Iniciar la verificación cuando se valide el código
document.getElementById('validateCodeBtn').addEventListener('click', function() {
    // Después de validar el código exitosamente
    startVerificationCheck();
});

// También verificar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    checkVerificationStatus();
});

function validatePassword(password) {
    const hasMinLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    
    $('#lengthCheck').prop('checked', hasMinLength);
    $('#uppercaseCheck').prop('checked', hasUppercase);
    $('#numberCheck').prop('checked', hasNumber);
    
    return hasMinLength && hasUppercase && hasNumber;
}

function checkFormCompletion() {
    const personaId = $('#persona_id').val();
    const email = $('#email').val();
    const password = $('#password').val();
    const passwordConfirm = $('#password_confirmation').val();
    const roleId = $('#role_id').val();
    const isCodeGroupVisible = $('#verificationCodeGroup').is(':visible');
    
    let isValid = personaId && email && roleId && validatePassword(password);
    
    // Validar confirmación de contraseña
    if (password && passwordConfirm && password !== passwordConfirm) {
        isValid = false;
    }
    
    // Si se envió el código, debe estar validado
    if (isCodeGroupVisible && !isCodeValidated) {
        isValid = false;
    }
    
    $('#submitBtn').prop('disabled', !isValid);
}

$(document).ready(function() {
    // Validación de contraseña en tiempo real
    $('#password').on('input', function() {
        validatePassword($(this).val());
        checkFormCompletion();
    });
    
    $('#password_confirmation').on('input', checkFormCompletion);
    $('#role_id, #persona_id').on('change', checkFormCompletion);
    $('#verification_code').on('input', function() {
        // Resetear validación si el código cambia
        if (isCodeValidated) {
            isCodeValidated = false;
            $('#validateCodeBtn')
                .prop('disabled', false)
                .html('<i class="material-icons opacity-10 me-1">check_circle</i> Validar Código')
                .removeClass('btn-outline-success')
                .addClass('btn-success');
            checkFormCompletion();
        }
    });
    
    // Validar formulario antes de enviar
    $('#userForm').on('submit', function(e) {
        const isCodeGroupVisible = $('#verificationCodeGroup').is(':visible');
        
        if (isCodeGroupVisible && !isCodeValidated) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Código no validado',
                text: 'Por favor valide el código de verificación antes de continuar.',
                confirmButtonText: 'Entendido'
            });
            return;
        }
        
        const password = $('#password').val();
        const passwordConfirm = $('#password_confirmation').val();
        
        if (password !== passwordConfirm) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Contraseñas no coinciden',
                text: 'Las contraseñas ingresadas no coinciden.',
                confirmButtonText: 'Entendido'
            });
        }
    });
    
    // Cargar datos si hay valores antiguos
    @if(old('persona_id'))
        loadPersonaData('{{ old('persona_id') }}');
    @endif
    
    @if(session('verification_sent'))
        $('#verificationCodeGroup').show();
        // Si ya se envió el código, permitir validarlo
        isCodeValidated = false;
        $('#submitBtn').prop('disabled', true);
    @endif
});
</script>
@endpush

@push('styles')
<style>
    .input-group-outline .input-group-text {
        border: 1px solid #d2d6da;
        border-left: 0;
        background-color: #f8f9fa;
    }
    .input-group-outline:focus-within .input-group-text {
        border-color: #198754;
        background-color: #fff;
    }
    .alert {
        border: 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .alert .alert-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 0.375rem;
        background-color: rgba(255, 255, 255, 0.2);
    }
    .form-label {
        font-weight: 600;
        color: #344767;
    }
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
    .form-check-input:disabled {
        background-color: #e9ecef;
    }
</style>
@endpush