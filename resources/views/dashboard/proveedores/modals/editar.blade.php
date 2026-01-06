<div class="modal fade" id="editarProveedorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title text-white"><i class="material-icons me-2">edit</i>Editar Proveedor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarProveedor" method="POST" action="#">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Persona / Contacto <span class="text-danger">*</span></label>
                            <select name="persona_id" id="editPersona" class="form-select" required>
                                <option value="">Seleccione persona</option>
                                @foreach($personas as $persona)
                                    @php
                                        $docType = strtoupper($persona->tipo_documento);
                                        $tipoPersona = $docType === 'J' ? 'Jurídico' : 'Natural';
                                    @endphp
                                    <option value="{{ $persona->id }}">{{ $docType }} ({{ $tipoPersona }}) - {{ $persona->nombre_completo }} | {{ $persona->telefono ?? 'Sin teléfono' }} | {{ $persona->email ?? 'Sin correo' }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">El contacto es la persona seleccionada. Usa instrucciones si necesitas detalles adicionales.</small>
                            <span class="validation-feedback validation-error" style="display: none;"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Categorías (múltiples) <span class="text-danger">*</span></label>
                            <div class="border rounded p-2" id="editarSelectorCategorias" data-target-input="#editarTiposSeleccionados" data-target-summary="#editarResumenCategorias">
                                <div class="d-flex flex-wrap gap-1 mb-2 categorias-seleccionadas"></div>
                                <div class="d-flex flex-wrap gap-1 categorias-disponibles">
                                    @foreach($tiposProveedores as $tipo)
                                        <button type="button" class="btn btn-sm btn-outline-success categoria-item" data-id="{{ $tipo->id }}" data-nombre="{{ $tipo->nombre_tipo }}">{{ $tipo->nombre_tipo }}</button>
                                    @endforeach
                                </div>
                            </div>
                            <div id="editarTiposSeleccionados" class="d-none"></div>
                            <input type="text" class="form-control d-none" id="editarResumenCategorias" placeholder="Resumen de categorías" readonly>
                            <span class="validation-feedback validation-error" style="display: none;"></span>
                        </div>
                    </div>

                    <div class="mb-3" id="productosWrapperEdit">
                        <label class="form-label fw-bold">Productos / Servicios</label> <span class="text-danger">*</span>
                        <div class="d-flex gap-2 mb-2">
                            <input type="text" id="productoInputEdit" class="form-control" placeholder="Escribe y presiona Enter" autocomplete="off">
                            <button type="button" class="btn btn-success" id="addProductoBtnEdit">
                                <i class="material-icons align-middle" style="font-size:18px">add</i>
                                Añadir
                            </button>
                        </div>
                        <div class="d-flex flex-wrap gap-2" id="productosChipsEdit"></div>
                        <div id="productosHiddenInputsEdit" class="d-none"></div>
                        <span class="validation-feedback validation-error" style="display: none;"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="material-icons me-2">close</i>Cancelar</button>
                    <button type="submit" class="btn btn-warning text-white"><i class="material-icons me-2">save</i>Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectorCategorias = document.getElementById('editarSelectorCategorias');
    const resumenCategorias = document.getElementById('editarResumenCategorias');
    const tiposSeleccionadosInput = document.getElementById('editarTiposSeleccionados');
    const productoInput = document.getElementById('productoInputEdit');
    const addProductoBtn = document.getElementById('addProductoBtnEdit');
    const productosChips = document.getElementById('productosChipsEdit');
    const productosHiddenInputs = document.getElementById('productosHiddenInputsEdit');

    // Selección de categorías (igual a crear)
    if (selectorCategorias) {
        selectorCategorias.addEventListener('click', function(e) {
            if (!e.target.classList.contains('categoria-item')) return;
            e.preventDefault();
            e.target.classList.toggle('btn-outline-success');
            e.target.classList.toggle('btn-success');

            const id = e.target.dataset.id;
            const nombre = e.target.dataset.nombre;

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'tipos_proveedores[]';
            hidden.value = id;
            hidden.dataset.id = id;

            // Toggle seleccionado
            const existing = tiposSeleccionadosInput?.querySelector(`input[data-id="${id}"]`);
            if (existing) {
                existing.remove();
            } else {
                tiposSeleccionadosInput?.appendChild(hidden);
            }

            const selected = Array.from(tiposSeleccionadosInput?.querySelectorAll('input') || [])
                .map(el => ({ id: el.value, nombre }));
            resumenCategorias.value = selected.map(s => s.nombre).join(', ');
        });
    }

    // Chips de productos/servicios
    const addProducto = (value) => {
        const val = (value ?? '').trim();
        if (!val) return;
        if (!productosHiddenInputs || !productosChips) return;

        const key = val.toLowerCase();
        if (productosHiddenInputs.querySelector(`input[data-key="${key}"]`)) {
            if (productoInput) productoInput.value = '';
            return;
        }

        const chip = document.createElement('span');
        chip.className = 'badge bg-light text-dark border d-flex align-items-center gap-1 px-2 py-2';
        chip.dataset.key = key;
        chip.innerHTML = `<span>${val}</span><button type="button" class="btn btn-sm btn-link text-danger p-0" aria-label="Quitar">&times;</button>`;

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'productos_servicios[]';
        hidden.value = val;
        hidden.dataset.key = key;

        chip.querySelector('button').addEventListener('click', () => {
            chip.remove();
            hidden.remove();
        });

        productosChips.appendChild(chip);
        productosHiddenInputs.appendChild(hidden);
        if (productoInput) productoInput.value = '';
    };

    addProductoBtn?.addEventListener('click', () => addProducto(productoInput?.value));
    productoInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addProducto(productoInput.value);
        }
    });

    // Helper global para precargar productos desde el JS externo
    window.setEditProductos = function(items = []) {
        if (productosChips) productosChips.innerHTML = '';
        if (productosHiddenInputs) productosHiddenInputs.innerHTML = '';
        if (!Array.isArray(items)) return;
        items.forEach(val => addProducto(String(val)));
    };
});
</script>
@endpush
