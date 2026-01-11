@extends('layouts.material')

@section('title', 'Crear Nuevo Rol')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">
                            <i class="material-icons opacity-10 me-2">add_circle</i>
                            Crear Nuevo Rol
                        </h6>
                        <p class="text-white text-sm ps-3 mb-0">Paso 1: Asigne un nombre al rol</p>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.roles.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group input-group-outline mb-4">
                                    <label class="form-label">Nombre del Rol *</label>
                                    <input type="text" name="name" class="form-control" 
                                           value="{{ old('name') }}" required
                                           placeholder="Ej: auditor, analista, gerente">
                                </div>
                                @error('name')
                                <div class="text-danger text-xs mb-3">{{ $message }}</div>
                                @enderror
                                
                                <div class="alert alert-info text-white">
                                    <div class="d-flex align-items-center">
                                        <i class="material-icons me-2">info</i>
                                        <span class="text-sm">
                                            <strong>Paso 1 de 2:</strong> Primero cree el rol, luego podrá asignar los permisos.
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dashboard.roles.index') }}" class="btn btn-secondary">
                                <i class="material-icons text-sm">arrow_back</i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons text-sm">navigate_next</i> Siguiente: Asignar Permisos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection