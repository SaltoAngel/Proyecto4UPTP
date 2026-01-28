<div class="modal fade" id="crearRolModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success py-3">
                <h5 class="modal-title text-white d-flex align-items-center">
                    <i class="material-icons me-2">add_circle</i>Nuevo Rol de Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('dashboard.roles.store') }}" method="POST" id="formCreate">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-4">
                        <label class="text-uppercase text-xs font-weight-bolder text-secondary ml-1">NOMBRE DEL ROL <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control border px-3" placeholder="Ej: Auditor Interno" required style="border-radius: 0.5rem !important;">
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <h6 class="text-uppercase text-xs font-weight-bolder text-secondary mb-0">ASIGNACIÓN DE PERMISOS</h6>
                        <div class="d-flex align-items-center gap-3">
                            <div class="form-check form-switch mb-0 d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" id="masterSelectCreate" onclick="toggleAll('formCreate', this)">
                                <label class="form-check-label mb-0 ms-2 text-xs text-uppercase font-weight-bold" for="masterSelectCreate" style="cursor:pointer">SELECCIONAR TODO</label>
                            </div>
                            <button type="button" class="btn btn-link text-danger text-xs font-weight-bold mb-0 p-0 d-flex align-items-center" onclick="resetForm('formCreate', 'masterSelectCreate')">
                                <i class="material-icons text-sm me-1">delete_sweep</i> LIMPIAR
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        @foreach($permissions as $modulo => $perms)
                            <div class="col-md-4 mb-4">
                                <div class="card border shadow-none" style="border-radius: 0.75rem;">
                                    <div class="card-header p-2 bg-light d-flex justify-content-between align-items-center" style="border-radius: 0.75rem 0.75rem 0 0;">
                                        <span class="text-xs text-uppercase font-weight-bolder ps-2 text-dark">{{ $modulo }}</span>
                                        <button type="button" class="btn btn-link text-success text-xxs mb-0 p-0 font-weight-bolder" onclick="toggleModule(this)">TODO</button>
                                    </div>
                                    <div class="card-body p-3 module-container">
                                        @foreach($perms as $perm)
                                            <div class="form-check my-1">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}" 
                                                       id="pc_{{ $perm->id }}">
                                                <label class="form-check-label text-xs mb-0" for="pc_{{ $perm->id }}">{{ $perm->name }}</label>
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
                        <i class="material-icons me-2">save</i>Guardar 
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>