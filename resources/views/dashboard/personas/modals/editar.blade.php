<!-- resources/views/dashboard/personas/modals/editar.blade.php -->
<style>
    /* Color personalizado para radios de Tipo de Persona */
    .tipo-persona-edit .form-check-input[type='radio']:checked {
        background-color: #62e91e;
        border-color: #62e91e;
        box-shadow: 0 0 0 0.2rem rgba(98, 233, 30, 0.25);
    }
</style>
<div class="modal fade" id="editarPersonaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title text-white">
                    <i class="material-icons me-2 text-white">edit</i>Editar Persona
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="#" id="formEditarPersona">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Tipo de Persona -->
                    <div class="row mb-3 tipo-persona-edit">
                        <div class="col-12">
                            <label class="form-label fw-bold">Tipo de Persona <span class="text-danger">*</span></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo" id="editTipoNatural" value="natural" checked>
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

                    <!-- Campos para Persona Natural -->
                    <div class="campo-natural-edit">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editNombres" class="form-label">
                                    <i class="material-icons me-1">badge</i>Nombres <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="editNombres" name="nombres" maxlength="60" autocomplete="off">
                                <div class="form-text text-danger small d-none" id="editNombres-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="editApellidos" class="form-label">
                                    <i class="material-icons me-1">badge</i>Apellidos <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="editApellidos" name="apellidos" maxlength="60" autocomplete="off">
                                <div class="form-text text-danger small d-none" id="editApellidos-error"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Campos para Persona Jurídica -->
                    <div class="campo-juridica-edit d-none">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editRazonSocial" class="form-label">
                                    <i class="material-icons me-1">business</i>Razón Social<span class="text-danger"> *</span>
                                </label>
                                <input type="text" class="form-control" id="editRazonSocial" name="razon_social" maxlength="120" autocomplete="off">
                                <div class="form-text text-danger small d-none" id="editRazonSocial-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="editNombreComercial" class="form-label">
                                    <i class="material-icons me-1">storefront</i>Nombre Comercial
                                </label>
                                <input type="text" class="form-control" id="editNombreComercial" name="nombre_comercial" maxlength="120" autocomplete="off">
                                <div class="form-text text-danger small d-none" id="editNombreComercial-error"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Documento -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="editTipoDocumento" class="form-label">
                                <i class="material-icons me-1">badge</i>Tipo Documento<span class="text-danger"> *</span>
                            </label>
                            <div class="input-group">
                                <select class="form-select" id="editTipoDocumento" name="tipo_documento" required>
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
                            <label for="editDocumento" class="form-label">
                                <i class="material-icons me-1">numbers</i>Número de Documento<span class="text-danger"> *</span>
                            </label>
                            <input type="text" class="form-control" id="editDocumento" name="documento" required maxlength="12" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="editDocumento-error"></div>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editTelefono" class="form-label">
                                <i class="material-icons me-1">phone</i>Teléfono Principal<span class="text-danger"> *</span>
                            </label> 
                            <div class="input-group">
                                <select class="form-select" id="editTelefonoPrefijo" aria-label="Prefijo venezolano" style="max-width: 55px;">
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
                                <input type="text" class="form-control" id="editTelefono" name="telefono" maxlength="7" autocomplete="off" placeholder="Número" aria-label="Número">
                            </div>
                            <div class="form-text text-danger small d-none" id="editTelefono-error"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="editToggleTelAlt">
                                <label class="form-check-label" for="editToggleTelAlt">
                                    <i class="material-icons me-1 align-middle">phone_in_talk</i>Agregar teléfono alternativo
                                </label>
                            </div>
                            <div id="editTelAltWrapper" class="d-none">
                                <div class="input-group mb-1">
                                    <select class="form-select" id="editTelefonoAlternativoPrefijo" aria-label="Prefijo alternativo" style="max-width: 55px;">
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
                                    <input type="text" class="form-control" id="editTelefonoAlternativo" name="telefono_alternativo" maxlength="7" autocomplete="off" placeholder="Número alternativo" aria-label="Número alternativo">
                                </div>
                                <div class="form-text text-danger small d-none" id="editTelefonoAlternativo-error"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Email y ubicación -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editEmail" class="form-label">
                                <i class="material-icons me-1">mail</i>Correo Electrónico<span class="text-danger"> *</span>
                            </label>
                            <input type="email" class="form-control" id="editEmail" name="email" maxlength="120" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="editEmail-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="editEstado" class="form-label">
                                <i class="material-icons me-1">map</i>Estado<span class="text-danger"> *</span>
                            </label>
                            <select class="form-select" id="editEstado" name="estado" required></select>
                            <div class="form-text text-danger small d-none" id="editEstado-error"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editMunicipio" class="form-label">
                                <i class="material-icons me-1">map</i>Municipio<span class="text-danger"> *</span>
                            </label>
                            <select class="form-select" id="editMunicipio" name="municipio" required></select>
                            <div class="form-text text-danger small d-none" id="editMunicipio-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="editParroquia" class="form-label">
                                <i class="material-icons me-1">location_city</i>Parroquia<span class="text-danger"> *</span>
                            </label>
                            <select class="form-select" id="editParroquia" name="parroquia" required></select>
                            <div class="form-text text-danger small d-none" id="editParroquia-error"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="editTipoVia" class="form-label">
                                <i class="material-icons me-1">signpost</i>Tipo de vía<span class="text-danger"> *</span>
                            </label>
                            <select class="form-select" id="editTipoVia" name="tipo_via" required>
                                <option value="">Seleccionar...</option>
                                <option value="calle">Calle</option>
                                <option value="avenida">Avenida</option>
                                <option value="carrera">Carrera</option>
                                <option value="vereda">Vereda</option>
                                <option value="troncal">Troncal</option>
                                <option value="autopista">Autopista</option>
                                <option value="otro">Otro</option>
                            </select>
                            <div class="form-text text-danger small d-none" id="editTipoVia-error"></div>
                        </div>
                        <div class="col-md-8">
                            <label for="editNombreVia" class="form-label">
                                <i class="material-icons me-1">edit_road</i>Nombre de vía<span class="text-danger"> *</span>
                            </label>
                            <input type="text" class="form-control" id="editNombreVia" name="nombre_via" maxlength="120" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="editNombreVia-error"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editNumeroPisoApto" class="form-label">
                                <i class="material-icons me-1">home</i>Número / Piso / Apto <span class="text-danger"> *</span>
                            </label>
                            <input type="text" class="form-control" id="editNumeroPisoApto" name="numero_piso_apto" maxlength="40" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="editNumeroPisoApto-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="editUrbanizacionSector" class="form-label">
                                <i class="material-icons me-1">holiday_village</i>Urbanización / Sector <span class="text-danger"> *</span>
                            </label>
                            <input type="text" class="form-control" id="editUrbanizacionSector" name="urbanizacion_sector" maxlength="120" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="editUrbanizacionSector-error"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editReferencia" class="form-label">
                                <i class="material-icons me-1">place</i>Punto de referencia
                            </label>
                            <input type="text" class="form-control" id="editReferencia" name="referencia" maxlength="200" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="editReferencia-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="editDireccion" class="form-label">
                                <i class="material-icons me-1">location_on</i>Dirección específica<span class="text-danger"> *</span>
                            </label>
                            <textarea class="form-control" id="editDireccion" name="direccion" rows="2" maxlength="240" autocomplete="off"></textarea>
                            <div class="form-text text-danger small d-none" id="editDireccion-error"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="material-icons me-2">close</i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="material-icons me-2">save</i>Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const modalEl = document.getElementById('editarPersonaModal');
    const estadosSelect = document.getElementById('editEstado');
    const municipiosSelect = document.getElementById('editMunicipio');
    const parroquiasSelect = document.getElementById('editParroquia');
    const nombresInput = document.getElementById('editNombres');
    const apellidosInput = document.getElementById('editApellidos');
    const razonInput = document.getElementById('editRazonSocial');
    const comercialInput = document.getElementById('editNombreComercial');
    const docInput = document.getElementById('editDocumento');
    const tipoDocSelect = document.getElementById('editTipoDocumento');
    const emailInput = document.getElementById('editEmail');
    const telInput = document.getElementById('editTelefono');
    const telPrefijo = document.getElementById('editTelefonoPrefijo');
    const telAltInput = document.getElementById('editTelefonoAlternativo');
    const telAltPrefijo = document.getElementById('editTelefonoAlternativoPrefijo');
    const telAltToggle = document.getElementById('editToggleTelAlt');
    const telAltWrapper = document.getElementById('editTelAltWrapper');
    const tipoViaSelect = document.getElementById('editTipoVia');
    const nombreViaInput = document.getElementById('editNombreVia');
    const numeroPisoAptoInput = document.getElementById('editNumeroPisoApto');
    const urbanizacionInput = document.getElementById('editUrbanizacionSector');
    const referenciaInput = document.getElementById('editReferencia');
    const direccionInput = document.getElementById('editDireccion');
    const naturalWrapper = document.querySelector('.campo-natural-edit');
    const juridicaWrapper = document.querySelector('.campo-juridica-edit');
    const tipoNatural = document.getElementById('editTipoNatural');
    const tipoJuridica = document.getElementById('editTipoJuridica');
    const form = document.getElementById('formEditarPersona');
    if (!modalEl || !form) return;

    let venezuelaGeo = {};
    let dataLoaded = false;
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
        if (dataLoaded) return;
        try {
            const res = await fetch(venezuelaDataUrl, { cache: 'no-store' });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const json = await res.json();
            venezuelaGeo = normalizeData(json);
        } catch (error) {
            console.error('No se pudo cargar venezuela.json', error);
            venezuelaGeo = {};
        } finally {
            dataLoaded = true;
        }
    };

    const renderEstados = (estados) => {
        if (!estadosSelect) return;
        estadosSelect.innerHTML = '';
        if (!estados.length) {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = 'Sin estados disponibles';
            estadosSelect.appendChild(opt);
            municipiosSelect.innerHTML = '';
            parroquiasSelect.innerHTML = '';
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
        if (!municipiosSelect) return;
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
        if (!parroquiasSelect) return;
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

    const setUbicacion = (estadoSel = '', municipioSel = '', parroquiaSel = '') => {
        const estados = Object.keys(venezuelaGeo).sort((a, b) => a.localeCompare(b, 'es'));
        renderEstados(estados);
        const estadoVal = estados.includes(estadoSel) ? estadoSel : (estados.includes(defaultEstado) ? defaultEstado : (estados[0] || ''));
        estadosSelect.value = estadoVal;
        renderMunicipios(estadoVal);
        const municipios = Array.from(municipiosSelect.options).map(o => o.value);
        const municipioVal = municipios.includes(municipioSel) ? municipioSel : (municipios[0] || '');
        municipiosSelect.value = municipioVal;
        renderParroquias(estadoVal, municipioVal);
        const parroquias = Array.from(parroquiasSelect.options).map(o => o.value);
        const parroquiaVal = parroquias.includes(parroquiaSel) ? parroquiaSel : (parroquias[0] || '');
        parroquiasSelect.value = parroquiaVal;
    };

    await loadVenezuelaData();
    setUbicacion();

    estadosSelect?.addEventListener('change', (e) => {
        renderMunicipios(e.target.value);
        const firstMunicipio = municipiosSelect.options[0]?.value || '';
        municipiosSelect.value = firstMunicipio;
        renderParroquias(e.target.value, firstMunicipio);
        const firstParroquia = parroquiasSelect.options[0]?.value || '';
        parroquiasSelect.value = firstParroquia;
    });

    municipiosSelect?.addEventListener('change', (e) => {
        const estado = estadosSelect.value;
        renderParroquias(estado, e.target.value);
        const firstParroquia = parroquiasSelect.options[0]?.value || '';
        parroquiasSelect.value = firstParroquia;
    });

    const toggleTipo = (tipo) => {
        if (tipo === 'juridica') {
            juridicaWrapper?.classList.remove('d-none');
            naturalWrapper?.classList.add('d-none');
        } else {
            juridicaWrapper?.classList.add('d-none');
            naturalWrapper?.classList.remove('d-none');
        }
    };

    tipoNatural?.addEventListener('change', () => toggleTipo('natural'));
    tipoJuridica?.addEventListener('change', () => toggleTipo('juridica'));

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
    const digitsOnly = (val = '') => String(val || '').replace(/\D/g, '');
    const formatDocumento = (input) => {
        if (!input) return;
        input.addEventListener('input', () => {
            const digits = digitsOnly(input.value).slice(0, input.maxLength || 20);
            const formatted = digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = formatted;
        });
    };
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
        if (!v && document.getElementById('editTipoJuridica')?.checked) return showError(razonInput, 'Ingresa razón social');
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
        const digits = digitsOnly(docInput.value);
        if (!digits) return showError(docInput, 'Ingresa el documento');
        if (digits.length < 6 || digits.length > docInput.maxLength) return showError(docInput, `Entre 6 y ${docInput.maxLength} dígitos`);
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
    formatDocumento(docInput);
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

    const splitPhone = (val = '') => {
        const digits = String(val || '').replace(/\D/g, '');
        if (digits.length >= 11) return { pref: digits.slice(0, 4), num: digits.slice(4, 11) };
        if (digits.length >= 4) return { pref: digits.slice(0, 4), num: digits.slice(4) };
        return { pref: '', num: digits };
    };

    const applyPersonaPhones = (persona) => {
        const { pref, num } = splitPhone(persona?.telefono || '');
        if (telPrefijo) telPrefijo.value = pref || telPrefijo.value;
        if (telInput) telInput.value = num || '';

        const alt = splitPhone(persona?.telefono_alternativo || '');
        if (alt.num) {
            telAltToggle && (telAltToggle.checked = true);
            telAltWrapper && telAltWrapper.classList.remove('d-none');
            if (telAltPrefijo) telAltPrefijo.value = alt.pref || telAltPrefijo.value;
            if (telAltInput) telAltInput.value = alt.num;
        } else {
            telAltToggle && (telAltToggle.checked = false);
            telAltWrapper && telAltWrapper.classList.add('d-none');
            if (telAltInput) telAltInput.value = '';
        }
    };

    const applyPersonaUbicacion = (persona) => {
        const estadoPreferido = persona?.estado || '';
        const municipioPreferido = persona?.municipio || persona?.ciudad || '';
        const parroquiaPreferida = persona?.parroquia || '';
        setUbicacion(estadoPreferido, municipioPreferido, parroquiaPreferida);
    };

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

        if (docInput) {
            docInput.value = digitsOnly(docInput.value);
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

    modalEl.addEventListener('show.bs.modal', async (event) => {
        await loadVenezuelaData();
        const trigger = event.relatedTarget;
        const personaData = trigger?.getAttribute('data-persona');
        const persona = personaData ? JSON.parse(personaData) : null;
        if (!persona) return;

        const tipo = persona.tipo === 'juridica' ? 'juridica' : 'natural';
        if (tipo === 'juridica') {
            tipoJuridica && (tipoJuridica.checked = true);
            tipoNatural && (tipoNatural.checked = false);
        } else {
            tipoNatural && (tipoNatural.checked = true);
            tipoJuridica && (tipoJuridica.checked = false);
        }
        toggleTipo(tipo);

        if (nombresInput) nombresInput.value = persona.nombres || '';
        if (apellidosInput) apellidosInput.value = persona.apellidos || '';
        if (razonInput) razonInput.value = persona.razon_social || '';
        if (comercialInput) comercialInput.value = persona.nombre_comercial || '';
        if (tipoDocSelect) tipoDocSelect.value = persona.tipo_documento || '';
        if (docInput) {
            const digits = digitsOnly(persona.documento || '').slice(0, docInput.maxLength);
            docInput.value = digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        if (emailInput) emailInput.value = persona.email || '';
        if (tipoViaSelect) tipoViaSelect.value = persona.tipo_via || '';
        if (nombreViaInput) nombreViaInput.value = persona.nombre_via || '';
        if (numeroPisoAptoInput) numeroPisoAptoInput.value = persona.numero_piso_apto || '';
        if (urbanizacionInput) urbanizacionInput.value = persona.urbanizacion_sector || '';
        if (referenciaInput) referenciaInput.value = persona.referencia || '';
        if (direccionInput) direccionInput.value = persona.direccion || '';

        applyPersonaPhones(persona);
        applyPersonaUbicacion(persona);
    });
});
</script>
@endpush
