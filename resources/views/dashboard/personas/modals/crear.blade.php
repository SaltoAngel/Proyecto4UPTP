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
                                       id="tipo_natural" value="natural" checked style="">
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
                        <div class="col-md-6">
                            <label for="estado" class="form-label">
                                <i class="material-icons me-1">map</i>Estado<span class="text-danger"> *</span>
                            </label>
                            <select class="form-select" id="estado" name="estado" required></select>
                            <div class="form-text text-danger small d-none" id="estado-error"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="municipio" class="form-label">
                                <i class="material-icons me-1">map</i>Municipio<span class="text-danger"> *</span>
                            </label>
                            <select class="form-select" id="municipio" name="municipio" required></select>
                            <div class="form-text text-danger small d-none" id="municipio-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="parroquia" class="form-label">
                                <i class="material-icons me-1">location_city</i>Parroquia<span class="text-danger"> *</span>
                            </label>
                            <select class="form-select" id="parroquia" name="parroquia" required></select>
                            <div class="form-text text-danger small d-none" id="parroquia-error"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="tipo_via" class="form-label">
                                <i class="material-icons me-1">signpost</i>Tipo de vía<span class="text-danger"> *</span>
                            </label>
                            <select class="form-select" id="tipo_via" name="tipo_via" required>
                                <option value="">Seleccionar...</option>
                                <option value="calle">Calle</option>
                                <option value="avenida">Avenida</option>
                                <option value="carrera">Carrera</option>
                                <option value="vereda">Vereda</option>
                                <option value="troncal">Troncal</option>
                                <option value="autopista">Autopista</option>
                                <option value="otro">Otro</option>
                            </select>
                            <div class="form-text text-danger small d-none" id="tipo_via-error"></div>
                        </div>
                        <div class="col-md-8">
                            <label for="nombre_via" class="form-label">
                                <i class="material-icons me-1">edit_road</i>Nombre de vía<span class="text-danger"> *</span>
                            </label>
                            <input type="text" class="form-control" id="nombre_via" name="nombre_via" maxlength="120" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="nombre_via-error"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="numero_piso_apto" class="form-label">
                                <i class="material-icons me-1">home</i>Número / Piso / Apto
                            </label>
                            <input type="text" class="form-control" id="numero_piso_apto" name="numero_piso_apto" maxlength="40" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="numero_piso_apto-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="urbanizacion_sector" class="form-label">
                                <i class="material-icons me-1">holiday_village</i>Urbanización / Sector
                            </label>
                            <input type="text" class="form-control" id="urbanizacion_sector" name="urbanizacion_sector" maxlength="120" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="urbanizacion_sector-error"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="referencia" class="form-label">
                                <i class="material-icons me-1">place</i>Punto de referencia
                            </label>
                            <input type="text" class="form-control" id="referencia" name="referencia" maxlength="200" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="referencia-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="direccion" class="form-label">
                                <i class="material-icons me-1">location_on</i>Dirección específica<span class="text-danger"> *</span>
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
document.addEventListener('DOMContentLoaded', async function() {
    const estadosSelect = document.getElementById('estado');
    const municipiosSelect = document.getElementById('municipio');
    const parroquiasSelect = document.getElementById('parroquia');
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
    const tipoViaSelect = document.getElementById('tipo_via');
    const nombreViaInput = document.getElementById('nombre_via');
    const numeroPisoAptoInput = document.getElementById('numero_piso_apto');
    const urbanizacionInput = document.getElementById('urbanizacion_sector');
    const referenciaInput = document.getElementById('referencia');
    const direccionInput = document.getElementById('direccion');
    const form = document.getElementById('formCrearPersona');
    if (!estadosSelect || !municipiosSelect || !parroquiasSelect) return;

    let venezuelaGeo = {};
    const venezuelaDataUrl = "{{ asset('data/venezuela.json') }}";
    const defaultEstado = 'Distrito Capital';

    const normalizeData = (data = []) => {
        const map = {};
        data.forEach((estado) => {
            if (!estado?.estado) return;
            const municipiosMap = {};
            (estado.municipios || []).forEach((m) => {
                if (!m?.municipio) return;
                const parroquiasOrdenadas = (m.parroquias || []).slice().sort((a, b) => a.localeCompare(b, 'es'));
                municipiosMap[m.municipio] = parroquiasOrdenadas;
            });
            map[estado.estado] = municipiosMap;
        });
        return map;
    };

    const loadVenezuelaData = async () => {
        try {
            const res = await fetch(venezuelaDataUrl, { cache: 'no-store' });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const json = await res.json();
            venezuelaGeo = normalizeData(json);
        } catch (error) {
            console.error('No se pudo cargar venezuela.json', error);
            venezuelaGeo = {};
        }
    };

    const renderEstados = (estados) => {
        estadosSelect.innerHTML = '';
        if (!estados.length) {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = 'Sin estados disponibles';
            estadosSelect.appendChild(opt);
            return;
        }
        estados.forEach(estado => {
            const opt = document.createElement('option');
            opt.value = estado;
            opt.textContent = estado;
            estadosSelect.appendChild(opt);
        });
    };

    const renderMunicipios = (estado) => {
        municipiosSelect.innerHTML = '';
        const municipios = venezuelaGeo[estado] ? Object.keys(venezuelaGeo[estado]) : [];
        if (!municipios.length) {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = 'Sin municipios disponibles';
            municipiosSelect.appendChild(opt);
            parroquiasSelect.innerHTML = '';
            const optP = document.createElement('option');
            optP.value = '';
            optP.textContent = 'Sin parroquias disponibles';
            parroquiasSelect.appendChild(optP);
            return;
        }
        municipios.forEach(m => {
            const opt = document.createElement('option');
            opt.value = m;
            opt.textContent = m;
            municipiosSelect.appendChild(opt);
        });
    };

    const renderParroquias = (estado, municipio) => {
        parroquiasSelect.innerHTML = '';
        const parroquias = venezuelaGeo[estado]?.[municipio] || [];
        if (!parroquias.length) {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = 'Sin parroquias disponibles';
            parroquiasSelect.appendChild(opt);
            return;
        }
        parroquias.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p;
            opt.textContent = p;
            parroquiasSelect.appendChild(opt);
        });
    };

    const setInicial = () => {
        const estados = Object.keys(venezuelaGeo).sort((a, b) => a.localeCompare(b, 'es'));
        renderEstados(estados);
        const initialEstado = estados.includes(defaultEstado) ? defaultEstado : (estados[0] || '');
        estadosSelect.value = initialEstado;
        renderMunicipios(initialEstado);
        const firstMunicipio = municipiosSelect.options[0]?.value || '';
        municipiosSelect.value = firstMunicipio;
        renderParroquias(initialEstado, firstMunicipio);
        const firstParroquia = parroquiasSelect.options[0]?.value || '';
        parroquiasSelect.value = firstParroquia;
    };

    await loadVenezuelaData();
    setInicial();

    estadosSelect.addEventListener('change', (e) => {
        renderMunicipios(e.target.value);
        const firstMunicipio = municipiosSelect.options[0]?.value || '';
        municipiosSelect.value = firstMunicipio;
        renderParroquias(e.target.value, firstMunicipio);
        const firstParroquia = parroquiasSelect.options[0]?.value || '';
        parroquiasSelect.value = firstParroquia;
    });

    municipiosSelect.addEventListener('change', (e) => {
        const estado = estadosSelect.value;
        renderParroquias(estado, e.target.value);
        const firstParroquia = parroquiasSelect.options[0]?.value || '';
        parroquiasSelect.value = firstParroquia;
    });

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

    const validateEstado = () => {
        if (!estadosSelect) return;
        const v = estadosSelect.value.trim();
        if (!v) return showError(estadosSelect, 'Selecciona el estado');
        showSuccess(estadosSelect);
    };

    const validateMunicipio = () => {
        if (!municipiosSelect) return;
        const v = municipiosSelect.value.trim();
        if (!v) return showError(municipiosSelect, 'Selecciona el municipio');
        showSuccess(municipiosSelect);
    };

    const validateParroquia = () => {
        if (!parroquiasSelect) return;
        const v = parroquiasSelect.value.trim();
        if (!v) return showError(parroquiasSelect, 'Selecciona la parroquia');
        showSuccess(parroquiasSelect);
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

    const validateTipoVia = () => {
        if (!tipoViaSelect) return;
        const v = tipoViaSelect.value.trim();
        if (!v) return showError(tipoViaSelect, 'Selecciona el tipo de vía');
        showSuccess(tipoViaSelect);
    };

    const validateNombreVia = () => {
        if (!nombreViaInput) return;
        const v = nombreViaInput.value.trim();
        if (!v) return showError(nombreViaInput, 'Ingresa el nombre de la vía');
        if (!onlyAlnum(v)) return showError(nombreViaInput, 'Solo letras, números y espacios');
        showSuccess(nombreViaInput);
    };

    const validateUrbanizacion = () => {
        if (!urbanizacionInput) return;
        const v = urbanizacionInput.value.trim();
        if (!v) { clearError(urbanizacionInput); return; }
        if (!onlyAlnum(v)) return showError(urbanizacionInput, 'Solo letras, números y espacios');
        showSuccess(urbanizacionInput);
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
    blockInvalidChars(nombreViaInput, /[a-zA-Z0-9À-ÿ\s.'-]/g);
    blockInvalidChars(numeroPisoAptoInput, /[a-zA-Z0-9#\-\s]/g);
    blockInvalidChars(urbanizacionInput, /[a-zA-Z0-9À-ÿ\s.'-]/g);
    blockInvalidChars(referenciaInput, /[a-zA-Z0-9À-ÿ\s.'-]/g);

    nombresInput?.addEventListener('blur', validateNombre);
    apellidosInput?.addEventListener('blur', validateApellido);
    razonInput?.addEventListener('blur', validateRazon);
    comercialInput?.addEventListener('blur', validateComercial);
    docInput?.addEventListener('blur', validateDocumento);
    emailInput?.addEventListener('blur', validateEmail);
    estadosSelect?.addEventListener('change', validateEstado);
    municipiosSelect?.addEventListener('change', validateMunicipio);
    parroquiasSelect?.addEventListener('change', validateParroquia);
    telInput?.addEventListener('blur', validateTelefono);
    telAltInput?.addEventListener('blur', validateTelAlt);
    tipoViaSelect?.addEventListener('change', validateTipoVia);
    nombreViaInput?.addEventListener('blur', validateNombreVia);
    urbanizacionInput?.addEventListener('blur', validateUrbanizacion);
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
        validateEstado();
        validateMunicipio();
        validateParroquia();
        validateTelefono();
        validateTelAlt();
        validateTipoVia();
        validateNombreVia();
        validateUrbanizacion();
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