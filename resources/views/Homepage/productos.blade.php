@extends('layouts.home')

@section('title', 'Productos - Nupilca C.A')

@section('content')
    <!-- Hero Section -->
    <section class="products-hero">
        <div class="container">
            <h1>Nuestros Productos</h1>
            <p>Calidad garantizada para el crecimiento saludable de tus animales</p>
        </div>
    </section>

    <!-- Filtros por categoría -->
    <section class="categories-filter">
        <div class="container">
            <div class="categories-list" id="categories-list">
                @foreach($categorias as $key => $categoria)
                    <button class="category-btn {{ $key == 'all' ? 'active' : '' }}" data-category="{{ $key }}">
                        {{ $categoria }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Grid de productos -->
    <section class="products-section">
        <div class="container">
            <div class="section-title">
                <h2>Catálogo de Alimentos</h2>
                <p>Selecciona un producto para ver más detalles</p>
            </div>
            
            <div class="products-grid" id="products-container">
                @foreach($productos as $producto)
                    @php
                        $claseStock = $producto['stock'] > 50 ? 'in-stock' : 'low-stock';
                        $textoStock = $producto['stock'] > 50 ? $producto['stock'] . ' unidades' : 'Solo ' . $producto['stock'] . ' unidades';
                    @endphp
                    
                    <div class="product-card" data-category="{{ $producto['categoria'] }}">
                        @if($producto['destacado'])
                            <div class="product-badge">Destacado</div>
                        @endif
                        <div class="product-image">
                            <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}">
                        </div>
                        <div class="product-info">
                            <div class="product-category">{{ $producto['categoria'] }}</div>
                            <h3 class="product-title">{{ $producto['nombre'] }}</h3>
                            <p class="product-description">{{ $producto['descripcion_corta'] }}</p>
                            <div class="product-footer">
                                <div>
                                    <div class="product-weight">{{ $producto['peso'] }}</div>
                                </div>
                                <div class="product-stock {{ $claseStock }}">{{ $textoStock }}</div>
                            </div>
                            <button class="btn btn-primary view-product-btn" 
                                    style="width: 100%; margin-top: 15px;" 
                                    data-id="{{ $producto['id'] }}">
                                Ver Detalles
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Enlace al dashboard de administración (visible solo para administradores) -->
            @auth
                @if(auth()->user()->is_admin)
                    <div style="text-align: center; margin-top: 50px;">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Panel de Administración</a>
                    </div>
                @endif
            @endauth
        </div>
    </section>

    <!-- Modal de detalles del producto -->
    <div class="product-modal" id="productModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Alimento Multipropósito</h2>
                <button class="close-modal" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-image">
                    <img id="modalImage" src="" alt="Producto">
                </div>
                <div class="modal-info">
                    <span class="modal-category" id="modalCategory">General</span>
                    <h3 id="modalProductTitle">Alimento Multipropósito</h3>
                    <p class="modal-description" id="modalDescription">
                        Descripción del producto...
                    </p>
                    
                    <div class="modal-details">
                        <div class="detail-item">
                            <span class="detail-label">Precio:</span>
                            <span class="detail-value" id="modalPrice">$0.00</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Peso:</span>
                            <span class="detail-value" id="modalWeight">0 kg</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Stock disponible:</span>
                            <span class="detail-value" id="modalStock">0 unidades</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Categoría:</span>
                            <span class="detail-value" id="modalCategoryFull">Categoría</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Estado:</span>
                            <span class="detail-value in-stock" id="modalStatus">Disponible</span>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button class="btn btn-secondary" onclick="closeModal()">Cerrar</button>
                       <!-- <a href="{{ route('contacto') }}" class="btn btn-primary">Solicitar Cotización</a>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/productos.css') }}">
@endpush

@push('scripts')
<script>
    // Convertir datos de PHP a JavaScript
    const productos = @json($productos);
</script>
<script src="{{ asset('js/productos.js') }}"></script>
@endpush