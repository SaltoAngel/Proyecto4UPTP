@extends('layouts.material')

@section('title', 'Editar Rol: ' . $role->name)

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-warning shadow-warning border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">
                            <i class="material-icons opacity-10 me-2">edit</i>
                            Editar Rol
                        </h6>
                        <p class="text-white text-sm ps-3 mb-0">Actualice el nombre del rol</p>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-12">
                                <!-- Información actual -->
                                <div class="alert alert-info text-white mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="material-icons me-2">info</i>
                                        <div>
                                            <strong>Rol actual:</strong> {{ $role->name }}
                                            <br>
                                            <small>Tiene {{ $role->permissions->count() }} permisos asignados</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campo para editar nombre -->
                                <div class="input-group input-group-outline mb-4">
                                    <label class="form-label">Nuevo Nombre del Rol *</label>
                                    <input type="text" name="name" class="form-control" 
                                           value="{{ old('name', $role->name) }}" required
                                           placeholder="Ingrese el nuevo nombre">
                                </div>
                                
                                @error('name')
                                <div class="text-danger text-xs mb-3">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('dashboard.roles.index') }}" class="btn btn-secondary me-2">
                                            <i class="material-icons text-sm">arrow_back</i> Cancelar
                                        </a>
                                        <a href="{{ route('dashboard.roles.assign-permissions', $role) }}" 
                                           class="btn btn-primary">
                                            <i class="material-icons text-sm">key</i> Editar Permisos
                                        </a>
                                    </div>
                                    
                                    <div>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="material-icons text-sm">save</i> Guardar Cambios
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Información adicional -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-frame">
                                <div class="card-body p-3">
                                    <h6 class="mb-2">Opciones disponibles:</h6>
                                    <div class="list-group list-group-flush">
                                        <a href="{{ route('dashboard.roles.assign-permissions', $role) }}" 
                                           class="list-group-item list-group-item-action border-0 px-0 py-2">
                                            <div class="d-flex align-items-center">
                                                <i class="material-icons text-primary me-3">key</i>
                                                <div>
                                                    <h6 class="mb-0 text-sm">Editar Permisos</h6>
                                                    <p class="text-xs text-secondary mb-0">Agregar o quitar permisos a este rol</p>
                                                </div>
                                                <i class="material-icons text-secondary ms-auto">chevron_right</i>
                                            </div>
                                        </a>
                                        
                                        @if($role->users()->count() > 0)
                                        <div class="list-group-item border-0 px-0 py-2">
                                            <div class="d-flex align-items-center">
                                                <i class="material-icons text-success me-3">people</i>
                                                <div>
                                                    <h6 class="mb-0 text-sm">Usuarios con este rol</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $role->users()->count() }} usuarios asignados</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <a href="{{ route('dashboard.roles.show', $role) }}" 
                                           class="list-group-item list-group-item-action border-0 px-0 py-2">
                                            <div class="d-flex align-items-center">
                                                <i class="material-icons text-info me-3">visibility</i>
                                                <div>
                                                    <h6 class="mb-0 text-sm">Ver Detalles Completos</h6>
                                                    <p class="text-xs text-secondary mb-0">Ver todos los permisos y usuarios</p>
                                                </div>
                                                <i class="material-icons text-secondary ms-auto">chevron_right</i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection