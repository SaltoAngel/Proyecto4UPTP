@extends('layouts.material')

@section('title', 'Recepciones')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Recepciones</h6>
        <a href="{{ route('dashboard.recepciones.create') }}" class="btn btn-primary btn-sm">
          <i class="material-icons align-middle me-1" style="font-size:18px;">add</i> Nueva recepción
        </a>
      </div>
      <div class="card-body">
        <p class="text-muted mb-0">Vista placeholder de recepciones. Integra tu tabla o DataTable aquí.</p>
      </div>
    </div>
  </div>
</div>
@endsection
