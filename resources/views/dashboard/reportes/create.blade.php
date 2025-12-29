@extends('layouts.material')

@section('title', 'Crear Nuevo Reporte')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Crear Nuevo Reporte</h3>
                    <div class="card-tools">
                        <a href="{{ route('dashboard.reportes-admin.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <form action="{{ route('dashboard.reportes-admin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre del Reporte *</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                           value="{{ old('nombre') }}" required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="template">Nombre del Template *</label>
                                    <input type="text" name="template" id="template" class="form-control @error('template') is-invalid @enderror"
                                           value="{{ old('template') }}" required>
                                    <small class="form-text text-muted">Sin extensión .jrxml</small>
                                    @error('template')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria">Categoría</label>
                                    <select name="categoria" id="categoria" class="form-control">
                                        <option value="">Sin categoría</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria }}" {{ old('categoria') == $categoria ? 'selected' : '' }}>
                                                {{ $categoria }}
                                            </option>
                                        @endforeach
                                        <option value="__new__">+ Nueva categoría</option>
                                    </select>
                                    <input type="text" id="nueva_categoria" class="form-control mt-2" placeholder="Nueva categoría" style="display: none;">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jrxml_file">Archivo JRXML</label>
                                    <input type="file" name="jrxml_file" id="jrxml_file" class="form-control-file @error('jrxml_file') is-invalid @enderror"
                                           accept=".jrxml,.xml">
                                    <small class="form-text text-muted">Opcional: subir archivo JRXML existente</small>
                                    @error('jrxml_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="requiere_db" value="0">
                                        <input type="checkbox" name="requiere_db" value="1" class="custom-control-input" id="requiere_db"
                                               {{ old('requiere_db', false) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="requiere_db">Requiere conexión a base de datos</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="activo" value="0">
                                        <input type="checkbox" name="activo" value="1" class="custom-control-input" id="activo"
                                               {{ old('activo', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="activo">Activo</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="parametros_group" style="display: none;">
                            <label for="parametros">Parámetros (JSON)</label>
                            <textarea name="parametros" id="parametros" class="form-control" rows="4" placeholder='{"param1": "string", "param2": "boolean"}'>{{ old('parametros') }}</textarea>
                            <small class="form-text text-muted">Formato JSON con los parámetros disponibles</small>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Crear Reporte
                        </button>
                        <a href="{{ route('dashboard.reportes-admin.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Mostrar/ocultar campo de nueva categoría
    $('#categoria').change(function() {
        if ($(this).val() === '__new__') {
            $('#nueva_categoria').show().attr('name', 'categoria');
            $(this).attr('name', 'categoria_old');
        } else {
            $('#nueva_categoria').hide().removeAttr('name');
            $(this).attr('name', 'categoria');
        }
    });

    // Mostrar/ocultar parámetros cuando requiere DB
    $('#requiere_db').change(function() {
        if ($(this).is(':checked')) {
            $('#parametros_group').show();
        } else {
            $('#parametros_group').hide();
        }
    });

    // Inicializar estado
    if ($('#requiere_db').is(':checked')) {
        $('#parametros_group').show();
    }
});
</script>
@endsection