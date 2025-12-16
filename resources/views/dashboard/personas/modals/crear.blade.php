<!-- resources/views/dashboard/personas/modals/crear.blade.php -->
<div class="modal fade" id="crearPersonaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Registrar Nueva Persona
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="formCrearPersona" method="POST" action="{{ route('dashboard.personas.store') }}">
                @csrf
                
                <div class="modal-body">
                    <!-- Tipo de Persona -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Tipo de Persona *</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo" 
                                       id="tipo_natural" value="natural" checked>
                                <label class="form-check-label" for="tipo_natural">
                                    <i class="fas fa-user me-1"></i>Persona Natural
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo" 
                                       id="tipo_juridica" value="juridica">
                                <label class="form-check-label" for="tipo_juridica">
                                    <i class="fas fa-building me-1"></i>Persona Jurídica
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campos para Persona Natural -->
                    <div class="campo-natural">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombres" class="form-label">
                                    <i class="fas fa-signature me-1"></i>Nombres *
                                </label>
                                <input type="text" class="form-control" id="nombres" name="nombres">
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">
                                    <i class="fas fa-signature me-1"></i>Apellidos *
                                </label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campos para Persona Jurídica -->
                    <div class="campo-juridica" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="razon_social" class="form-label">
                                    <i class="fas fa-building me-1"></i>Razón Social *
                                </label>
                                <input type="text" class="form-control" id="razon_social" name="razon_social">
                            </div>
                            <div class="col-md-6">
                                <label for="nombre_comercial" class="form-label">
                                    <i class="fas fa-store me-1"></i>Nombre Comercial
                                </label>
                                <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Documento -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tipo_documento" class="form-label">
                                <i class="fas fa-id-card me-1"></i>Tipo Documento *
                            </label>
                            <div class="input-group">
                                <select class="form-select" id="tipo_documento" name="tipo_documento" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="V">V - Venezolano</option>
                                    <option value="E">E - Extranjero</option>
                                    <option value="J">J - RIF</option>
                                    <option value="G">G - Gubernamental</option>
                                    <option value="P">P - Pasaporte</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label for="documento" class="form-label">
                                <i class="fas fa-hashtag me-1"></i>Número de Documento *
                            </label>
                            <input type="text" class="form-control" id="documento" name="documento" required>
                        </div>
                    </div>
                    
                    <!-- Contacto -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">
                                <i class="fas fa-phone me-1"></i>Teléfono Principal
                            </label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>
                        <div class="col-md-6">
                            <label for="telefono_alternativo" class="form-label">
                                <i class="fas fa-phone-alt me-1"></i>Teléfono Alternativo
                            </label>
                            <input type="text" class="form-control" id="telefono_alternativo" name="telefono_alternativo">
                        </div>
                    </div>
                    
                    <!-- Email y ubicación -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Correo Electrónico
                            </label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="col-md-3">
                            <label for="estado" class="form-label">
                                <i class="fas fa-map me-1"></i>Estado/Provincia
                            </label>
                            <input type="text" class="form-control" id="estado" name="estado">
                        </div>
                        <div class="col-md-3">
                            <label for="ciudad" class="form-label">
                                <i class="fas fa-city me-1"></i>Ciudad
                            </label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="direccion" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Dirección Completa
                            </label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Guardar Persona
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>