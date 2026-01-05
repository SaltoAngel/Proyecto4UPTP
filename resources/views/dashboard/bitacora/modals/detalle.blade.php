    <!-- Modal Detalle Bitácora -->
    <div class="modal fade" id="modalBitacora" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <span class="material-icons text-primary">description</span>
                        <span id="modalBitacoraCodigo">Detalle</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="small text-muted">Usuario</div>
                            <div class="fw-semibold" id="modalBitacoraUsuario">-</div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Módulo</div>
                            <span class="badge bg-primary" id="modalBitacoraModulo">-</span>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Acción</div>
                            <span class="badge bg-success" id="modalBitacoraAccion">-</span>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Fecha</div>
                            <div id="modalBitacoraFecha">-</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="small text-muted mb-1">Detalle</div>
                        <div class="bg-light p-3 rounded" id="modalBitacoraDetalle">-</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="small text-muted mb-1">Datos Anteriores</div>
                            <pre class="bg-light p-3 rounded" id="modalBitacoraAntes" style="min-height:140px;">-</pre>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted mb-1">Datos Nuevos</div>
                            <pre class="bg-light p-3 rounded" id="modalBitacoraDespues" style="min-height:140px;">-</pre>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
