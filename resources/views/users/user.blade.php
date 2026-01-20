@extends('layouts.material')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">
                            <i class="material-icons opacity-10 me-2">person</i>
                            Gestión de Usuarios
                        </h6>
                        <p class="text-sm mb-0">Administre los usuarios del sistema</p>
                    </div>
                    <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary d-flex align-items-center">
                        <i class="material-icons opacity-10 me-1" style="font-size: 18px">person_add</i>
                        Registrar Nuevo Usuario
                    </a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Filtros y Búsqueda -->
                    <div class="px-4 pt-4">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="input-group input-group-outline">
                                    <label class="form-label">Buscar usuario...</label>
                                    <input type="text" id="search-input" class="form-control" 
                                           placeholder="Buscar por nombre, email o documento..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="form-select px-3" id="status-filter">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="form-select px-3" id="role-filter">
                                    <option value="">Todos los roles</option>
                                    @foreach($roles ?? [] as $role)
                                        <option value="{{ $role->id }}" {{ request('rol') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3 d-flex align-items-end">
                                <button class="btn btn-outline-primary btn-sm w-100" onclick="applyFilters()">
                                    <i class="material-icons opacity-10 me-1" style="font-size: 18px">search</i>
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Usuarios -->
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Usuario</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Documento</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rol</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estado</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Último acceso</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <div class="avatar avatar-sm me-3">
                                                    <span class="avatar-initial rounded-circle bg-gradient-primary">
                                                        {{ strtoupper(substr($user->persona->nombres, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $user->persona->nombres }} {{ $user->persona->apellidos }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $user->persona->documento }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-primary">
                                            {{ $user->roles->first()->name ?? 'Sin rol' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->status == 'activo')
                                            <span class="badge badge-sm bg-gradient-success">Activo</span>
                                        @elseif($user->status == 'pendiente')
                                            <span class="badge badge-sm bg-gradient-warning">Pendiente</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">
                                            {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                                        </p>
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.show', $user->id) }}" 
                                               class="btn btn-link text-info px-2 mb-0" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-original-title="Ver detalles">
                                                <i class="material-icons opacity-10">visibility</i>
                                            </a>
                                            <a href="{{ route('users.edit', $user->id) }}" 
                                               class="btn btn-link text-warning px-2 mb-0" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-original-title="Editar">
                                                <i class="material-icons opacity-10">edit</i>
                                            </a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-link text-danger px-2 mb-0" 
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-original-title="Eliminar"
                                                        onclick="return confirm('¿Está seguro de eliminar este usuario?')">
                                                    <i class="material-icons opacity-10">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="material-icons opacity-10" style="font-size: 48px">person_off</i>
                                        <p class="text-sm text-secondary mt-2">No se encontraron usuarios</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($users->hasPages())
                    <div class="card-footer px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 text-sm">
                                    Mostrando {{ $users->firstItem() }} - {{ $users->lastItem() }} de {{ $users->total() }} registros
                                </p>
                            </div>
                            <div>
                                {{ $users->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Detalles de Usuario -->
<div class="modal fade" id="userDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="material-icons opacity-10 me-2">person</i>
                    Detalles del Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Los detalles se cargarán dinámicamente via AJAX -->
                <div id="user-detail-content">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table > :not(caption) > * > * {
        background-color: transparent !important;
    }
    .avatar-initial {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
    }
    .btn-group .btn-link {
        padding: 0.25rem 0.5rem;
    }
    .btn-group .btn-link:hover {
        background: rgba(0,0,0,0.05);
        border-radius: 0.375rem;
    }
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
    .card-footer {
        background-color: transparent;
        border-top: 1px solid rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Aplicar filtros
    window.applyFilters = function() {
        const search = document.getElementById('search-input').value;
        const status = document.getElementById('status-filter').value;
        const role = document.getElementById('role-filter').value;
        
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        
        if (search) params.set('search', search);
        else params.delete('search');
        
        if (status) params.set('estado', status);
        else params.delete('estado');
        
        if (role) params.set('rol', role);
        else params.delete('rol');
        
        params.delete('page'); // Resetear a página 1
        
        window.location.href = `${url.pathname}?${params.toString()}`;
    };

    // Buscar al presionar Enter
    document.getElementById('search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });

    // Cargar detalles del usuario via AJAX
    document.querySelectorAll('a[href*="users.show"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.getAttribute('href').split('/').pop();
            
            fetch(`/usuarios/${userId}`)
                .then(response => response.text())
                .then(html => {
                    const modal = new bootstrap.Modal(document.getElementById('userDetailModal'));
                    const content = document.getElementById('user-detail-content');
                    content.innerHTML = html;
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar los detalles del usuario'
                    });
                });
        });
    });
});
</script>
@endpush