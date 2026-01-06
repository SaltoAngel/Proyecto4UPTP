@extends('layouts.material')

@section('content')
<div class="container">
    <h1>Prueba de Reportes</h1>
    <p>Aquí puedes probar la generación de reportes.</p>
    <div class="row">
        @foreach($templateNames as $template)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ ucfirst($template) }}</h5>
                        <button class="btn btn-primary download-btn" data-template="{{ $template }}" data-format="pdf">Generar PDF</button>
                        <button class="btn btn-secondary download-btn" data-template="{{ $template }}" data-format="xlsx">Generar Excel</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.download-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const template = this.getAttribute('data-template');
            const format = this.getAttribute('data-format');
            const url = `/dashboard/reportes/generar/${template}/${format}`;

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status} ${response.statusText}`);
                }

                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Error en el servidor');
                }

                const blob = await response.blob();
                const downloadUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = downloadUrl;
                a.download = `reporte_${template}.${format}`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(downloadUrl);
            } catch (error) {
                console.error('Error al descargar el reporte:', error);
                alert('Error al generar el reporte: ' + error.message);
            }
        });
    });
});
</script>
@endpush