<!-- resources/views/materias-primas/partials/tabla-materias.blade.php -->
<div class="table-responsive">
    <table class="table table-hover" id="materiasTable">
        <thead class="table-light">
            <tr>
                <th>CÓDIGO</th>
                <th>DESCRIPCIÓN</th>
                <th>CATEGORÍA</th>
                <th>STOCK ACTUAL</th>
                <th>STOCK MÍN</th>
                <th>STOCK MÁX</th>
                <th>PTO. REORDEN</th>
                <th>ESTADO</th>
                <th>ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @forelse($materiasPrimas as $materia)
            <tr data-materia-id="{{ $materia->id }}">
                <td>{{ $materia->codigo }}</td>
                <td>
                    <strong>{{ $materia->descripcion }}</strong>
                    @if($materia->nombre_comercial)
                        <br><small class="text-muted">{{ $materia->nombre_comercial }}</small>
                    @endif
                </td>
                <td>
                    @if($materia->categoria)
                        <span class="badge bg-info">{{ $materia->categoria->nombre }}</span>
                    @else
                        <span class="badge bg-secondary">Sin categoría</span>
                    @endif
                </td>
                <td class="text-center">
                    <strong class="{{ $materia->inventario ? 'estado-inventario ' . $materia->inventario->estado : '' }}">
                        {{ $materia->inventario ? number_format($materia->inventario->stock_actual, 2) : '0.00' }}
                    </strong>
                </td>
                <td class="text-center">
                    {{ $materia->inventario ? number_format($materia->inventario->stock_minimo, 2) : '0.00' }}
                </td>
                <td class="text-center">
                    {{ $materia->inventario ? number_format($materia->inventario->stock_maximo, 2) : '0.00' }}
                </td>
                <td class="text-center">
                    {{ $materia->inventario ? number_format($materia->inventario->punto_reorden, 2) : '0.00' }}
                </td>
                <td>
                    @if($materia->inventario)
                        @php
                            $estadoClase = [
                                'normal' => 'bg-success',
                                'critico' => 'bg-danger',
                                'agotado' => 'bg-secondary'
                            ][$materia->inventario->estado] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $estadoClase }}">
                            {{ ucfirst($materia->inventario->estado) }}
                        </span>
                    @else
                        <span class="badge bg-warning">Sin inventario</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group btn-group-sm acciones-materia">
                        <button class="btn btn-info btn-ver-materia" 
                                data-bs-toggle="tooltip"
                                title="Ver detalles"
                                data-id="{{ $materia->id }}">
                            <i class="material-icons">visibility</i>
                        </button>
                        
                        <button class="btn btn-warning btn-editar-materia" 
                                data-bs-toggle="tooltip"
                                title="Editar"
                                data-id="{{ $materia->id }}">
                            <i class="material-icons">edit</i>
                        </button>
                        
                        @if($materia->activo)
                            <button type="button" class="btn btn-danger btn-deshabilitar-materia" 
                                    data-bs-toggle="tooltip"
                                    title="Deshabilitar"
                                    data-url="{{ route('materias-primas.destroy', $materia->id) }}">
                                <i class="material-icons">person_off</i>
                            </button>
                        @else
                            <button type="button" class="btn btn-success btn-habilitar-materia" 
                                    data-bs-toggle="tooltip"
                                    title="Habilitar"
                                    data-url="{{ route('materias-primas.restore', $materia->id) }}">
                                <i class="material-icons">restore</i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-muted py-4">
                    <i class="material-icons" style="font-size:3rem;">inventory</i>
                    <h5 class="mt-2">No hay materias primas registradas</h5>
                    <p class="mb-0">Comienza agregando una nueva materia prima</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Paginación -->
    @if($materiasPrimas->hasPages() && !isset($filtroEstado))
        <div class="d-flex justify-content-center mt-3">
            {{ $materiasPrimas->links() }}
        </div>
    @endif
</div>