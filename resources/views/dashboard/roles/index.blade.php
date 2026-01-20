@extends('layouts.material')

@section('title', 'Gestión de roles - ' . config('app.name'))

@push('styles')
<style>
    /* Estilo idéntico a Personas para agrupar botones */
    .acciones-rol .btn { 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        width: 35px; 
        height: 35px; 
        padding: 0; 
    }
    .acciones-rol .btn .material-icons { font-size: 18px; }
    .acciones-rol .btn + .btn { margin-left: -1px; }
    .acciones-rol .btn + form .btn, .acciones-rol form + .btn { margin-left: -1px; }
    .acciones-rol form { display: inline-block; margin: 0; }
    .acciones-rol .btn { border-radius: 0; border: 1px solid rgba(0,0,0,0.1); }
    .acciones-rol .btn:first-child { border-top-left-radius: .4rem; border-bottom-left-radius: .4rem; }
    .acciones-rol .btn:last-child { border-top-right-radius: .4rem; border-bottom-right-radius: .4rem; }
    
    .badge-status { width: 80px; font-size: 0.7rem; }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">
                    <i class="material-icons me-2 text-primary">admin_panel_settings</i>Módulo de Roles
                </h2>
                <p class="text-muted small">Gestión de accesos y estados del sistema</p>
            </div>
            @can('create roles')
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearRolModal">
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
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nombre del Rol</th>
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
                            <div class="d-flex align-items-center">
                                <h6 class="mb-0 text-sm @if($role->isProtectedRole()) text-danger @endif">
                                    {{ $role->name }}
                                </h6>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $role->permissions_count }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark">{{ $role->users_count }}</span>
                        </td>
                        <td class="text-center">
                            @if($role->isProtectedRole())
                                <span class="text-xs font-weight-bold text-danger">SISTEMA</span>
                            @else
                                <span class="text-xs font-weight-bold text-success">MANUAL</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{-- STATUS: Verde si está activo, Gris si está borrado (trashed) --}}
                            <span class="badge badge-status {{ $role->trashed() ? 'bg-secondary' : 'bg-success' }}">
                                {{ $role->trashed() ? 'Inactivo' : 'Activo' }}
                            </span>
                        </td>
                        <td class="align-middle text-center acciones-rol">

                            {{-- Botón Ver con tu estilo original corregido --}}
                                    <button type="button" 
                                            class="btn btn-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#showRolModal{{ $role->id }}" 
                                            title="Detalles">
                                        <i class="material-icons">visibility</i>
                                    </button>

                            @if(!$role->isProtectedRole())
                                @if(!$role->trashed())
                                    {{-- Botón Editar: Ahora dispara la modal mediante ID único --}}
                                    <button type="button" class="btn btn-warning" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editRolModal{{ $role->id }}" 
                                            title="Editar Rol y Permisos">
                                        <i class="material-icons">edit</i>
                                    </button>

                                     {{-- Para desabilitar --}}
                                    <form action="{{ route('dashboard.roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Deshabilitar rol?')">
                                            <i class="material-icons">block</i>
                                        </button>
                                    </form>
                                @else
                                    {{-- Botón Restaurar si está Inactivo --}}
                                    <form action="{{ route('dashboard.roles.restore', $role->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Restaurar">
                                            <i class="material-icons">settings_backup_restore</i>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <button class="btn btn-light" disabled title="Protegido">
                                    <i class="material-icons text-muted">lock</i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 1. AQUÍ VAN LAS MODALES DE EDICIÓN --}}
{{-- Necesitamos recorrer los roles otra vez para generar una modal por cada uno --}}
@foreach($roles as $role)
    @if(!$role->isProtectedRole() && !$role->trashed())
        @include('dashboard.roles.modals.show')
        @include('dashboard.roles.modals.edit', ['role' => $role])
    @endif
    @include('dashboard.roles.modals.create')
@endforeach

<script>
/**
 * Función para el Switch Maestro (Seleccionar todo el formulario)
 * Marca o desmarca absolutamente todos los permisos del rol.
 */
function toggleAll(formId, masterSwitch) {
    const form = document.getElementById(formId);
    const checkboxes = form.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(cb => {
        cb.checked = masterSwitch.checked;
    });
}

/**
 * Función para Limpiar todo el formulario
 * Desmarca todos los permisos y apaga el switch maestro.
 */
function resetForm(formId, masterSwitchId) {
    const form = document.getElementById(formId);
    const checkboxes = form.querySelectorAll('input[name="permissions[]"]');
    checkboxes.forEach(cb => {
        cb.checked = false;
    });
    // Apagar el switch maestro también
    const masterSwitch = document.getElementById(masterSwitchId);
    if (masterSwitch) masterSwitch.checked = false;
}

/**
 * Función para los botones "Todo" de cada módulo individual
 * Selecciona o deselecciona solo los permisos dentro de ese cuadro gris.
 */
function toggleModule(btn) {
    // Busca el contenedor de la tarjeta más cercano y luego su cuerpo (donde están los checks)
    const container = btn.closest('.card').querySelector('.module-container');
    const checkboxes = container.querySelectorAll('input[type="checkbox"]');
    
    // Si todos están marcados, los desmarca. Si falta alguno, los marca todos.
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inicializar todas las modales de Bootstrap (por si acaso)
    var modalEls = document.querySelectorAll('.modal');
    modalEls.forEach(function(modalEl) {
        new bootstrap.Modal(modalEl);
    });

    // Asegurar que los Tabs funcionen dentro de las modales
    var triggerTabList = [].slice.call(document.querySelectorAll('.nav-pills a'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    });
});

</script>

@endsection