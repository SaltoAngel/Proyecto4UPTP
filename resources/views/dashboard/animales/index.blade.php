@extends('layouts.material')

@section('title', 'Animales')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0">Módulo de Animales</h2>
            <p class="text-muted mb-0">Registro de tipos de animal por especie y sus requerimientos (incluye aminoácidos)</p>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearAnimalModal">
            <i class="material-icons me-2">add</i>Registrar Animal
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($tipos as $tipo)
                        <tr>
                            <td>{{ $loop->iteration + ($tipos->currentPage() - 1) * $tipos->perPage() }}</td>
                            <td>{{ $tipo->especie->nombre ?? '—' }}</td>
                            <td><strong>{{ $tipo->nombre }}</strong></td>
                            <td>{{ $tipo->codigo_etapa ?? '—' }}</td>
                            <td>{{ ($tipo->edad_minima_dias ?? '—') }} - {{ ($tipo->edad_maxima_dias ?? '—') }}</td>
                            <td>{{ ($tipo->peso_minimo_kg ?? '—') }} - {{ ($tipo->peso_maximo_kg ?? '—') }}</td>
                            <td>
                                <span class="badge {{ ($tipo->activo ?? true) ? 'bg-success' : 'bg-secondary' }}">{{ ($tipo->activo ?? true) ? 'Activo' : 'Inactivo' }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($tipos->hasPages())
                <div class="d-flex justify-content-center">{{ $tipos->links() }}</div>
            @endif
        </div>
    </div>
    </div>

@include('dashboard.animales.modals.crear')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabla = $('#tablaAnimales').DataTable({
        responsive: true,
        language: { url: "{{ asset('datatables-i18n-es.json') }}" },
        order: [[0, 'asc']],
        columnDefs: [ { orderable: false, targets: [0] } ]
    });
    tabla.on('order.dt search.dt draw.dt', function() {
        let i = 1;
        tabla.cells(null, 0, { search: 'applied', order: 'applied' }).every(function() { this.data(i++); });
    });

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
