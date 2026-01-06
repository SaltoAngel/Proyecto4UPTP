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
                    <div class="row mb-3 tipo-persona-edit">
                        <div class="col-12">
                            <label class="form-label fw-bold">Tipo de Persona <span class="text-danger">*</span></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo" id="editTipoNatural" value="natural">
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

                    <div class="campo-natural-edit">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editNombres" class="form-label"><i class="material-icons me-1">badge</i>Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editNombres" name="nombres" maxlength="60" autocomplete="off">
                                <div class="form-text text-danger small d-none" id="editNombres-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="editApellidos" class="form-label"><i class="material-icons me-1">badge</i>Apellidos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editApellidos" name="apellidos" maxlength="60" autocomplete="off">
                                <div class="form-text text-danger small d-none" id="editApellidos-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="campo-juridica-edit" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editRazonSocial" class="form-label"><i class="material-icons me-1">business</i>Razón Social <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editRazonSocial" name="razon_social" maxlength="120" autocomplete="off">
                                <div class="form-text text-danger small d-none" id="editRazonSocial-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="editNombreComercial" class="form-label"><i class="material-icons me-1">storefront</i>Nombre Comercial <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editNombreComercial" name="nombre_comercial" maxlength="120" autocomplete="off">
                                <div class="form-text text-danger small d-none" id="editNombreComercial-error"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="editTipoDocumento" class="form-label"><i class="material-icons me-1">badge</i>Tipo Documento <span class="text-danger">*</span></label>
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
                            <label for="editDocumento" class="form-label"><i class="material-icons me-1">numbers</i>Número de Documento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editDocumento" name="documento" required maxlength="12" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="editDocumento-error"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editTelefono" class="form-label"><i class="material-icons me-1">phone</i>Teléfono Principal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-select" id="editTelefonoPrefijo" aria-label="Prefijo venezolano">
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
                                    <select class="form-select" id="editTelefonoAlternativoPrefijo" aria-label="Prefijo alternativo">
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

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editEmail" class="form-label"><i class="material-icons me-1">mail</i>Correo Electrónico</label>
                            <input type="email" class="form-control" id="editEmail" name="email" maxlength="120" autocomplete="off">
                            <div class="form-text text-danger small d-none" id="editEmail-error"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="editEstado" class="form-label"><i class="material-icons me-1">map</i>Estado/Provincia</label>
                            <select class="form-select" id="editEstado" name="estado" required></select>
                        </div>
                        <div class="col-md-3">
                            <label for="editCiudad" class="form-label"><i class="material-icons me-1">location_city</i>Ciudad</label>
                            <select class="form-select" id="editCiudad" name="ciudad" required></select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="editDireccion" class="form-label"><i class="material-icons me-1">location_on</i>Dirección Completa</label>
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
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('editarPersonaModal');
    const estadosSelect = document.getElementById('editEstado');
    const ciudadesSelect = document.getElementById('editCiudad');
    const nombresInput = document.getElementById('editNombres');
    const apellidosInput = document.getElementById('editApellidos');
    const razonInput = document.getElementById('editRazonSocial');
    const comercialInput = document.getElementById('editNombreComercial');
    const docInput = document.getElementById('editDocumento');
    const emailInput = document.getElementById('editEmail');
    const telInput = document.getElementById('editTelefono');
    const telPrefijo = document.getElementById('editTelefonoPrefijo');
    const telAltInput = document.getElementById('editTelefonoAlternativo');
    const telAltPrefijo = document.getElementById('editTelefonoAlternativoPrefijo');
    const telAltToggle = document.getElementById('editToggleTelAlt');
    const telAltWrapper = document.getElementById('editTelAltWrapper');
    const direccionInput = document.getElementById('editDireccion');
    const form = document.getElementById('formEditarPersona');
    if (!modalEl) return;

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
    let estadosCargados = false;
    let estadoPreferido = '';
    let ciudadPreferida = '';

    const renderEstados = (estados) => {
        if (!estadosSelect) return;
        estadosSelect.innerHTML = '';
        estados.forEach(estado => {
            const opt = document.createElement('option');
            opt.value = estado;
            opt.textContent = estado;
            estadosSelect.appendChild(opt);
        });
    };

    const loadCiudades = (estado) => {
        if (!ciudadesSelect) return;
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

    const setEstadoCiudad = (estadoSel, ciudadSel) => {
        if (!estadosSelect) return;
        const estados = Array.from(estadosSelect.options).map(o => o.value);
        const estadoVal = estados.includes(estadoSel) ? estadoSel : (estados.includes(defaultEstado) ? defaultEstado : (estados[0] || ''));
        estadosSelect.value = estadoVal;
        loadCiudades(estadoVal);
        if (ciudadesSelect && ciudadSel) {
            const ciudades = Array.from(ciudadesSelect.options).map(o => o.value);
            if (ciudades.includes(ciudadSel)) ciudadesSelect.value = ciudadSel;
        }
    };

    const cargarEstados = () => {
        return fetch("{{ route('geo.ve') }}")
            .then(r => { if (!r.ok) throw new Error('geo ve no disponible'); return r.json(); })
            .then(json => {
                const features = Array.isArray(json?.features) ? json.features : [];
                const estados = [...new Set(features.map(f => getFeatureName(f.properties)).filter(Boolean))].sort();
                if (!estados.length) throw new Error('sin estados');
                renderEstados(estados);
                estadosCargados = true;
                setEstadoCiudad(estadoPreferido || defaultEstado, ciudadPreferida || '');
            })
            .catch(() => {
                const estadosFallback = Object.keys(stateCities).sort();
                renderEstados(estadosFallback);
                estadosCargados = true;
                setEstadoCiudad(estadoPreferido || defaultEstado, ciudadPreferida || '');
            });
    };

    if (estadosSelect && ciudadesSelect) {
        cargarEstados();
        estadosSelect.addEventListener('change', (e) => loadCiudades(e.target.value));
    }

    // Validación y bloqueo de caracteres
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

    const applyPersonaEstadoCiudad = (persona) => {
        estadoPreferido = persona?.estado || '';
        ciudadPreferida = persona?.ciudad || '';
        if (estadosCargados) setEstadoCiudad(estadoPreferido || defaultEstado, ciudadPreferida || '');
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

    modalEl.addEventListener('show.bs.modal', (event) => {
        const trigger = event.relatedTarget;
        const personaData = trigger?.getAttribute('data-persona');
        const persona = personaData ? JSON.parse(personaData) : null;
        if (!persona) return;

        applyPersonaPhones(persona);
        applyPersonaEstadoCiudad(persona);
        if (emailInput) emailInput.value = persona.email || '';
        if (direccionInput) direccionInput.value = persona.direccion || '';
    });
});
</script>
@endpush
