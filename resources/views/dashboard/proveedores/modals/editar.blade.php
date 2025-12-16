<div class="modal fade" id="editarProveedorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarProveedor" method="POST" action="#">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Persona <span class="text-danger">*</span></label>
                            <select name="persona_id" id="editPersona" class="form-select" required>
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
                            <div class="border rounded p-2" id="editarSelectorCategorias" data-target-input="#editarTiposSeleccionados" data-target-summary="#editarResumenCategorias">
                                <div class="d-flex flex-wrap gap-1 mb-2 categorias-seleccionadas"></div>
                                <div class="d-flex flex-wrap gap-1 categorias-disponibles">
                                    @foreach($tiposProveedores as $tipo)
                                        <button type="button" class="btn btn-sm btn-outline-primary categoria-item" data-id="{{ $tipo->id }}" data-nombre="{{ $tipo->nombre_tipo }}">{{ $tipo->nombre_tipo }}</button>
                                    @endforeach
                                </div>
                            </div>
                            <div id="editarTiposSeleccionados" class="d-none"></div>
                            <input type="text" class="form-control mt-2" id="editarResumenCategorias" placeholder="Resumen de categorías" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Especialización</label>
                            <input type="text" name="especializacion" id="editEspecializacion" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Productos / Servicios</label>
                        <textarea name="productos_servicios" id="editProductos" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Calificación (1-5)</label>
                            <select name="calificacion" id="editCalificacion" class="form-select">
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado" id="editEstado" class="form-select" required>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="suspendido">Suspendido</option>
                                <option value="en_revision">En revisión</option>
                                <option value="bloqueado">Bloqueado</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones de calificación</label>
                        <textarea name="observaciones_calificacion" id="editObservaciones" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha de registro</label>
                            <input type="date" name="fecha_registro" id="editFechaRegistro" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha última compra</label>
                            <input type="date" name="fecha_ultima_compra" id="editFechaUltimaCompra" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Banco</label>
                            <input type="text" name="banco" id="editBanco" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de cuenta</label>
                            <input type="text" name="tipo_cuenta" id="editTipoCuenta" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Número de cuenta</label>
                            <input type="text" name="numero_cuenta" id="editNumeroCuenta" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark"><i class="fas fa-save me-2"></i>Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
