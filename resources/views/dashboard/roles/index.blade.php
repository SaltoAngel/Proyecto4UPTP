@extends('layouts.material')

@section('title', 'Gestión de Roles')

@section('content')
<div class="container-fluid px-0">
    <div class="row">
        <div class="col-12 mx-auto">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="text-white text-capitalize ps-3 mb-0">
                                    <i class="material-icons opacity-10 me-2">admin_panel_settings</i>
                                    Roles del Sistema
                                </h6>
                                <p class="text-white text-sm ps-3 mb-0">Administra los roles y sus permisos</p>
                            </div>
                            <div class="col-4 text-end">
                                @can('create roles')
                                <a href="{{ route('dashboard.roles.create') }}" 
                                   class="btn btn-sm btn-dark mb-0">
                                    <i class="material-icons text-sm">add</i> Nuevo Rol
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body px-0 pb-2">
                    <!-- Mensajes -->
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible text-white mx-3" role="alert">
                        <span class="text-sm">{{ session('success') }}</span>
                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Tabla de roles -->
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rol</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Permisos</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Usuarios</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tipo</th>
                                    <th class="text-secondary opacity-7 text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm @if($role->isProtectedRole()) text-danger @else text-primary @endif">
                                                    {{ $role->name }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-info">{{ $role->permissions_count }}</span>
                                        @if($role->permissions_count > 0 && !$role->isProtectedRole())
                                        <a href="{{ route('dashboard.roles.assign-permissions', $role) }}" 
                                           class="btn btn-xs btn-link p-0 ms-1"
                                           data-bs-toggle="tooltip" 
                                           title="Editar permisos">
                                            <i class="material-icons text-xs text-warning">edit</i>
                                        </a>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-warning">{{ $role->users_count }}</span>
                                    </td>
                                    <td>
                                        @if($role->isProtectedRole())
                                        <span class="badge badge-sm bg-gradient-danger">
                                            <i class="material-icons text-xs">lock</i> Sistema
                                        </span>
                                        @else
                                        <span class="badge badge-sm bg-gradient-success">
                                            <i class="material-icons text-xs">edit</i> Personalizado
                                        </span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('dashboard.roles.show', $role) }}" 
                                               class="btn btn-xs btn-outline-info px-3 mb-0 me-1"
                                               data-bs-toggle="tooltip" title="Ver detalles">
                                                <i class="material-icons text-sm">visibility</i>
                                            </a>
                                            
                                            @can('edit roles')
                                            @if(!$role->isProtectedRole())
                                            <a href="{{ route('dashboard.roles.edit', $role) }}" 
                                               class="btn btn-xs btn-outline-warning px-3 mb-0 me-1"
                                               data-bs-toggle="tooltip" title="Editar nombre">
                                                <i class="material-icons text-sm">edit</i>
                                            </a>
                                            
                                            <!-- Botón para asignar/editar permisos -->
                                            <a href="{{ route('dashboard.roles.assign-permissions', $role) }}" 
                                               class="btn btn-xs btn-outline-primary px-3 mb-0 me-1"
                                               data-bs-toggle="tooltip" title="Asignar permisos">
                                                <i class="material-icons text-sm">key</i>
                                            </a>
                                            @endif
                                            @endcan
                                            
                                            @can('delete roles')
                                            @if(!$role->isProtectedRole() && $role->users_count == 0)
                                            <form action="{{ route('dashboard.roles.destroy', $role) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-xs btn-outline-danger px-3 mb-0"
                                                        data-bs-toggle="tooltip" 
                                                        title="Eliminar rol"
                                                        onclick="return confirm('¿Eliminar el rol \"{{ $role->name }}\"?')">
                                                    <i class="material-icons text-sm">delete</i>
                                                </button>
                                            </form>
                                            @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-4 px-3">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltips.map(function(tooltip) {
            return new bootstrap.Tooltip(tooltip);
        });
    });
</script>
@endpush