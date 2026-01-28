<div class="modal fade" id="crearRolModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">
                    <i class="material-icons me-2 text-white">admin_panel_settings</i>Nuevo rol
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('dashboard.roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <label class="form-label fw-bold"><i class="material-icons text-sm me-1">label</i>Nombre del rol <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control border px-2" placeholder="Ej: Auditor Interno" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 text-uppercase text-secondary text-xs font-weight-bolder">Asignación de permisos</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkAll">
                            <label class="form-check-label text-xs fw-bold" for="checkAll">SELECCIONAR TODO</label>
                        </div>
                    </div>

                    <div class="row">
                        @foreach($permissions as $modulo => $perms)
                        <div class="col-md-6 mb-4">
                            <div class="card border shadow-none">
                                <div class="card-header p-2 bg-light d-flex justify-content-between align-items-center">
                                    <span class="text-xs font-weight-bold text-uppercase text-primary">{{ $modulo }}</span>
                                    <small class="text-muted" style="cursor:pointer" onclick="checkModulo('{{ $modulo }}')">Todo</small>
                                </div>
                                <div class="card-body p-2">
                                    @foreach($perms as $perm)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input permission-checkbox module-{{ $modulo }}" 
                                               type="checkbox" name="permissions[]" 
                                               value="{{ $perm->id }}" id="perm_{{ $perm->id }}">
                                        <label class="form-check-label text-xs" for="perm_{{ $perm->id }}">
                                            {{ $perm->name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="material-icons me-2">save</i>Guardar Rol
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Este script ahora está en el archivo principal
</script>