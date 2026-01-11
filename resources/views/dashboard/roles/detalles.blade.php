@extends('layouts.material')

@section('title', 'Detalle Rol: ' . $role->name)

@section('content')
<div class="container-fluid px-0">
    <div class="row">
        <div class="col-md-8">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="text-white text-capitalize ps-3 mb-0">
                                    <i class="material-icons opacity-10 me-2">admin_panel_settings</i>
                                    Detalles del Rol
                                </h6>
                                <p class="text-white text-sm ps-3 mb-0">{{ $role->name }}</p>
                            </div>
                            <div class="col-4 text-end">
                                @if(!$role->isProtectedRole())
                                <a href="{{ route('dashboard.roles.assign-permissions', $role) }}" 
                                   class="btn btn-sm btn-warning mb-0">
                                    <i class="material-icons text-sm">key</i> Editar Permisos
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información básica -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card card-frame">
                                <div class="card-body">
                                    <h6 class="card-title">Información del Rol</h6>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0 py-2">
                                            <strong>Nombre:</strong>
                                            <span class="float-end">
                                                <span class="badge bg-primary">{{ $role->name }}</span>
                                            </span>
                                        </li>
                                        <li class="list-group-item px-0 py-2">
                                            <strong>Guard:</strong>
                                            <span class="float-end">{{ $role->guard_name }}</span>
                                        </li>
                                        <li class="list-group-item px-0 py-2">
                                            <strong>Tipo:</strong>
                                            <span class="float-end">
                                                @if($role->isProtectedRole())
                                                <span class="badge bg-danger">Rol del Sistema</span>
                                                @else
                                                <span class="badge bg-success">Rol Personalizado</span>
                                                @endif
                                            </span>
                                        </li>
                                        <li class="list-group-item px-0 py-2">
                                            <strong>Creado:</strong>
                                            <span class="float-end">{{ $role->created_at->format('d/m/Y H:i') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-frame">
                                <div class="card-body">
                                    <h6 class="card-title">Estadísticas</h6>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Permisos</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    <span class="text-success">{{ $role->permissions->count() }}</span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Usuarios</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    <span class="text-warning">{{ $role->users->count() }}</span>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permisos asignados -->
                    <div class="card">
                        <div class="card-header">
                            <h6>Permisos Asignados</h6>
                            <p class="text-sm text-secondary mb-0">Total: {{ $role->permissions->count() }} permisos</p>
                        </div>
                        <div class="card-body">
                            <div class="