<div class="modal fade" id="crearProveedorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="material-icons me-2">local_shipping</i>Registrar Proveedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCrearProveedor" method="POST" action="{{ route('dashboard.proveedores.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Persona <span class="text-danger">*</span></label>
                            <select name="persona_id" class="form-select" required>
                                <option value="">Seleccione persona</option>
                                @foreach($personas as $persona)
                                    <option value="{{ $persona->id }}">{{ $persona->codigo }} - {{ $persona->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Categorías (múltiples)</label>
                            <div class="border rounded p-2" id="crearSelectorCategorias" data-target-input="#crearTiposSeleccionados" data-target-summary="#crearResumenCategorias">
                                <div class="d-flex flex-wrap gap-1 mb-2 categorias-seleccionadas"></div>
                                <div class="d-flex flex-wrap gap-1 categorias-disponibles">
                                    @foreach($tiposProveedores as $tipo)
                                        <button type="button" class="btn btn-sm btn-outline-primary categoria-item" data-id="{{ $tipo->id }}" data-nombre="{{ $tipo->nombre_tipo }}">{{ $tipo->nombre_tipo }}</button>
                                    @endforeach
                                </div>
                            </div>
                            <div id="crearTiposSeleccionados" class="d-none"></div>
                            <input type="text" class="form-control mt-2" id="crearResumenCategorias" placeholder="Resumen de categorías" readonly>
                            <small class="text-muted">Click para agregar; vuelve a hacer click para quitar.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Especialización</label>
                            <input type="text" name="especializacion" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Productos / Servicios</label>
                        <textarea name="productos_servicios" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Contacto comercial</label>
                            <input type="text" name="contacto_comercial" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Teléfono comercial</label>
                            <input type="text" name="telefono_comercial" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email comercial</label>
                            <input type="email" name="email_comercial" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Calificación (1-5)</label>
                            <select name="calificacion" class="form-select">
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ $i === 5 ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado" class="form-select" required>
                                <option value="activo" selected>Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="suspendido">Suspendido</option>
                                <option value="en_revision">En revisión</option>
                                <option value="bloqueado">Bloqueado</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones de calificación</label>
                        <textarea name="observaciones_calificacion" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Banco</label>
                            <input type="text" name="banco" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de cuenta</label>
                            <input type="text" name="tipo_cuenta" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Número de cuenta</label>
                            <input type="text" name="numero_cuenta" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="material-icons me-2">close</i>Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="material-icons me-2">save</i>Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
