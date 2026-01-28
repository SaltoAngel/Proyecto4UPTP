@extends('layouts.material')

@section('title', 'Gestión de roles - ' . config('app.name'))

@push('styles')
<style>
    /* Diseño de botones de acción */
    .acciones-rol .btn { 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        width: 35px; 
        height: 35px; 
        padding: 0; 
        border-radius: 0; 
        border: 1px solid rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }
    .acciones-rol .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }
    .acciones-rol .btn .material-icons { font-size: 18px; }
    .acciones-rol .btn:first-child { border-top-left-radius: .4rem; border-bottom-left-radius: .4rem; }
    .acciones-rol .btn:last-child { border-top-right-radius: .4rem; border-bottom-right-radius: .4rem; }
    
    /* Z-index para que la modal no se quede atrás */
    .modal { z-index: 1060 !important; }
    .modal-backdrop { z-index: 1050 !important; }

    /* Estilo para los inputs */
    .form-group label { display: block; margin-bottom: 5px; color: #7b809a; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; }
    .form-control.border { border: 1px solid #d2d6da !important; padding: 10px 15px !important; border-radius: 0.5rem !important; }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0"><i class="material-icons me-2 text-primary">admin_panel_settings</i>Módulo de Roles</h2>
                <p class="text-muted small">Gestión de accesos y estados del sistema</p>
            </div>
            @can('create roles')
                <button type="button" class="btn btn-success" id="btnNuevoRol">
                    <i class="material-icons me-2">add_circle</i>Nuevo Rol
                </button>
            @endcan
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-items-center mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Nombre del Rol</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Permisos</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Usuarios</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Tipo</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Estado</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td class="ps-4">
                            <h6 class="mb-0 text-sm {{ $role->isProtectedRole() ? 'text-danger' : '' }}">{{ $role->name }}</h6>
                        </td>
                        <td class="text-center"><span class="badge bg-info">{{ $role->permissions_count }}</span></td>
                        <td class="text-center"><span class="badge bg-warning text-dark">{{ $role->users_count }}</span></td>
                        <td class="text-center">
                            <span class="text-xs font-weight-bold {{ $role->isProtectedRole() ? 'text-danger' : 'text-success' }}">
                                {{ $role->isProtectedRole() ? 'SISTEMA' : 'MANUAL' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $role->trashed() ? 'bg-secondary' : 'bg-success' }} text-xxs">
                                {{ $role->trashed() ? 'Inactivo' : 'Activo' }}
                            </span>
                        </td>
                        <td class="align-middle text-center acciones-rol">
                            <button type="button" class="btn btn-info btn-ver-rol" data-role-id="{{ $role->id }}" title="Detalles">
                                <i class="material-icons">visibility</i>
                            </button>

                            @if(!$role->isProtectedRole())
                                @if(!$role->trashed())
                                    <button type="button" class="btn btn-warning btn-editar-rol" data-role-id="{{ $role->id }}" title="Editar">
                                        <i class="material-icons">edit</i>
                                    </button>

                                    <form action="{{ route('dashboard.roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Deshabilitar rol?')">
                                            <i class="material-icons">block</i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('dashboard.roles.restore', $role->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Restaurar">
                                            <i class="material-icons">settings_backup_restore</i>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <button class="btn btn-light" disabled><i class="material-icons text-muted">lock</i></button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- LAS MODALES SIEMPRE AL FINAL DEL CONTENT --}}
@foreach($roles as $role)
    @include('dashboard.roles.modals.show')
    @include('dashboard.roles.modals.edit')
@endforeach
@include('dashboard.roles.modals.create')

@endsection

{{-- En la sección @push('scripts') del index.blade.php --}}
@push('scripts')
<script>
// Inicializar todas las modales cuando se cargue la página
document.addEventListener('DOMContentLoaded', function() {
    // Botón para abrir modal de nuevo rol
    const btnNuevoRol = document.getElementById('btnNuevoRol');
    if (btnNuevoRol) {
        btnNuevoRol.addEventListener('click', function() {
            // Resetear el formulario de creación cada vez que se abre
            resetForm('formCreate', 'masterSelectCreate');
            const modal = new bootstrap.Modal(document.getElementById('crearRolModal'));
            modal.show();
        });
    }

    // Botones para ver detalles de rol
    document.querySelectorAll('.btn-ver-rol').forEach(btn => {
        btn.addEventListener('click', function() {
            const roleId = this.getAttribute('data-role-id');
            const modal = new bootstrap.Modal(document.getElementById('showRolModal' + roleId));
            modal.show();
        });
    });

    // Botones para editar rol
    document.querySelectorAll('.btn-editar-rol').forEach(btn => {
        btn.addEventListener('click', function() {
            const roleId = this.getAttribute('data-role-id');
            const modal = new bootstrap.Modal(document.getElementById('editRolModal' + roleId));
            modal.show();
        });
    });
});

/**
 * FUNCIONES GLOBALES (Para que los botones de las modales funcionen)
 */
window.toggleAll = function(formId, masterSwitch) {
    const form = document.getElementById(formId);
    if (!form) return;
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => cb.checked = masterSwitch.checked);
}

window.resetForm = function(formId, masterSwitchId) {
    const form = document.getElementById(formId);
    if (!form) return;
    form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    const master = document.getElementById(masterSwitchId);
    if (master) master.checked = false;
    
    // Limpiar también el campo de nombre en el formulario de creación
    if (formId === 'formCreate') {
        const nameInput = form.querySelector('input[name="name"]');
        if (nameInput) nameInput.value = '';
    }
}

window.toggleModule = function(btn) {
    const container = btn.closest('.card').querySelector('.module-container');
    const checkboxes = container.querySelectorAll('input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}
</script>
@endpush