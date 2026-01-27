@if(isset($materia))
<form id="editMateriaForm" action="{{ route('materias-primas.update', $materia->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="codigo_edit" class="form-label">Código <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="codigo_edit" name="codigo" 
                           value="{{ $materia->codigo }}" required maxlength="50">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="categoria_id_edit" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria_id_edit" name="categoria_id">
                        <option value="">Seleccionar categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ $materia->categoria_id == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="descripcion_edit" class="form-label">Descripción <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="descripcion_edit" name="descripcion" 
                   value="{{ $materia->descripcion }}" required maxlength="150">
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nombre_comercial_edit" class="form-label">Nombre Comercial</label>
                    <input type="text" class="form-control" id="nombre_comercial_edit" name="nombre_comercial" 
                           value="{{ $materia->nombre_comercial }}" maxlength="150">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nombre_cientifico_edit" class="form-label">Nombre Científico</label>
                    <input type="text" class="form-control" id="nombre_cientifico_edit" name="nombre_cientifico" 
                           value="{{ $materia->nombre_cientifico }}" maxlength="150">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="comentario_edit" class="form-label">Comentarios</label>
            <textarea class="form-control" id="comentario_edit" name="comentario" rows="2">{{ $materia->comentario }}</textarea>
        </div>

        <hr class="my-4">
        <h6 class="mb-3 text-primary">
            <i class="material-icons opacity-10 me-2">inventory</i>
            Parámetros de Inventario
        </h6>

        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="stock_minimo_edit" class="form-label">Stock Mínimo (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.001" class="form-control" id="stock_minimo_edit" 
                           name="inventario[stock_minimo]" value="{{ $materia->inventario->stock_minimo ?? 0 }}" required min="0">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="stock_maximo_edit" class="form-label">Stock Máximo (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.001" class="form-control" id="stock_maximo_edit" 
                           name="inventario[stock_maximo]" value="{{ $materia->inventario->stock_maximo ?? 0 }}" required min="0">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="punto_reorden_edit" class="form-label">Punto de Reorden (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.001" class="form-control" id="punto_reorden_edit" 
                           name="inventario[punto_reorden]" value="{{ $materia->inventario->punto_reorden ?? 0 }}" required min="0" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="almacen_edit" class="form-label">Almacén</label>
                    <input type="text" class="form-control" id="almacen_edit" 
                           name="inventario[almacen]" value="{{ $materia->inventario->almacen ?? '' }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="activo_edit" name="activo" {{ $materia->activo ? 'checked' : '' }}>
                    <label class="form-check-label" for="activo_edit">Activo</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="disponible_edit" name="disponible" {{ $materia->disponible ? 'checked' : '' }}>
                    <label class="form-check-label" for="disponible_edit">Disponible</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="preferido_edit" name="preferido" {{ $materia->preferido ? 'checked' : '' }}>
                    <label class="form-check-label" for="preferido_edit">Preferido</label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">
            <i class="material-icons opacity-10 me-1">save</i>
            Actualizar Materia Prima
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Calcular punto de reorden automáticamente
        $('#stock_minimo_edit, #stock_maximo_edit').on('input', function() {
            const min = parseFloat($('#stock_minimo_edit').val()) || 0;
            const max = parseFloat($('#stock_maximo_edit').val()) || 0;
            
            if (min > 0 && max > 0 && max > min) {
                const puntoReorden = (min + max) / 2;
                $('#punto_reorden_edit').val(puntoReorden.toFixed(3));
            }
        });
        
        // Submit del formulario via AJAX
        $('#editMateriaForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Error al actualizar'
                    });
                }
            });
        });
    });
</script>
@else
<div class="alert alert-danger">
    <i class="material-icons opacity-10 me-2">error</i>
    No se pudo cargar la información para editar
</div>
@endif