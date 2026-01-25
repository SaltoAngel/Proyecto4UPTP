@extends('layouts.material')

@section('title', 'Detalles de Usuario')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Detalles del Usuario</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> {{ $user->persona->nombres }} {{ $user->persona->apellidos }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Documento:</strong> {{ $user->persona->documento }}</p>
                            <p><strong>Rol:</strong> {{ $user->roles->first()->name ?? 'Sin rol' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Estado:</strong> 
                                @if($user->status == 'activo')
                                    <span class="badge bg-success">Activo</span>
                                @elseif($user->status == 'pendiente')
                                    <span class="badge bg-warning">Pendiente</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </p>
                            <p><strong>Último acceso:</strong> {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}</p>
                            <p><strong>Creado:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection