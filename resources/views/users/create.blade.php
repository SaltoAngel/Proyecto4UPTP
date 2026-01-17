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
                                                <option value="{{ $persona->id }}" 
                                                        data-email="{{ $persona->email }}"
                                                        data-documento="{{ $persona->documento }}"
                                                        {{ old('persona_id') == $persona->id ? 'selected' : '' }}>
                                                    {{ $persona->nombre_completo }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('persona_id')
                                        <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Seleccione una persona que no tenga usuario asignado</small>
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
                                    <small class="text-muted">Este será su usuario para acceder al sistema</small>
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
                                               placeholder="Ingrese el código de 6 dígitos"
                                               maxlength="6"
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
                        </div>

                        <!-- Información de Contraseña -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-secondary" role="alert">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="material-icons opacity-10">info</i>
                                        </div>
                                        <div>
                                            <h6 class="alert-heading">Información de Contraseña</h6>
                                            <p class="mb-1">
                                                La contraseña se generará automáticamente con el número de documento de la persona.
                                            </p>
                                            <p class="mb-0">
                                                <strong>Contraseña generada:</strong> 
                                                <span id="generatedPassword" class="badge bg-gradient-primary">—</span>
                                            </p>
                                            <hr class="my-2">
                                            <p class="mb-0 text-sm">
                                                <i class="material-icons opacity-10 me-1" style="font-size: 16px">lock</i>
                                                El usuario deberá cambiar la contraseña en su primer inicio de sesión
                                            </p>
                                            <p class="mb-0 text-sm">
                                                <i class="material-icons opacity-10 me-1" style="font-size: 16px">hourglass_empty</i>
                                                Estado inicial: <span class="badge bg-gradient-warning">Pendiente</span>
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
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="material-icons opacity-10 me-1">cancel</i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
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
function loadPersonaData(personaId) {
    if (!personaId) {
        $('#email').val('');
        $('#sendCodeBtn').prop('disabled', true);
        $('#verificationCodeGroup').hide();
        $('#generatedPassword').text('—');
        return;
    }

    const selectedOption = $(`#persona_id option[value="${personaId}"]`);
    const email = selectedOption.data('email');
    const documento = selectedOption.data('documento');
    
    // Limpiar formato del documento para la contraseña
    const cleanDocumento = documento.replace(/[\.-]/g, '');
    
    $('#email').val(email);
    $('#generatedPassword').text(cleanDocumento);
    $('#sendCodeBtn').prop('disabled', false);
}

function sendVerificationCode() {
    const email = $('#email').val();
    
    if (!email) {
        Swal.fire({
            icon: 'warning',
            title: 'Seleccione una persona',
            text: 'Por favor seleccione una persona primero.'
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
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            btn.prop('disabled', false);
            btn.html('<i class="material-icons opacity-10">send</i> Enviar código');
            $('#verificationCodeGroup').show();
            $('#verification_code').focus();
            
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

function resendCode() {
    sendVerificationCode();
}

// Validar formulario antes de enviar
$(document).ready(function() {
    $('#userForm').on('submit', function(e) {
        const verificationCode = $('#verification_code').val();
        const email = $('#email').val();
        const personaId = $('#persona_id').val();
        const roleId = $('#role_id').val();

        if (!personaId || !email || !roleId) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Por favor complete todos los campos requeridos.',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // Si ya se envió el código, requerir verificación
        if ($('#verificationCodeGroup').is(':visible') && !verificationCode) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Código requerido',
                text: 'Por favor ingrese el código de verificación.',
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
</style>
@endpush