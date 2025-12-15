<div class="modal fade" id="verPersonaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Persona</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Columna Izquierda: Información Principal -->
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2 mb-3">Información Principal</h6>
                        
                        <div class="mb-3">
                            <strong>Código:</strong>
                            <div id="modalCodigo" class="text-muted"></div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Tipo:</strong>
                            <div id="modalTipo" class="text-muted"></div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Estado:</strong>
                            <div id="modalEstadoRegistro"></div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Nombre Completo:</strong>
                            <div id="modalNombreCompleto" class="fw-bold"></div>
                        </div>
                        
                        <!-- Campos según tipo -->
                        <div id="camposNatural" style="display: none;">
                            <div class="mb-3">
                                <strong>Nombres:</strong>
                                <div id="modalNombres" class="text-muted"></div>
                            </div>
                            <div class="mb-3">
                                <strong>Apellidos:</strong>
                                <div id="modalApellidos" class="text-muted"></div>
                            </div>
                        </div>
                        
                        <div id="camposJuridica" style="display: none;">
                            <div class="mb-3">
                                <strong>Razón Social:</strong>
                                <div id="modalRazonSocial" class="text-muted"></div>
                            </div>
                            <div class="mb-3">
                                <strong>Nombre Comercial:</strong>
                                <div id="modalNombreComercial" class="text-muted"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Columna Derecha: Información Adicional -->
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2 mb-3">Documentación</h6>
                        
                        <div class="mb-3">
                            <strong>Tipo Documento:</strong>
                            <div id="modalTipoDocumento" class="text-muted"></div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>N° Documento:</strong>
                            <div id="modalDocumento" class="text-muted"></div>
                        </div>
                        
                        <h6 class="border-bottom pb-2 mb-3 mt-4">Contacto</h6>
                        
                        <div class="mb-3">
                            <strong>Email:</strong>
                            <div id="modalEmail" class="text-muted"></div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Teléfono:</strong>
                            <div id="modalTelefono" class="text-muted"></div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Teléfono Alternativo:</strong>
                            <div id="modalTelefonoAlternativo" class="text-muted"></div>
                        </div>
                        
                        <h6 class="border-bottom pb-2 mb-3 mt-4">Ubicación</h6>
                        
                        <div class="mb-3">
                            <strong>Dirección:</strong>
                            <div id="modalDireccion" class="text-muted"></div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Estado/Provincia:</strong>
                            <div id="modalEstado" class="text-muted"></div>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Ciudad:</strong>
                            <div id="modalCiudad" class="text-muted"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Información de Auditoría (Full Width) -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Información de Sistema</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <strong>Fecha Registro:</strong>
                                <div id="modalFechaRegistro" class="text-muted small"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Última Actualización:</strong>
                                <div id="modalFechaActualizacion" class="text-muted small"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <strong>Fecha Eliminación:</strong>
                                <div id="modalFechaEliminacion" class="text-muted small"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="editarPersona()">Editar</button>
            </div>
        </div>
    </div>
</div>