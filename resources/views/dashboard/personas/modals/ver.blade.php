<div class="modal fade" id="verPersonaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title text-white">
                    <i class="material-icons me-2">badge</i>Detalles de Persona
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Resumen superior -->
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="badge bg-dark"><i class="material-icons me-1">qr_code_2</i><span id="modalCodigo">-</span></span>
                            <span id="modalEstadoRegistro" class="badge bg-secondary">-</span>
                            <span class="badge bg-light text-dark"><i class="material-icons me-1">badge</i><span id="modalTipo">-</span></span>
                            <span class="badge bg-light text-dark"><i class="material-icons me-1">access_time</i><span id="modalFechaActualizacion" class="small">-</span></span>
                        </div>
                    </div>

                    <!-- Información principal -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="form-label fw-bold mb-3">Información Principal</h6>
                                <div class="mb-3">
                                    <div class="text-uppercase text-muted small"><i class="material-icons me-1">badge</i>Nombre</div>
                                    <div id="modalNombreCompleto" class="fw-bold"></div>
                                </div>
                                <div id="camposNatural" style="display: none;">
                                    <div class="mb-2">
                                        <div class="text-muted small"><i class="material-icons me-1">person</i>Nombres</div>
                                        <div id="modalNombres" class="text-muted"></div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-muted small"><i class="material-icons me-1">person</i>Apellidos</div>
                                        <div id="modalApellidos" class="text-muted"></div>
                                    </div>
                                </div>
                                <div id="camposJuridica" style="display: none;">
                                    <div class="mb-2">
                                        <div class="text-muted small"><i class="material-icons me-1">business</i>Razón Social</div>
                                        <div id="modalRazonSocial" class="text-muted"></div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-muted small"><i class="material-icons me-1">storefront</i>Nombre Comercial</div>
                                        <div id="modalNombreComercial" class="text-muted"></div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="text-muted small"><i class="material-icons me-1">badge</i>Tipo Documento</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div id="modalTipoDocumento" class="text-muted"></div>
                                        <span class="badge bg-light text-dark" id="modalTipoDocLeyenda">-</span>
                                    </div>
                                    <div class="text-muted small mt-2"><i class="material-icons me-1">numbers</i>N° Documento</div>
                                    <div id="modalDocumento" class="text-muted"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contacto y ubicación -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3"><i class="material-icons me-1">contact_mail</i>Contacto</h6>
                                <div class="mb-2">
                                    <div class="text-muted small"><i class="material-icons me-1">mail</i>Email</div>
                                    <div id="modalEmail" class="text-muted"></div>
                                </div>
                                <div class="mb-2">
                                    <div class="text-muted small"><i class="material-icons me-1">phone</i>Teléfono</div>
                                    <div id="modalTelefono" class="text-muted"></div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-muted small"><i class="material-icons me-1">phone_in_talk</i>Teléfono Alternativo</div>
                                    <div id="modalTelefonoAlternativo" class="text-muted"></div>
                                </div>
                                <h6 class="text-primary fw-bold mb-3"><i class="material-icons me-1">location_on</i>Ubicación</h6>
                                <div class="mb-2">
                                    <div class="text-muted small"><i class="material-icons me-1">pin_drop</i>Dirección</div>
                                    <div id="modalDireccion" class="text-muted"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-muted small"><i class="material-icons me-1">map</i>Estado/Provincia</div>
                                        <div id="modalEstado" class="text-muted"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-muted small"><i class="material-icons me-1">location_city</i>Ciudad</div>
                                        <div id="modalCiudad" class="text-muted"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Auditoría -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3"><i class="material-icons me-1">dns</i>Información de Sistema</h6>
                                <div class="row text-muted small">
                                    <div class="col-md-4 mb-2">
                                        <div><i class="material-icons me-1">event_available</i>Fecha Registro</div>
                                        <div id="modalFechaRegistro"></div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div><i class="material-icons me-1">event</i>Última Actualización</div>
                                        <div id="modalFechaActualizacion"></div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div><i class="material-icons me-1">event_busy</i>Fecha Eliminación</div>
                                        <div id="modalFechaEliminacion"></div>
                                    </div>
                                </div>
                            </div>
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