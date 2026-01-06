<div class="modal fade" id="verProveedorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title text-white"><i class="material-icons me-2">badge</i>Detalles de Proveedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3">Información general</h6>
                                <div class="mb-2"><span class="text-muted small">Código</span><div id="verCodigo" class="fw-bold"></div></div>
                                <div class="mb-2"><span class="text-muted small">Documento</span><div id="verDocumento"></div></div>
                                <div class="mb-2"><span class="text-muted small">Categorías</span><div id="verCategoria"></div></div>
                                <div class="mb-2"><span class="text-muted small">Productos/Servicios</span><div id="verProductos"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="text-primary fw-bold mb-3">Contacto</h6>
                                <div class="mb-2"><span class="text-muted small">Persona / Contacto</span><div id="verNombre"></div></div>
                                <div class="mb-2"><span class="text-muted small">Teléfono</span><div id="verTelefono"></div></div>
                                <div class="mb-2"><span class="text-muted small">Email</span><div id="verEmail"></div></div>
                                <div class="mb-2"><span class="text-muted small">Calificación</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <div id="verCalificacionStars" class="text-warning"></div>
                                        <div id="verCalificacionValor" class="text-muted small"></div>
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
