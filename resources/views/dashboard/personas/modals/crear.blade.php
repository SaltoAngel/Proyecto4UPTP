<!-- resources/views/dashboard/personas/modals/crear.blade.php -->
<div class="modal fade" id="crearPersonaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">
                    <i class="material-icons me-2 text-white">person_add</i>Registrar Nueva Persona
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form id="formCrearPersona" method="POST" action="{{ route('dashboard.personas.store') }}">
                @csrf
                
                <div class="modal-body">
                    <!-- Tipo de Persona -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Tipo de Persona <span class="text-danger">*</span></label></label> <br>    
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
                                    <i class="material-icons me-1">badge</i>Nombres <span class="text-danger"> *</span></label>
                                </label>
                                <input type="text" class="form-control" id="nombres" name="nombres" maxlength="60" autocomplete="off">
                                <div class="form-text text-danger small d-none" id="nombres-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">
                                    <i class="material-icons me-1">badge</i>Apellidos <span class="text-danger"> *</span></label>
                                </label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" maxlength="60" autocomplete="off">
                                <div class="form-text text-danger small d-none" id="apellidos-error"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campos para Persona Jurídica -->
                    <div class="campo-juridica" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="razon_social" class="form-label">
                                    <i class="material-icons me-1">business</i>Razón Social<span class="text-danger"> *</span></label>
                                </label>
                                <input type="text" class="form-control" id="razon_social" name="razon_social" maxlength="120" autocomplete="off">
                                <div class="form-text text-danger small d-none" id="razon_social-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="nombre_comercial" class="form-label">
                                    <i class="material-icons me-1">storefront</i>Nombre Comercial
                                </label>
                                <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" maxlength="120" autocomplete="off">
                                <div class="form-text text-danger small d-none" id="nombre_comercial-error"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Documento -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tipo_documento" class="form-label">
                                <i class="material-icons me-1">badge</i>Tipo Documento<span class="text-danger"> *</span></label>
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
                                <i class="material-icons me-1">numbers</i>Número de Documento<span class="text-danger"> *</span></label>
                            </label>
                            <input type="text" class="form-control" id="documento" name="documento" required maxlength="12" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="documento-error"></div>
                        </div>
                    </div>
                    
                    <!-- Contacto -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">
                                <i class="material-icons me-1">phone</i>Teléfono Principal<span class="text-danger"> *</span></label> 
                            </label>
                            <div class="input-group">
                                <select class="form-select" id="telefono_prefijo" aria-label="Prefijo venezolano">
                                    <option value="0412">0412</option>
                                    <option value="0414">0414</option>
                                    <option value="0416">0416</option>
                                    <option value="0424">0424</option>
                                    <option value="0426">0426</option>
                                    <option value="0212">0212</option>
                                    <option value="0251">0251</option>
                                    <option value="0255">0255</option>
                                    <option value="0257">0257</option>
                                    <option value="0258">0258</option>
                                    <option value="0261">0261</option>
                                    <option value="0268">0268</option>
                                    <option value="0274">0274</option>
                                    <option value="0276">0276</option>
                                </select>
                                <input type="text" class="form-control" id="telefono" name="telefono" maxlength="7" autocomplete="off" placeholder="Número" aria-label="Número">
                            </div>
                            <div class="form-text text-danger small d-none" id="telefono-error"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="toggle_tel_alt">
                                <label class="form-check-label" for="toggle_tel_alt">
                                    <i class="material-icons me-1 align-middle">phone_in_talk</i>Agregar teléfono alternativo
                                </label>
                            </div>
                            <div id="tel_alt_wrapper" class="d-none">
                                <div class="input-group mb-1">
                                    <select class="form-select" id="telefono_alternativo_prefijo" aria-label="Prefijo alternativo">
                                        <option value="0412">0412</option>
                                        <option value="0414">0414</option>
                                        <option value="0416">0416</option>
                                        <option value="0424">0424</option>
                                        <option value="0426">0426</option>
                                        <option value="0212">0212</option>
                                        <option value="0251">0251</option>
                                        <option value="0255">0255</option>
                                        <option value="0257">0257</option>
                                        <option value="0258">0258</option>
                                        <option value="0261">0261</option>
                                        <option value="0268">0268</option>
                                        <option value="0274">0274</option>
                                        <option value="0276">0276</option>
                                    </select>
                                    <input type="text" class="form-control" id="telefono_alternativo" name="telefono_alternativo" maxlength="7" autocomplete="off" placeholder="Número" aria-label="Número alternativo">
                                </div>
                                <div class="form-text text-danger small d-none" id="telefono_alternativo-error"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Email y ubicación -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="material-icons me-1">mail</i>Correo Electrónico<span class="text-danger"> *</span>
                            </label>
                            <input type="email" class="form-control" id="email" name="email" maxlength="120" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="email-error"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="estado" class="form-label">
                                <i class="material-icons me-1">map</i>Estado<span class="text-danger"> *</span>
                            </label>
                            <select class="form-select" id="estado" name="estado" required></select>
                        </div>
                        <div class="col-md-3">
                            <label for="ciudad" class="form-label">
                                <i class="material-icons me-1">location_city</i>Ciudad<span class="text-danger"> *</span>
                            </label>
                            <select class="form-select" id="ciudad" name="ciudad" required></select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="direccion" class="form-label">
                                <i class="material-icons me-1">location_on</i>Dirección Completa
                            </label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="2" maxlength="240" autocomplete="off"></textarea>
                            <div class="form-text text-danger small d-none" id="direccion-error"></div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="material-icons me-2">close</i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="material-icons me-2">save</i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const estadosSelect = document.getElementById('estado');
    const ciudadesSelect = document.getElementById('ciudad');
    const nombresInput = document.getElementById('nombres');
    const apellidosInput = document.getElementById('apellidos');
    const razonInput = document.getElementById('razon_social');
    const comercialInput = document.getElementById('nombre_comercial');
    const docInput = document.getElementById('documento');
    const emailInput = document.getElementById('email');
    const telInput = document.getElementById('telefono');
    const telPrefijo = document.getElementById('telefono_prefijo');
    const telAltInput = document.getElementById('telefono_alternativo');
    const telAltPrefijo = document.getElementById('telefono_alternativo_prefijo');
    const telAltToggle = document.getElementById('toggle_tel_alt');
    const telAltWrapper = document.getElementById('tel_alt_wrapper');
    const direccionInput = document.getElementById('direccion');
    const form = document.getElementById('formCrearPersona');
    if (!estadosSelect || !ciudadesSelect) return;

    const stateCities = {
        'Amazonas': ['Puerto Ayacucho'],
        'Anzoátegui': ['Barcelona', 'Puerto La Cruz', 'El Tigre'],
        'Apure': ['San Fernando de Apure', 'Guasdualito'],
        'Aragua': ['Maracay', 'La Victoria', 'Turmero'],
        'Barinas': ['Barinas', 'Socopó'],
        'Bolívar': ['Ciudad Bolívar', 'Ciudad Guayana', 'Upata'],
        'Carabobo': ['Valencia', 'Naguanagua', 'Puerto Cabello', 'Guacara'],
        'Cojedes': ['San Carlos', 'Tinaco'],
        'Delta Amacuro': ['Tucupita'],
        'Distrito Capital': ['Caracas', 'El Junquito'],
        'Falcón': ['Coro', 'Punto Fijo', 'La Vela'],
        'Guárico': ['San Juan de los Morros', 'Calabozo', 'Valle de la Pascua'],
        'La Guaira': ['La Guaira', 'Maiquetía'],
        'Lara': ['Barquisimeto', 'Carora', 'El Tocuyo', 'Quíbor'],
        'Mérida': ['Mérida', 'Ejido', 'El Vigía'],
        'Miranda': ['Los Teques', 'Guarenas', 'Guatire', 'Baruta', 'Chacao'],
        'Monagas': ['Maturín', 'Punta de Mata', 'Caripe'],
        'Nueva Esparta': ['La Asunción', 'Porlamar'],
        'Portuguesa': ['Acarigua', 'Araure', 'Guanare', 'Turén', 'Píritu', 'Ospino', 'Biscucuy', 'Guanarito', 'Papelón', 'Agua Blanca', 'San Rafael de Onoto'],
        'Sucre': ['Cumaná', 'Carúpano'],
        'Táchira': ['San Cristóbal', 'Rubio', 'Táriba'],
        'Trujillo': ['Trujillo', 'Valera', 'Boconó'],
        'Yaracuy': ['San Felipe', 'Chivacoa'],
        'Zulia': ['Maracaibo', 'Cabimas', 'Ciudad Ojeda', 'Lagunillas']
    };

    const getFeatureName = (props = {}) => props.name || props.NOMBRE || props.state || props.estado || props.Estado || '';
    const defaultEstado = 'Distrito Capital';

    const renderEstados = (estados) => {
        estadosSelect.innerHTML = '';
        estados.forEach(estado => {
            const opt = document.createElement('option');
            opt.value = estado;
            opt.textContent = estado;
            estadosSelect.appendChild(opt);
        });
    };

    const loadCiudades = (estado) => {
        ciudadesSelect.innerHTML = '';
        const ciudades = stateCities[estado] || [];
        if (ciudades.length === 0) {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = 'Sin ciudades disponibles';
            ciudadesSelect.appendChild(opt);
            return;
        }
        ciudades.forEach(ciudad => {
            const opt = document.createElement('option');
            opt.value = ciudad;
            opt.textContent = ciudad;
            ciudadesSelect.appendChild(opt);
        });
    };

    const setInicial = (estados) => {
        const initial = estados.includes(defaultEstado) ? defaultEstado : (estados[0] || '');
        estadosSelect.value = initial;
        loadCiudades(initial);
    };

    fetch("{{ route('geo.ve') }}")
        .then(r => { if (!r.ok) throw new Error('geo ve no disponible'); return r.json(); })
        .then(json => {
            const features = Array.isArray(json?.features) ? json.features : [];
            const estados = [...new Set(features.map(f => getFeatureName(f.properties)).filter(Boolean))].sort();
            if (!estados.length) throw new Error('sin estados');
            renderEstados(estados);
            setInicial(estados);
        })
        .catch(() => {
            const estadosFallback = Object.keys(stateCities).sort();
            renderEstados(estadosFallback);
            setInicial(estadosFallback);
        });

    estadosSelect.addEventListener('change', (e) => loadCiudades(e.target.value));

    // Helpers de validación en tiempo real (similar a login)
    const showError = (input, message) => {
        if (!input) return;
        const err = document.getElementById(`${input.id}-error`);
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        if (err) {
            err.textContent = message || '';
            err.classList.remove('d-none');
        }
    };

    const clearError = (input) => {
        if (!input) return;
        const err = document.getElementById(`${input.id}-error`);
        input.classList.remove('is-invalid');
        input.classList.remove('is-valid');
        if (err) {
            err.textContent = '';
            err.classList.add('d-none');
        }
    };

    const showSuccess = (input) => {
        if (!input) return;
        clearError(input);
        input.classList.add('is-valid');
    };

    const onlyLetters = (val) => /^[a-zA-ZÀ-ÿ\s.'-]+$/.test(val);
    const onlyAlnum = (val) => /^[a-zA-Z0-9À-ÿ\s.'-]+$/.test(val);
    const onlyDigits = (val) => /^\d+$/.test(val);
    const validEmail = (val) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);

    const validateNombre = () => {
        const v = (nombresInput?.value || '').trim();
        if (!v) return showError(nombresInput, 'Ingresa nombres');
        if (!onlyLetters(v)) return showError(nombresInput, 'Solo letras y espacios');
        showSuccess(nombresInput);
    };

    const validateApellido = () => {
        const v = (apellidosInput?.value || '').trim();
        if (!v) return showError(apellidosInput, 'Ingresa apellidos');
        if (!onlyLetters(v)) return showError(apellidosInput, 'Solo letras y espacios');
        showSuccess(apellidosInput);
    };

    const validateRazon = () => {
        if (!razonInput) return;
        const v = razonInput.value.trim();
        if (!v && document.getElementById('tipo_juridica')?.checked) return showError(razonInput, 'Ingresa razón social');
        if (v && !onlyAlnum(v)) return showError(razonInput, 'Solo letras, números y espacios');
        if (v) showSuccess(razonInput); else clearError(razonInput);
    };

    const validateComercial = () => {
        if (!comercialInput) return;
        const v = comercialInput.value.trim();
        if (v && !onlyAlnum(v)) return showError(comercialInput, 'Solo letras, números y espacios');
        if (v) showSuccess(comercialInput); else clearError(comercialInput);
    };

    const validateDocumento = () => {
        if (!docInput) return;
        const v = docInput.value.trim();
        if (!v) return showError(docInput, 'Ingresa el documento');
        if (!onlyDigits(v)) return showError(docInput, 'Solo números');
        if (v.length < 6 || v.length > docInput.maxLength) return showError(docInput, `Entre 6 y ${docInput.maxLength} dígitos`);
        showSuccess(docInput);
    };

    const validateEmail = () => {
        if (!emailInput) return;
        const v = emailInput.value.trim();
        if (!v) return showError(emailInput, 'Ingresa el correo');
        if (!validEmail(v)) return showError(emailInput, 'Correo inválido');
        showSuccess(emailInput);
    };

    const validateTelefono = () => {
        if (!telInput) return;
        const v = telInput.value.trim();
        if (!v) return showError(telInput, 'Ingresa el número');
        if (!onlyDigits(v)) return showError(telInput, 'Solo números');
        if (v.length !== 7) return showError(telInput, 'Debe tener 7 dígitos');
        showSuccess(telInput);
    };

    const validateTelAlt = () => {
        if (!telAltInput || !telAltToggle) return;
        if (!telAltToggle.checked) { clearError(telAltInput); return; }
        const v = telAltInput.value.trim();
        if (!v) return showError(telAltInput, 'Ingresa el número');
        if (!onlyDigits(v)) return showError(telAltInput, 'Solo números');
        if (v.length !== 7) return showError(telAltInput, 'Debe tener 7 dígitos');
        showSuccess(telAltInput);
    };

    const validateDireccion = () => {
        if (!direccionInput) return;
        const v = direccionInput.value.trim();
        if (!v) return showError(direccionInput, 'Ingresa la dirección');
        showSuccess(direccionInput);
    };

    const blockInvalidChars = (input, regexAllowed) => {
        if (!input) return;
        input.addEventListener('input', () => {
            const clean = (input.value || '').match(regexAllowed)?.join('') || '';
            if (clean !== input.value) input.value = clean;
        });
    };

    blockInvalidChars(nombresInput, /[a-zA-ZÀ-ÿ\s.'-]/g);
    blockInvalidChars(apellidosInput, /[a-zA-ZÀ-ÿ\s.'-]/g);
    blockInvalidChars(razonInput, /[a-zA-Z0-9À-ÿ\s.'-]/g);
    blockInvalidChars(comercialInput, /[a-zA-Z0-9À-ÿ\s.'-]/g);
    blockInvalidChars(docInput, /[0-9]/g);
    blockInvalidChars(telInput, /[0-9]/g);
    blockInvalidChars(telAltInput, /[0-9]/g);

    nombresInput?.addEventListener('blur', validateNombre);
    apellidosInput?.addEventListener('blur', validateApellido);
    razonInput?.addEventListener('blur', validateRazon);
    comercialInput?.addEventListener('blur', validateComercial);
    docInput?.addEventListener('blur', validateDocumento);
    emailInput?.addEventListener('blur', validateEmail);
    telInput?.addEventListener('blur', validateTelefono);
    telAltInput?.addEventListener('blur', validateTelAlt);
    direccionInput?.addEventListener('blur', validateDireccion);

    telAltToggle?.addEventListener('change', (e) => {
        if (e.target.checked) {
            telAltWrapper?.classList.remove('d-none');
        } else {
            telAltWrapper?.classList.add('d-none');
            if (telAltInput) {
                telAltInput.value = '';
                clearError(telAltInput);
                telAltInput.classList.remove('is-valid');
            }
        }
    });

    form?.addEventListener('submit', (e) => {
        validateNombre();
        validateApellido();
        validateRazon();
        validateComercial();
        validateDocumento();
        validateEmail();
        validateTelefono();
        validateTelAlt();
        validateDireccion();

        const hasInvalid = form.querySelector('.is-invalid');
        if (hasInvalid) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return;
        }

        if (telInput && telPrefijo) {
            telInput.value = `${telPrefijo.value}${telInput.value}`;
        }

        if (telAltToggle?.checked && telAltInput && telAltPrefijo) {
            telAltInput.value = `${telAltPrefijo.value}${telAltInput.value}`;
        } else if (telAltInput && !telAltToggle?.checked) {
            telAltInput.value = '';
        }
    });
});
</script>
@endpush