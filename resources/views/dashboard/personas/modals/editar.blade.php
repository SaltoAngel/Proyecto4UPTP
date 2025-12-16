<div class="modal fade" id="editarPersonaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2"></i>Editar Persona
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="#" id="formEditarPersona">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Tipo de Persona *</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo" id="editTipoNatural" value="natural">
                                <label class="form-check-label" for="editTipoNatural">
                                    <i class="fas fa-user me-1"></i>Persona Natural
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo" id="editTipoJuridica" value="juridica">
                                <label class="form-check-label" for="editTipoJuridica">
                                    <i class="fas fa-building me-1"></i>Persona Jurídica
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="campo-natural-edit">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editNombres" class="form-label"><i class="fas fa-signature me-1"></i>Nombres *</label>
                                <input type="text" class="form-control" id="editNombres" name="nombres">
                            </div>
                            <div class="col-md-6">
                                <label for="editApellidos" class="form-label"><i class="fas fa-signature me-1"></i>Apellidos *</label>
                                <input type="text" class="form-control" id="editApellidos" name="apellidos">
                            </div>
                        </div>
                    </div>

                    <div class="campo-juridica-edit" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editRazonSocial" class="form-label"><i class="fas fa-building me-1"></i>Razón Social *</label>
                                <input type="text" class="form-control" id="editRazonSocial" name="razon_social">
                            </div>
                            <div class="col-md-6">
                                <label for="editNombreComercial" class="form-label"><i class="fas fa-store me-1"></i>Nombre Comercial</label>
                                <input type="text" class="form-control" id="editNombreComercial" name="nombre_comercial">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="editTipoDocumento" class="form-label"><i class="fas fa-id-card me-1"></i>Tipo Documento *</label>
                            <div class="input-group">
                                <select class="form-select" id="editTipoDocumento" name="tipo_documento" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="V">V - Venezolano</option>
                                    <option value="E">E - Extranjero</option>
                                    <option value="J">J - RIF</option>
                                    <option value="G">G - Gubernamental</option>
                                    <option value="P">P - Pasaporte</option>
                                </select>
                                <span class="input-group-text" id="editTipoDocLeyenda">-</span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label for="editDocumento" class="form-label"><i class="fas fa-hashtag me-1"></i>Número de Documento *</label>
                            <input type="text" class="form-control" id="editDocumento" name="documento" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editTelefono" class="form-label"><i class="fas fa-phone me-1"></i>Teléfono Principal</label>
                            <input type="text" class="form-control" id="editTelefono" name="telefono">
                        </div>
                        <div class="col-md-6">
                            <label for="editTelefonoAlternativo" class="form-label"><i class="fas fa-phone-alt me-1"></i>Teléfono Alternativo</label>
                            <input type="text" class="form-control" id="editTelefonoAlternativo" name="telefono_alternativo">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editEmail" class="form-label"><i class="fas fa-envelope me-1"></i>Correo Electrónico</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="col-md-3">
                            <label for="editEstado" class="form-label"><i class="fas fa-map me-1"></i>Estado/Provincia</label>
                            <input type="text" class="form-control" id="editEstado" name="estado">
                        </div>
                        <div class="col-md-3">
                            <label for="editCiudad" class="form-label"><i class="fas fa-city me-1"></i>Ciudad</label>
                            <input type="text" class="form-control" id="editCiudad" name="ciudad">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="editDireccion" class="form-label"><i class="fas fa-map-marker-alt me-1"></i>Dirección Completa</label>
                            <textarea class="form-control" id="editDireccion" name="direccion" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning text-dark">
                        <i class="fas fa-save me-2"></i>Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
