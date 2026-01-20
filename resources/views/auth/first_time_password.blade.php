@extends('layouts.material')

@section('title', 'Cambio de Contraseña - Primer Acceso')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <h5 class="mb-0">
                                <i class="material-icons opacity-10 me-2">lock</i>
                                Cambio de Contraseña
                            </h5>
                            <p class="text-sm mb-0">Primer acceso - Complete el formulario para continuar</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="material-icons opacity-10">warning</i>
                            </div>
                            <div>
                                <h6 class="alert-heading">Primer acceso detectado</h6>
                                <p class="mb-0">Por seguridad, debe cambiar su contraseña antes de continuar.</p>
                            </div>
                        </div>
                    </div>

                    {{-- CAMBIO AQUÍ: Corregido de auth.first_time a password.first_time --}}
                    <form method="POST" action="{{ route('password.first_time') }}" id="passwordForm">
                        @csrf

                        <!-- Contraseña Actual (Contraseña Temporal) -->
                        <div class="form-group">
                            <label for="current_password" class="form-label">Contraseña Actual *</label>
                            <div class="input-group input-group-outline">
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password" 
                                       required
                                       placeholder="Ingrese su contraseña actual">
                                <span class="input-group-text">
                                    <button type="button" 
                                            class="btn btn-link text-secondary p-0" 
                                            onclick="togglePassword('current_password')">
                                        <i class="material-icons opacity-10" id="currentEyeIcon">visibility</i>
                                    </button>
                                </span>
                            </div>
                            @error('current_password')
                                <div class="text-danger text-sm mt-1">{{ $message }}</div>
                            @enderror
                            {{-- CAMBIO AQUÍ: Mensaje actualizado --}}
                            <small class="text-muted">
                                Ingrese la contraseña temporal que se le asignó al crear su usuario.
                            </small>
                        </div>

                        <!-- Nueva Contraseña -->
                        <div class="form-group mt-3">
                            <label for="password" class="form-label">Nueva Contraseña *</label>
                            <div class="input-group input-group-outline">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required
                                       placeholder="Mínimo 8 caracteres con mayúsculas y números">
                                <span class="input-group-text">
                                    <button type="button" 
                                            class="btn btn-link text-secondary p-0" 
                                            onclick="togglePassword('password')">
                                        <i class="material-icons opacity-10" id="passwordEyeIcon">visibility</i>
                                    </button>
                                </span>
                            </div>
                            @error('password')
                                <div class="text-danger text-sm mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="material-icons opacity-10 me-1" style="font-size: 14px">info</i>
                                Mínimo 8 caracteres ○ Al menos una mayúscula ○ Al menos un número
                            </small>
                        </div>

                        <!-- Confirmar Nueva Contraseña -->
                        <div class="form-group mt-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña *</label>
                            <div class="input-group input-group-outline">
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       placeholder="Repita la nueva contraseña">
                                <span class="input-group-text">
                                    <button type="button" 
                                            class="btn btn-link text-secondary p-0" 
                                            onclick="togglePassword('password_confirmation')">
                                        <i class="material-icons opacity-10" id="confirmEyeIcon">visibility</i>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- Indicador de Fortaleza -->
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-sm">Fortaleza de la contraseña:</span>
                                <span class="text-sm" id="strengthText">Muy débil</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" id="strengthBar" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Botón de Envío -->
                        <div class="form-group mt-4 text-center">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="material-icons opacity-10 me-2">lock_reset</i>
                                Guardar Nueva Contraseña
                            </button>
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
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + 'EyeIcon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}

// Validar fortaleza de contraseña
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    
    let strength = 0;
    let text = '';
    let color = 'bg-danger';
    
    // Validaciones
    if (password.length >= 8) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[a-z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    
    // Determinar texto y color
    if (strength === 0) {
        text = 'Muy débil';
        color = 'bg-danger';
    } else if (strength <= 50) {
        text = 'Débil';
        color = 'bg-warning';
    } else if (strength <= 75) {
        text = 'Buena';
        color = 'bg-info';
    } else {
        text = 'Fuerte';
        color = 'bg-success';
    }
    
    strengthBar.style.width = strength + '%';
    strengthBar.className = 'progress-bar ' + color;
    strengthText.textContent = text;
});

// Validación del formulario
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Las contraseñas no coinciden',
            text: 'Por favor verifique que ambas contraseñas sean iguales.',
            confirmButtonText: 'Entendido'
        });
        return false;
    }
    
    // Validar requisitos mínimos
    if (password.length < 8 || !/[A-Z]/.test(password) || !/[0-9]/.test(password)) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Contraseña no válida',
            text: 'La contraseña debe tener al menos 8 caracteres, una mayúscula y un número.',
            confirmButtonText: 'Entendido'
        });
        return false;
    }
    
    return true;
});
</script>
@endpush

@push('styles')
<style>
    .progress {
        background-color: #e9ecef;
        border-radius: 0.375rem;
        overflow: hidden;
    }
    .progress-bar {
        transition: width 0.3s ease;
        border-radius: 0.375rem;
    }
    .input-group-outline:focus-within .input-group-text {
        border-color: #198754;
        background-color: #fff;
    }
</style>
@endpush