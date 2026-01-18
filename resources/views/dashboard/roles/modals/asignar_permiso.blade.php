@extends('layouts.material')

@section('title', 'Asignar Permisos: ' . $role->name)

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="text-white text-capitalize ps-3 mb-0">
                                    <i class="material-icons opacity-10 me-2">key</i>
                                    Asignar Permisos
                                </h6>
                                <p class="text-white text-sm ps-3 mb-0">
                                    Rol: <strong>{{ $role->name }}</strong>
                                </p>
                            </div>
                            <div class="col-4 text-end">
                                <a href="{{ route('dashboard.roles.index') }}" 
                                   class="btn btn-sm btn-dark mb-0">
                                    <i class="material-icons text-sm">list</i> Ver Todos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible text-white mx-3" role="alert">
                        <span class="text-sm">{{ session('success') }}</span>
                        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('dashboard.roles.update-permissions', $role) }}" method="POST" id="permissionsForm">
                        @csrf
                        
                        <!-- Controles de selección -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-gradient-dark">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h6 class="text-white mb-0">Seleccione los permisos</h6>
                                                <p class="text-white text-sm mb-0">Agrupados por módulo del sistema</p>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <button type="button" class="btn btn-sm btn-light me-2" id="selectAll">
                                                    <i class="material-icons text-sm">check_box</i> Seleccionar Todos
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light" id="deselectAll">
                                                    <i class="material-icons text-sm">check_box_outline_blank</i> Limpiar
                                                </button>
                                                <span class="badge bg-primary ms-2" id="selectedCount">
                                                    {{ count($rolePermissions) }} seleccionados
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permisos agrupados por módulo -->
                        <div class="row">
                            @foreach($groupedPermissions as $module => $permissions)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-header pb-0 p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $module }}</h6>
                                            <div class="form-check form-check-inline m-0">
                                                <input class="form-check-input module-checkbox" 
                                                       type="checkbox" 
                                                       data-module="{{ $module }}"
                                                       id="module_{{ Str::slug($module) }}">
                                                <label class="form-check-label text-sm" for="module_{{ Str::slug($module) }}">
                                                    Todo
                                                </label>
                                            </div>
                                        </div>
                                        <p class="text-sm text-secondary mb-0">{{ count($permissions) }} permisos</p>
                                    </div>
                                    <div class="card-body p-3 pt-0">
                                        <div class="list-group list-group-flush">
                                            @foreach($permissions as $permission)
                                            <div class="list-group-item border-0 px-0 py-1">
                                                <div class="form-check">
                                                    <input class="form-check-input permission-checkbox" 
                                                           type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->id }}" 
                                                           id="perm_{{ $permission->id }}"
                                                           data-module="{{ $module }}"
                                                           {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-sm mb-0" for="perm_{{ $permission->id }}">
                                                        {{ ucfirst($permission->name) }}
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Botones -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('dashboard.roles.edit', $role) }}" class="btn btn-secondary">
                                        <i class="material-icons text-sm">arrow_back</i> Volver a Editar Rol
                                    </a>
                                    
                                    <div>
                                        <a href="{{ route('dashboard.roles.index') }}" class="btn btn-light me-2">
                                            <i class="material-icons text-sm">close</i> Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-success">
                                            <i class="material-icons text-sm">save</i> Guardar Permisos
                                        </button>
                                    </div>
                                </div>
                            </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        const moduleCheckboxes = document.querySelectorAll('.module-checkbox');
        const selectAllBtn = document.getElementById('selectAll');
        const deselectAllBtn = document.getElementById('deselectAll');
        const selectedCount = document.getElementById('selectedCount');
        const permissionsForm = document.getElementById('permissionsForm');

        // Actualizar contador
        function updateSelectedCount() {
            const selected = document.querySelectorAll('.permission-checkbox:checked').length;
            selectedCount.textContent = `${selected} seleccionados`;
        }

        // Seleccionar/deseleccionar módulo completo
        moduleCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const module = this.dataset.module;
                document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`)
                    .forEach(perm => perm.checked = this.checked);
                updateSelectedCount();
            });
        });

        // Actualizar checkbox de módulo
        permissionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const module = this.dataset.module;
                const modulePermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
                const moduleCheckbox = document.querySelector(`.module-checkbox[data-module="${module}"]`);
                
                const allChecked = Array.from(modulePermissions).every(p => p.checked);
                const anyChecked = Array.from(modulePermissions).some(p => p.checked);
                
                moduleCheckbox.checked = allChecked;
                moduleCheckbox.indeterminate = anyChecked && !allChecked;
                
                updateSelectedCount();
            });
        });

        // Seleccionar todos
        selectAllBtn.addEventListener('click', function() {
            permissionCheckboxes.forEach(cb => cb.checked = true);
            moduleCheckboxes.forEach(cb => {
                cb.checked = true;
                cb.indeterminate = false;
            });
            updateSelectedCount();
        });

        // Deseleccionar todos
        deselectAllBtn.addEventListener('click', function() {
            permissionCheckboxes.forEach(cb => cb.checked = false);
            moduleCheckboxes.forEach(cb => {
                cb.checked = false;
                cb.indeterminate = false;
            });
            updateSelectedCount();
        });

        // Validación al enviar
        permissionsForm.addEventListener('submit', function(e) {
            const selected = document.querySelectorAll('.permission-checkbox:checked').length;
            if (selected === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Permisos requeridos',
                    text: 'Debe seleccionar al menos un permiso para el rol.',
                    confirmButtonText: 'Entendido'
                });
            }
        });

        // Inicializar checkboxes de módulo
        moduleCheckboxes.forEach(moduleCheckbox => {
            const module = moduleCheckbox.dataset.module;
            const modulePermissions = document.querySelectorAll(`.permission-checkbox[data-module="${module}"]`);
            const allChecked = Array.from(modulePermissions).every(p => p.checked);
            const anyChecked = Array.from(modulePermissions).some(p => p.checked);
            
            moduleCheckbox.checked = allChecked;
            moduleCheckbox.indeterminate = anyChecked && !allChecked;
        });

        // Contador inicial
        updateSelectedCount();
    });
</script>
@endpush