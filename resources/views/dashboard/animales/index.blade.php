@extends('layouts.material')

@section('title', 'Animales - ' . config('app.name'))

@push('styles')
<style>
    .especie-icon { font-size: 22px; line-height: 1; }
    .especie-icon-wrapper { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; background-color: rgba(0,0,0,0.06); }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0">
                <i class="material-icons me-2 text-success">pets</i>Módulo de Animales
            </h2>
            <p class="text-muted mb-0">Gestión de tipos de animal y requerimientos nutricionales</p>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearAnimalModal">
            <i class="material-icons me-2">add_circle</i>Nuevo Animal
        </button>
    </div>
    @if(session('status'))
        <div class="col-12"><div class="alert alert-success mt-3">{{ session('status') }}</div></div>
    @endif
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="tablaAnimales">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Especie</th>
                        <th>Nombre</th>
                        <th>Etapa</th>
                        <th>Edad (días)</th>
                        <th>Peso (kg)</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tipos as $tipo)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="align-middle">
                                @php
                                    $nombreEspecie = $tipo->especie->nombre ?? null;
                                    $iconMeta = [
                                        // Mamíferos
                                        'Cerdo' => ['icon' => 'pets', 'color' => '#e91e63'], 'Puerco' => ['icon' => 'pets', 'color' => '#e91e63'], 'Porcino' => ['icon' => 'pets', 'color' => '#e91e63'],
                                        'Vaca' => ['icon' => 'agriculture', 'color' => '#795548'], 'Bovino' => ['icon' => 'agriculture', 'color' => '#795548'], 'Bovinos' => ['icon' => 'agriculture', 'color' => '#795548'],
                                        'Ovino' => ['icon' => 'pets', 'color' => '#9e9e9e'], 'Oveja' => ['icon' => 'pets', 'color' => '#9e9e9e'], 'Ovinos' => ['icon' => 'pets', 'color' => '#9e9e9e'],
                                        'Caprino' => ['icon' => 'pets', 'color' => '#673ab7'], 'Cabra' => ['icon' => 'pets', 'color' => '#673ab7'], 'Caprinos' => ['icon' => 'pets', 'color' => '#673ab7'],
                                        'Caballo' => ['icon' => 'pets', 'color' => '#2196f3'], 'Equino' => ['icon' => 'pets', 'color' => '#2196f3'], 'Equinos' => ['icon' => 'pets', 'color' => '#2196f3'],
                                        'Conejo' => ['icon' => 'pets', 'color' => '#8bc34a'], 'Cunicola' => ['icon' => 'pets', 'color' => '#8bc34a'],
                                        // Aves
                                        'Aves' => ['icon' => 'emoji_nature', 'color' => '#ffc107'], 'Pollo' => ['icon' => 'emoji_nature', 'color' => '#ffc107'], 'Gallina' => ['icon' => 'emoji_nature', 'color' => '#ffc107'], 'Gallinas' => ['icon' => 'emoji_nature', 'color' => '#ffc107'],
                                        'Pato' => ['icon' => 'emoji_nature', 'color' => '#ffc107'], 'Patos' => ['icon' => 'emoji_nature', 'color' => '#ffc107'], 'Pavo' => ['icon' => 'emoji_nature', 'color' => '#ffc107'], 'Pavos' => ['icon' => 'emoji_nature', 'color' => '#ffc107'],
                                        // Peces
                                        'Pez' => ['icon' => 'opacity', 'color' => '#03a9f4'], 'Peces' => ['icon' => 'opacity', 'color' => '#03a9f4'], 'Tilapia' => ['icon' => 'opacity', 'color' => '#03a9f4'], 'Trucha' => ['icon' => 'opacity', 'color' => '#03a9f4'],
                                    ];
                                    $meta = $iconMeta[$nombreEspecie ?? ''] ?? ['icon' => 'pets', 'color' => '#607d8b'];
                                @endphp
                                <span class="especie-icon-wrapper" data-bs-toggle="tooltip" data-bs-title="{{ $nombreEspecie ?? '—' }}">
                                    <i class="material-icons especie-icon" style="color: {{ $meta['color'] }};">{{ $meta['icon'] }}</i>
                                </span>
                                <span class="ms-2">{{ $nombreEspecie ?? '—' }}</span>
                            </td>
                            <td><strong>{{ $tipo->nombre }}</strong></td>
                            <td>{{ $tipo->codigo_etapa ?? '—' }}</td>
                            <td>{{ ($tipo->edad_minima_dias ?? '—') }} - {{ ($tipo->edad_maxima_dias ?? '—') }}</td>
                            <td>{{ ($tipo->peso_minimo_kg ?? '—') }} - {{ ($tipo->peso_maximo_kg ?? '—') }}</td>
                            <td>
                                <span class="badge {{ ($tipo->activo ?? true) ? 'bg-success' : 'bg-secondary' }} estado-badge">{{ ($tipo->activo ?? true) ? 'Activo' : 'Inactivo' }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm acciones-animal" data-tipo-id="{{ $tipo->id }}">
                                    <button class="btn btn-info" data-bs-toggle="modal" 
                                            data-bs-target="#verAnimalModal" 
                                            data-tipo='@json($tipo)'>
                                        <i class="material-icons">visibility</i>
                                    </button>
                                    <button class="btn btn-warning" data-bs-toggle="modal" 
                                            data-bs-target="#editarAnimalModal" 
                                            data-tipo='@json($tipo)'>
                                        <i class="material-icons">edit</i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>

@include('dashboard.animales.modals.crear')
@include('dashboard.animales.modals.ver')
@include('dashboard.animales.modals.editar')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabla = $('#tablaAnimales').DataTable({
        responsive: true,
        language: { url: "{{ asset('datatables-i18n-es.json') }}" },
        order: [[1, 'asc'], [2, 'asc']],
        columnDefs: [
            { orderable: false, targets: [0, 7] } // numeración y acciones
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        lengthMenu: [[ 10, 25, 50, -1], [ 10, 25, 50, "Todos"]]
    });
    tabla.on('order.dt search.dt draw.dt', function() {
        let i = 1;
        tabla.cells(null, 0, { search: 'applied', order: 'applied' }).every(function() { this.data(i++); });
    });

    // Modal: ver animal
    $('#verAnimalModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const tipoData = button.data('tipo');
        const tipo = typeof tipoData === 'string' ? JSON.parse(tipoData) : tipoData;
        const modal = $(this);

        modal.find('.modal-title').text('Detalles: ' + (tipo.nombre || 'Tipo sin nombre'));
        modal.find('#verEspecie').text(tipo.especie?.nombre ?? '—');
        modal.find('#verNombre').text(tipo.nombre ?? '—');
        modal.find('#verEtapa').text(tipo.codigo_etapa ?? '—');
        modal.find('#verEdad').text((tipo.edad_minima_dias ?? '—') + ' - ' + (tipo.edad_maxima_dias ?? '—'));
        modal.find('#verPeso').text((tipo.peso_minimo_kg ?? '—') + ' - ' + (tipo.peso_maximo_kg ?? '—'));
        const estadoHtml = (tipo.activo ?? true) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>';
        modal.find('#verEstado').html(estadoHtml);
        modal.find('#verDescripcion').text(tipo.descripcion ?? '—');
    });

    // Modal: editar animal
    $('#editarAnimalModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const tipoData = button.data('tipo');
        const tipo = typeof tipoData === 'string' ? JSON.parse(tipoData) : tipoData;
        const modal = $(this);
        const form = modal.find('form');

        // Construir acción (asumiendo endpoint REST /dashboard/animales/{id})
        form.attr('action', '/dashboard/animales/' + tipo.id);
        form.find('input[name="_method"]').val('PUT');

        modal.find('#editNombre').val(tipo.nombre || '');
        modal.find('#editEtapa').val(tipo.codigo_etapa || '');
        modal.find('#editEdadMin').val(tipo.edad_minima_dias || '');
        modal.find('#editEdadMax').val(tipo.edad_maxima_dias || '');
        modal.find('#editPesoMin').val(tipo.peso_minimo_kg || '');
        modal.find('#editPesoMax').val(tipo.peso_maximo_kg || '');
        modal.find('#editActivo').prop('checked', !!(tipo.activo ?? true));
    });

    // Envío edición
    $('#formEditarAnimal').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            beforeSend: () => form.find('button[type="submit"]').prop('disabled', true).html('<i class="material-icons me-2">hourglass_empty</i>Guardando...'),
            success: (response) => {
                form.find('button[type="submit"]').prop('disabled', false).html('<i class="material-icons me-2">save</i>Guardar cambios');
                $('#editarAnimalModal').modal('hide');
                Swal.fire({ icon: 'success', title: 'Actualizado', text: response.message || 'Animal actualizado', timer: 1200, showConfirmButton: false });
                setTimeout(() => window.location.reload(), 800);
            },
            error: (xhr) => {
                form.find('button[type="submit"]').prop('disabled', false).html('<i class="material-icons me-2">save</i>Guardar cambios');
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON?.errors || {};
                    let html = '<ul>';
                    Object.keys(errors).forEach(k => { html += `<li>${errors[k][0]}</li>`; });
                    html += '</ul>';
                    Swal.fire({ icon: 'error', title: 'Errores de validación', html });
                } else {
                    const message = xhr.responseJSON?.message || 'Error al actualizar el animal';
                    Swal.fire({ icon: 'error', title: 'Error', text: message });
                }
            }
        });
    });

    // Inicializar tooltips de Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl); });

    $('#formCrearAnimal').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            beforeSend: () => form.find('button[type="submit"]').prop('disabled', true).html('<i class="material-icons me-2">hourglass_empty</i>Guardando...'),
            success: (response) => {
                form.find('button[type="submit"]').prop('disabled', false).html('<i class="material-icons me-2">save</i>Guardar');
                $('#crearAnimalModal').modal('hide');
                Swal.fire({ icon: 'success', title: 'Éxito', text: response.message || 'Animal registrado', timer: 1200, showConfirmButton: false });
                setTimeout(() => window.location.reload(), 800);
            },
            error: (xhr) => {
                form.find('button[type="submit"]').prop('disabled', false).html('<i class="material-icons me-2">save</i>Guardar');
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON?.errors || {};
                    let html = '<ul>';
                    Object.keys(errors).forEach(k => { html += `<li>${errors[k][0]}</li>`; });
                    html += '</ul>';
                    Swal.fire({ icon: 'error', title: 'Errores de validación', html });
                } else {
                    const message = xhr.responseJSON?.message || 'Error al registrar el animal';
                    Swal.fire({ icon: 'error', title: 'Error', text: message });
                }
            }
        });
    });
});
</script>
@endpush
