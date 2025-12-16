<div class="modal fade" id="verPersonaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-id-card me-2"></i>Detalles de Persona
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Resumen superior -->
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="badge bg-dark"><i class="fas fa-barcode me-1"></i><span id="modalCodigo">-</span></span>
                            <span id="modalEstadoRegistro" class="badge bg-secondary">-</span>
                            <span class="badge bg-light text-dark"><i class="fas fa-id-card me-1"></i><span id="modalTipo">-</span></span>
                            <span class="badge bg-light text-dark"><i class="fas fa-clock me-1"></i><span id="modalFechaActualizacion" class="small">-</span></span>
                        </div>
                    </div>

                    <!-- Información principal -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3">Información Principal</h6>
                                <div class="mb-3">
                                    <div class="text-uppercase text-muted small"><i class="fas fa-signature me-1"></i>Nombre</div>
                                    <div id="modalNombreCompleto" class="fw-bold"></div>
                                </div>
                                <div id="camposNatural" style="display: none;">
                                    <div class="mb-2">
                                        <div class="text-muted small"><i class="fas fa-user me-1"></i>Nombres</div>
                                        <div id="modalNombres" class="text-muted"></div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-muted small"><i class="fas fa-user me-1"></i>Apellidos</div>
                                        <div id="modalApellidos" class="text-muted"></div>
                                    </div>
                                </div>
                                <div id="camposJuridica" style="display: none;">
                                    <div class="mb-2">
                                        <div class="text-muted small"><i class="fas fa-building me-1"></i>Razón Social</div>
                                        <div id="modalRazonSocial" class="text-muted"></div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-muted small"><i class="fas fa-store me-1"></i>Nombre Comercial</div>
                                        <div id="modalNombreComercial" class="text-muted"></div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="text-muted small"><i class="fas fa-id-card me-1"></i>Tipo Documento</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div id="modalTipoDocumento" class="text-muted"></div>
                                        <span class="badge bg-light text-dark" id="modalTipoDocLeyenda">-</span>
                                    </div>
                                    <div class="text-muted small mt-2"><i class="fas fa-hashtag me-1"></i>N° Documento</div>
                                    <div id="modalDocumento" class="text-muted"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contacto y ubicación -->
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-envelope-open-text me-1"></i>Contacto</h6>
                                <div class="mb-2">
                                    <div class="text-muted small"><i class="fas fa-envelope me-1"></i>Email</div>
                                    <div id="modalEmail" class="text-muted"></div>
                                </div>
                                <div class="mb-2">
                                    <div class="text-muted small"><i class="fas fa-phone me-1"></i>Teléfono</div>
                                    <div id="modalTelefono" class="text-muted"></div>
                                </div>
                                <div class="mb-3">
                                    <div class="text-muted small"><i class="fas fa-phone-alt me-1"></i>Teléfono Alternativo</div>
                                    <div id="modalTelefonoAlternativo" class="text-muted"></div>
                                </div>
                                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-map-marker-alt me-1"></i>Ubicación</h6>
                                <div class="mb-2">
                                    <div class="text-muted small"><i class="fas fa-map-pin me-1"></i>Dirección</div>
                                    <div id="modalDireccion" class="text-muted"></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-muted small"><i class="fas fa-map me-1"></i>Estado/Provincia</div>
                                        <div id="modalEstado" class="text-muted"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-muted small"><i class="fas fa-city me-1"></i>Ciudad</div>
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
                                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-server me-1"></i>Información de Sistema</h6>
                                <div class="row text-muted small">
                                    <div class="col-md-4 mb-2">
                                        <div><i class="fas fa-calendar-plus me-1"></i>Fecha Registro</div>
                                        <div id="modalFechaRegistro"></div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div><i class="fas fa-calendar-check me-1"></i>Última Actualización</div>
                                        <div id="modalFechaActualizacion"></div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div><i class="fas fa-calendar-times me-1"></i>Fecha Eliminación</div>
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