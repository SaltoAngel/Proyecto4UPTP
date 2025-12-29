<div class="modal fade" id="editarPersonaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="material-icons me-2">edit</i>Editar Persona
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
                                    <i class="material-icons me-1">person</i>Persona Natural
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo" id="editTipoJuridica" value="juridica">
                                <label class="form-check-label" for="editTipoJuridica">
                                    <i class="material-icons me-1">business</i>Persona Jurídica
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="campo-natural-edit">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editNombres" class="form-label"><i class="material-icons me-1">badge</i>Nombres *</label>
                                <input type="text" class="form-control" id="editNombres" name="nombres">
                            </div>
                            <div class="col-md-6">
                                <label for="editApellidos" class="form-label"><i class="material-icons me-1">badge</i>Apellidos *</label>
                                <input type="text" class="form-control" id="editApellidos" name="apellidos">
                            </div>
                        </div>
                    </div>

                    <div class="campo-juridica-edit" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editRazonSocial" class="form-label"><i class="material-icons me-1">business</i>Razón Social *</label>
                                <input type="text" class="form-control" id="editRazonSocial" name="razon_social">
                            </div>
                            <div class="col-md-6">
                                <label for="editNombreComercial" class="form-label"><i class="material-icons me-1">storefront</i>Nombre Comercial</label>
                                <input type="text" class="form-control" id="editNombreComercial" name="nombre_comercial">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="editTipoDocumento" class="form-label"><i class="material-icons me-1">badge</i>Tipo Documento *</label>
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
                            <label for="editDocumento" class="form-label"><i class="material-icons me-1">numbers</i>Número de Documento *</label>
                            <input type="text" class="form-control" id="editDocumento" name="documento" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editTelefono" class="form-label"><i class="material-icons me-1">phone</i>Teléfono Principal</label>
                            <input type="text" class="form-control" id="editTelefono" name="telefono">
                        </div>
                        <div class="col-md-6">
                            <label for="editTelefonoAlternativo" class="form-label"><i class="material-icons me-1">phone_in_talk</i>Teléfono Alternativo</label>
                            <input type="text" class="form-control" id="editTelefonoAlternativo" name="telefono_alternativo">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editEmail" class="form-label"><i class="material-icons me-1">mail</i>Correo Electrónico</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="col-md-3">
                            <label for="editEstado" class="form-label"><i class="material-icons me-1">map</i>Estado/Provincia</label>
                            <input type="text" class="form-control" id="editEstado" name="estado">
                        </div>
                        <div class="col-md-3">
                            <label for="editCiudad" class="form-label"><i class="material-icons me-1">location_city</i>Ciudad</label>
                            <input type="text" class="form-control" id="editCiudad" name="ciudad">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="editDireccion" class="form-label"><i class="material-icons me-1">location_on</i>Dirección Completa</label>
                            <textarea class="form-control" id="editDireccion" name="direccion" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="material-icons me-2">close</i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning text-dark">
                        <i class="material-icons me-2">save</i>Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
