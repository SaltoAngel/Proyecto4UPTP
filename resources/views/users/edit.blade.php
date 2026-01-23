@extends('layouts.material')

@section('title', 'Editar Usuario')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('users.user') }}" class="btn btn-link text-dark p-0 me-3">
                            <i class="material-icons opacity-10">arrow_back</i>
                        </a>
                        <div>
                            <h5 class="mb-0">
                                <i class="material-icons opacity-10 me-2">edit</i>
                                Editar Usuario
                            </h5>
                            <p class="text-sm mb-0">{{ $user->email }}</p>
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

                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Información de la Persona (solo lectura) -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Persona</label>
                                    <div class="input-group input-group-outline">
                                        <input type="text" 
                                               class="form-control" 
                                               value="{{ optional($user->persona)->nombre_completo ?? (optional($user->persona)->nombres . ' ' . optional($user->persona)->apellidos) }} - {{ optional($user->persona)->documento ?? 'N/A' }}"
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
                                               value="{{ $user->email }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Solo Rol - Se eliminó el campo de estado -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role" class="form-label">Rol *</label>
                                    <div class="input-group input-group-outline">
                                        <select class="form-control @error('role') is-invalid @enderror" 
                                                id="role" 
                                                name="role" 
                                                required>
                                            <option value="">– Seleccione un rol –</option>
                                            @foreach($roles as $role)
                                                @php
                                                    // Obtener el nombre del rol actual del usuario
                                                    $currentRoleName = $user->roles->first()?->name;
                                                @endphp
                                                <option value="{{ $role->name }}" {{ old('role', $currentRoleName) == $role->name ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('role')
                                        <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Estado (solo visual, no editable) -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Estado Actual</label>
                                    <div class="input-group input-group-outline">
                                        @if($user->status == 'activo')
                                            <span class="badge bg-success py-2 px-3 w-100 text-center" style="font-size: 0.9rem;">
                                                <i class="material-icons opacity-10 me-1">check_circle</i> Activo
                                            </span>
                                        @elseif($user->status == 'pendiente')
                                            <span class="badge bg-warning py-2 px-3 w-100 text-center" style="font-size: 0.9rem;">
                                                <i class="material-icons opacity-10 me-1">pending</i> Pendiente
                                            </span>
                                        @else
                                            <span class="badge bg-danger py-2 px-3 w-100 text-center" style="font-size: 0.9rem;">
                                                <i class="material-icons opacity-10 me-1">block</i> Inactivo
                                            </span>
                                        @endif
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
                                            <div class="col-md-3">
                                                <p class="text-sm mb-1"><strong>Fecha de creación:</strong></p>
                                                <p class="text-sm">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="text-sm mb-1"><strong>Última actualización:</strong></p>
                                                <p class="text-sm">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="text-sm mb-1"><strong>Último acceso:</strong></p>
                                                <p class="text-sm">
                                                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="text-sm mb-1"><strong>Estado:</strong></p>
                                                <p class="text-sm">
                                                    @if($user->status == 'activo')
                                                        <span class="badge bg-success">Activo</span>
                                                    @elseif($user->status == 'pendiente')
                                                        <span class="badge bg-warning">Pendiente</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactivo</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        @if($user->roles->isNotEmpty())
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <p class="text-sm mb-1"><strong>Rol actual:</strong></p>
                                                @foreach($user->roles as $role)
                                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-end">
                                <a href="{{ route('users.user') }}" class="btn btn-outline-secondary me-2">
                                    <i class="material-icons opacity-10 me-1">cancel</i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="material-icons opacity-10 me-1">save</i> Guardar Cambios
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

@push('styles')
<style>
    .bg-gray-100 {
        background-color: #f8f9fa;
    }
    .card.bg-gray-100 {
        border: 1px solid #e9ecef;
    }
    .badge.py-2 {
        line-height: 1.5;
    }
</style>
@endpush
