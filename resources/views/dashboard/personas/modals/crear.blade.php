<!-- resources/views/dashboard/personas/modals/crear.blade.php -->
<div class="modal fade" id="crearPersonaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="material-icons me-2">person_add</i>Registrar Nueva Persona
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
                                    <i class="material-icons me-1">person</i>Persona Natural
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo" 
                                       id="tipo_juridica" value="juridica">
                                <label class="form-check-label" for="tipo_juridica">
                                    <i class="material-icons me-1">business</i>Persona Jurídica
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campos para Persona Natural -->
                    <div class="campo-natural">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombres" class="form-label">
                                    <i class="material-icons me-1">badge</i>Nombres *
                                </label>
                                <input type="text" class="form-control" id="nombres" name="nombres">
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">
                                    <i class="material-icons me-1">badge</i>Apellidos *
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
                                    <i class="material-icons me-1">business</i>Razón Social *
                                </label>
                                <input type="text" class="form-control" id="razon_social" name="razon_social">
                            </div>
                            <div class="col-md-6">
                                <label for="nombre_comercial" class="form-label">
                                    <i class="material-icons me-1">storefront</i>Nombre Comercial
                                </label>
                                <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Documento -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tipo_documento" class="form-label">
                                <i class="material-icons me-1">badge</i>Tipo Documento *
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
                                <i class="material-icons me-1">numbers</i>Número de Documento *
                            </label>
                            <input type="text" class="form-control" id="documento" name="documento" required>
                        </div>
                    </div>
                    
                    <!-- Contacto -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">
                                <i class="material-icons me-1">phone</i>Teléfono Principal
                            </label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>
                        <div class="col-md-6">
                            <label for="telefono_alternativo" class="form-label">
                                <i class="material-icons me-1">phone_in_talk</i>Teléfono Alternativo
                            </label>
                            <input type="text" class="form-control" id="telefono_alternativo" name="telefono_alternativo">
                        </div>
                    </div>
                    
                    <!-- Email y ubicación -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="material-icons me-1">mail</i>Correo Electrónico
                            </label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="col-md-3">
                            <label for="estado" class="form-label">
                                <i class="material-icons me-1">map</i>Estado/Provincia
                            </label>
                            <input type="text" class="form-control" id="estado" name="estado">
                        </div>
                        <div class="col-md-3">
                            <label for="ciudad" class="form-label">
                                <i class="material-icons me-1">location_city</i>Ciudad
                            </label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="direccion" class="form-label">
                                <i class="material-icons me-1">location_on</i>Dirección Completa
                            </label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="material-icons me-2">close</i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="material-icons me-2">save</i>Guardar Persona
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>