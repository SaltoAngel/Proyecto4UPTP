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
                                               value="{{ $user->persona->nombres }} {{ $user->persona->apellidos }} - {{ $user->persona->documento }}"
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

                        <!-- Estado -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">Estado *</label>
                                    <div class="input-group input-group-outline">
                                        <select class="form-control @error('status') is-invalid @enderror" 
                                                id="status" 
                                                name="status" 
                                                required>
                                            <option value="pendiente" {{ $user->status == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="activo" {{ $user->status == 'activo' ? 'selected' : '' }}>Activo</option>
                                            <option value="inactivo" {{ $user->status == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                        </select>
                                    </div>
                                    @error('status')
                                        <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Rol -->
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
                                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
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
                                                <p class="text-sm">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="text-sm mb-1"><strong>Última actualización:</strong></p>
                                                <p class="text-sm">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="text-sm mb-1"><strong>Último acceso:</strong></p>
                                                <p class="text-sm">
                                                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                                                </p>
                                            </div>
                                        </div>
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
</style>
@endpush