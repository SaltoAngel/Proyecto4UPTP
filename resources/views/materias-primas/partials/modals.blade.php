<!-- Modal para crear nueva materia prima -->
<div class="modal fade" id="createMateriaModal" tabindex="-1" aria-labelledby="createMateriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title text-white" id="createMateriaModalLabel">
                    <i class="material-icons opacity-10 me-2">add_circle</i>
                    Nueva Materia Prima
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createMateriaForm" action="{{ route('materias-primas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="codigo" class="form-label">Código <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="codigo" name="codigo" required 
                                       placeholder="Ej: CAF-001" maxlength="50">
                                <div class="form-text">Código único de identificación</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="categoria_id" class="form-label">Categoría</label>
                                <select class="form-select" id="categoria_id" name="categoria_id">
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" required 
                               placeholder="Ej: Café en grano" maxlength="150">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre_comercial" class="form-label">Nombre Comercial</label>
                                <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" 
                                       placeholder="Nombre comercial del producto" maxlength="150">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre_cientifico" class="form-label">Nombre Científico</label>
                                <input type="text" class="form-control" id="nombre_cientifico" name="nombre_cientifico" 
                                       placeholder="Nombre científico si aplica" maxlength="150">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="comentario" class="form-label">Comentarios</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="2" 
                                  placeholder="Observaciones adicionales..."></textarea>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3 text-primary">
                        <i class="material-icons opacity-10 me-2">inventory</i>
                        Parámetros de Inventario
                    </h6>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="stock_minimo" class="form-label">Stock Mínimo (kg) <span class="text-danger">*</span></label>
                                <input type="number" name="inventario[stock_minimo]" id="stock_minimo" 
                                       name="inventario[stock_minimo]" required min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="stock_maximo" class="form-label">Stock Máximo (kg) <span class="text-danger">*</span></label>
                                <input type="number" name="inventario[stock_maximo]" id="stock_maximo" 
                                       name="inventario[stock_maximo]" required min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="punto_reorden" class="form-label">Punto de Reorden (kg) <span class="text-danger">*</span></label>
                                <input type="number" name="inventario[punto_reorden]" id="punto_reorden" 
                                       name="inventario[punto_reorden]" required min="0" readonly>
                                <div class="form-text">Calculado automáticamente</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="almacen" class="form-label">Almacén</label>
                                <input type="text" name="inventario[almacen]" id="almacen" 
                                       name="inventario[almacen]" placeholder="Ej: Almacén A">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="activo" name="activo" checked>
                                <label class="form-check-label" for="activo">Activo</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="disponible" name="disponible" checked>
                                <label class="form-check-label" for="disponible">Disponible</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="preferido" name="preferido">
                                <label class="form-check-label" for="preferido">Preferido</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-icons opacity-10 me-1">save</i>
                        Guardar Materia Prima
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver detalles -->
<div class="modal fade" id="showMateriaModal" tabindex="-1" aria-labelledby="showMateriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h5 class="modal-title text-white" id="showMateriaModalLabel">
                    <i class="material-icons opacity-10 me-2">visibility</i>
                    Detalles de Materia Prima
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Contenido cargado via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar -->
<div class="modal fade" id="editMateriaModal" tabindex="-1" aria-labelledby="editMateriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-warning">
                <h5 class="modal-title text-white" id="editMateriaModalLabel">
                    <i class="material-icons opacity-10 me-2">edit</i>
                    Editar Materia Prima
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Contenido cargado via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para registrar entrada -->
<div class="modal fade" id="entradaInventarioModal" tabindex="-1" aria-labelledby="entradaInventarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success">
                <h5 class="modal-title text-white" id="entradaInventarioModalLabel">
                    <i class="material-icons opacity-10 me-2">input</i>
                    Registrar Entrada de Inventario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="entradaInventarioForm" action="{{ route('movimientos.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tipo_movimiento" value="entrada">
                <input type="hidden" name="origen_movimiento" value="compra">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="materia_prima_id" class="form-label">Materia Prima <span class="text-danger">*</span></label>
                                <select class="form-select" id="materia_prima_id" name="materia_prima_id" required>
                                    <option value="">Seleccionar materia prima</option>
                                    @foreach($materiasPrimasList as $materia)
                                        <option value="{{ $materia->id }}" data-stock="{{ $materia->stock_actual }}">
                                            {{ $materia->codigo }} - {{ $materia->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="proveedor_id" class="form-label">Proveedor <span class="text-danger">*</span></label>
                                <select class="form-select" id="proveedor_id" name="proveedor_id" required>
                                    <option value="">Seleccionar proveedor</option>
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad (kg) <span class="text-danger">*</span></label>
                                <input type="number" step="0.001" class="form-control" id="cantidad" name="cantidad" required min="0.001">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="costo_unitario" class="form-label">Costo Unitario <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="costo_unitario" name="costo_unitario" required min="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="costo_total" class="form-label">Costo Total</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="costo_total" name="costo_total" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numero_documento" class="form-label">N° Factura/Remisión</label>
                                <input type="text" class="form-control" id="numero_documento" name="numero_documento" 
                                       placeholder="Número de documento">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="lote" class="form-label">Lote</label>
                                <input type="text" class="form-control" id="lote" name="lote" 
                                       placeholder="Número de lote">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fecha_factura" class="form-label">Fecha Factura</label>
                                <input type="date" class="form-control" id="fecha_factura" name="fecha_factura" 
                                       value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fecha_recepcion" class="form-label">Fecha Recepción <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="fecha_recepcion" name="fecha_recepcion" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fecha_vencimiento" class="form-label">Fecha Vencimiento</label>
                                <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2" 
                                  placeholder="Observaciones de la entrada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="material-icons opacity-10 me-1">save</i>
                        Registrar Entrada
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para filtros -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark">
                <h5 class="modal-title text-white" id="filterModalLabel">
                    <i class="material-icons opacity-10 me-2">filter_list</i>
                    Filtros de Búsqueda
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ route('materias-primas.index') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="search" class="form-label">Buscar</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Código o descripción..." value="{{ request('search') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="categoria_filter" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria_filter" name="categoria_id">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="estado_filter" class="form-label">Estado Inventario</label>
                        <select class="form-select" id="estado_filter" name="estado_inventario">
                            <option value="">Todos los estados</option>
                            <option value="normal" {{ request('estado_inventario') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="critico" {{ request('estado_inventario') == 'critico' ? 'selected' : '' }}>Crítico</option>
                            <option value="agotado" {{ request('estado_inventario') == 'agotado' ? 'selected' : '' }}>Agotado</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="activo_filter" name="activo" value="1" 
                                   {{ request('activo') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo_filter">Solo activas</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="disponible_filter" name="disponible" value="1" 
                                   {{ request('disponible') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="disponible_filter">Solo disponibles</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('materias-primas.index') }}" class="btn btn-secondary me-auto">Limpiar</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                </div>
            </form>
        </div>
    </div>
</div>