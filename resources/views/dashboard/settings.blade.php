@extends('layouts.material')

@section('title', 'Configuración de Usuario')

{{--
  Vista: Configuración de Usuario
  Propósito: Editar datos personales vinculados a `persona` y cambiar contraseña del `user` autenticado.
  Flujo:
    - GET muestra formulario con valores actuales.
    - POST `dashboard.configuracion.update` valida y guarda en `persona`; si se envía password, verifica `current_password` y actualiza.
  Estilos: Usa overrides globales para inputs en el layout Material.
--}}
@section('content')
<div class="row">
  <div class="col-lg-7 mb-4">
    <div class="card h-100">
      <div class="card-header pb-0">
        <h6 class="mb-0"><i class="material-icons me-2" style="font-size:18px">person</i>Datos personales</h6>
      </div>
      <div class="card-body">
        {{-- Mensajes de error y éxito --}}
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        {{-- Formulario de datos personales (tabla personas) --}}
        <form method="POST" action="{{ route('dashboard.configuracion.update') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombres</label>
              <input type="text" name="nombres" class="form-control" value="{{ old('nombres', optional($persona)->nombres) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Apellidos</label>
              <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos', optional($persona)->apellidos) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono</label>
              <input type="text" name="telefono" class="form-control" value="{{ old('telefono', optional($persona)->telefono) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono alternativo</label>
              <input type="text" name="telefono_alternativo" class="form-control" value="{{ old('telefono_alternativo', optional($persona)->telefono_alternativo) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="{{ old('email', optional($persona)->email) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Ciudad</label>
              <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad', optional($persona)->ciudad) }}">
            </div>
            <div class="col-12">
              <label class="form-label">Dirección</label>
              <input type="text" name="direccion" class="form-control" value="{{ old('direccion', optional($persona)->direccion) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Estado</label>
              <input type="text" name="estado" class="form-control" value="{{ old('estado', optional($persona)->estado) }}">
            </div>
          </div>
          <div class="mt-4 d-flex gap-2">
            <button class="btn btn-primary"><i class="material-icons align-middle me-1" style="font-size:18px">save</i>Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-lg-5 mb-4">
    <div class="card h-100">
      <div class="card-header pb-0">
        <h6 class="mb-0"><i class="material-icons me-2" style="font-size:18px">lock</i>Cambiar contraseña</h6>
      </div>
      <div class="card-body">
        {{-- Formulario de cambio de contraseña (tabla users) --}}
        <form method="POST" action="{{ route('dashboard.configuracion.update') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Contraseña actual</label>
            <input type="password" name="current_password" class="form-control" autocomplete="current-password">
          </div>
          <div class="mb-3">
            <label class="form-label">Nueva contraseña</label>
            <input type="password" name="password" class="form-control" autocomplete="new-password">
          </div>
          <div class="mb-3">
            <label class="form-label">Confirmar nueva contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
          </div>
          <button class="btn btn-dark"><i class="material-icons align-middle me-1" style="font-size:18px">key</i>Actualizar contraseña</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
