@if(isset($materia))
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-gradient-info text-white">
                <h6 class="mb-0">
                    <i class="material-icons opacity-10 me-1">info</i>
                    Información General
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th class="text-sm">Código:</th>
                        <td class="text-sm">{{ $materia->codigo ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-sm">Descripción:</th>
                        <td class="text-sm">{{ $materia->descripcion }}</td>
                    </tr>
                    <tr>
                        <th class="text-sm">Categoría:</th>
                        <td class="text-sm">
                            <span class="badge bg-info">{{ $materia->categoria->nombre ?? 'Sin categoría' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-sm">Nombre Comercial:</th>
                        <td class="text-sm">{{ $materia->nombre_comercial ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-sm">Nombre Científico:</th>
                        <td class="text-sm">{{ $materia->nombre_cientifico ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th class="text-sm">Estado:</th>
                        <td class="text-sm">
                            @if($materia->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                            
                            @if($materia->disponible)
                                <span class="badge bg-success ms-1">Disponible</span>
                            @else
                                <span class="badge bg-danger ms-1">No disponible</span>
                            @endif
                            
                            @if($materia->preferido)
                                <span class="badge bg-warning ms-1">Preferido</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-sm">Fecha creación:</th>
                        <td class="text-sm">{{ $materia->fecha_creacion ? $materia->fecha_creacion->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-gradient-primary text-white">
                <h6 class="mb-0">
                    <i class="material-icons opacity-10 me-1">inventory</i>
                    Inventario
                </h6>
            </div>
            <div class="card-body">
                @if($materia->inventario)
                <table class="table table-sm">
                    <tr>
                        <th class="text-sm">Stock Actual:</th>
                        <td class="text-sm">
                            <span class="font-weight-bold">{{ number_format($materia->inventario->stock_actual, 3) }} kg</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-sm">Stock Mínimo:</th>
                        <td class="text-sm">{{ number_format($materia->inventario->stock_minimo, 3) }} kg</td>
                    </tr>
                    <tr>
                        <th class="text-sm">Stock Máximo:</th>
                        <td class="text-sm">{{ number_format($materia->inventario->stock_maximo, 3) }} kg</td>
                    </tr>
                    <tr>
                        <th class="text-sm">Punto de Reorden:</th>
                        <td class="text-sm">{{ number_format($materia->inventario->punto_reorden, 3) }} kg</td>
                    </tr>
                    <tr>
                        <th class="text-sm">Estado:</th>
                        <td class="text-sm">
                            @php
                                $estadoClass = [
                                    'normal' => 'success',
                                    'critico' => 'warning',
                                    'agotado' => 'danger'
                                ][$materia->inventario->estado] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $estadoClass }}">
                                {{ ucfirst($materia->inventario->estado) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-sm">Almacén:</th>
                        <td class="text-sm">{{ $materia->inventario->almacen ?? 'No especificado' }}</td>
                    </tr>
                    <tr>
                        <th class="text-sm">Último movimiento:</th>
                        <td class="text-sm">
                            {{ $materia->inventario->fecha_ultimo_movimiento ? $materia->inventario->fecha_ultimo_movimiento->format('d/m/Y') : 'N/A' }}
                        </td>
                    </tr>
                </table>
                @else
                <div class="alert alert-warning text-center">
                    <i class="material-icons opacity-10">warning</i>
                    <p class="mb-0">No hay inventario registrado</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-gradient-success text-white">
                <h6 class="mb-0">
                    <i class="material-icons opacity-10 me-1">analytics</i>
                    Resumen
                </h6>
            </div>
            <div class="card-body">
                @if($materia->inventario)
                <div class="text-center mb-3">
                    @php
                        $porcentaje = $materia->inventario->stock_maximo > 0 
                            ? ($materia->inventario->stock_actual / $materia->inventario->stock_maximo) * 100 
                            : 0;
                        $color = $porcentaje >= 70 ? 'success' : ($porcentaje >= 30 ? 'warning' : 'danger');
                    @endphp
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-{{ $color }}" role="progressbar" 
                             style="width: {{ $porcentaje }}%;" 
                             aria-valuenow="{{ $porcentaje }}" aria-valuemin="0" aria-valuemax="100">
                            {{ number_format($porcentaje, 1) }}%
                        </div>
                    </div>
                    <small class="text-muted">Nivel de stock actual</small>
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0 text-sm">Días de Stock</h6>
                            <h4 class="text-primary mb-0">
                                {{ $materia->inventario->stock_actual > 0 ? '∞' : '0' }}
                            </h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h6 class="mb-0 text-sm">Valor Total</h6>
                            <h4 class="text-success mb-0">
                                ${{ number_format($materia->inventario->costo_promedio * $materia->inventario->stock_actual, 2) }}
                            </h4>
                        </div>
                    </div>
                </div>
                @endif
                
                <hr class="my-3">
                
                <div class="text-center">
                    <h6 class="text-sm text-muted mb-2">Acciones Rápidas</h6>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#entradaInventarioModal">
                            <i class="material-icons opacity-10" style="font-size: 16px;">input</i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#editMateriaModal"
                                data-id="{{ $materia->id }}">
                            <i class="material-icons opacity-10" style="font-size: 16px;">edit</i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-btn"
                                data-id="{{ $materia->id }}"
                                data-nombre="{{ $materia->descripcion }}">
                            <i class="material-icons opacity-10" style="font-size: 16px;">delete</i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-gradient-secondary text-white">
                <h6 class="mb-0">
                    <i class="material-icons opacity-10 me-1">history</i>
                    Últimos Movimientos
                </h6>
            </div>
            <div class="card-body">
                @if($materia->movimientos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th class="text-sm">Fecha</th>
                                <th class="text-sm">Tipo</th>
                                <th class="text-sm">Cantidad</th>
                                <th class="text-sm">Documento</th>
                                <th class="text-sm">Proveedor</th>
                                <th class="text-sm">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materia->movimientos->take(5) as $movimiento)
                            <tr>
                                <td class="text-sm">{{ $movimiento->fecha_movimiento->format('d/m/Y H:i') }}</td>
                                <td class="text-sm">
                                    @php
                                        $tipoClass = [
                                            'entrada' => 'success',
                                            'salida' => 'danger',
                                            'ajuste' => 'warning'
                                        ][$movimiento->tipo_movimiento] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $tipoClass }}">
                                        {{ ucfirst($movimiento->tipo_movimiento) }}
                                    </span>
                                </td>
                                <td class="text-sm">{{ number_format($movimiento->cantidad, 3) }} kg</td>
                                <td class="text-sm">{{ $movimiento->numero_documento ?? 'N/A' }}</td>
                                <td class="text-sm">{{ $movimiento->proveedor->nombre ?? 'N/A' }}</td>
                                <td class="text-sm">{{ Str::limit($movimiento->observaciones, 30) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-3">
                    <i class="material-icons opacity-10" style="font-size: 48px;">inventory</i>
                    <p class="text-muted mb-0">No hay movimientos registrados</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Comentarios</h6>
            </div>
            <div class="card-body">
                <p class="text-sm">{{ $materia->comentario ?? 'Sin comentarios' }}</p>
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-danger">
    <i class="material-icons opacity-10 me-2">error</i>
    No se encontró la materia prima
</div>
@endif