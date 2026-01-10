@extends('layouts.home')

@section('title', 'Nosotros - Nupilca C.A')

@section('content')
    <!-- Hero Section para nosotros -->
    <section class="about-hero">
        <div class="container">
            <h1>Nuestra historia</h1>
            <p>Dedicados a la nutrición animal, cultivando confianza y crecimiento en el sector agropecuario nacional.</p>
        </div>
    </section>

    <!-- Sección de historia -->
    <section class="history-section" id="historia">
        <div class="container">
            <div class="section-title">
                <h2>Nuestra trayectoria</h2>
                <p>Desde nuestros humildes comienzos hasta convertirnos en líderes del sector</p>
            </div>
            
            <div class="history-timeline">
                @foreach($historia as $index => $item)
                    <div class="timeline-item">
                        <div class="timeline-year">{{ $item['anio'] }}</div>
                        <div class="timeline-content">
                            <h3>{{ $item['titulo'] }}</h3>
                            <p>{{ $item['descripcion'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Sección de valores -->
    <section class="values-section" id="valores">
        <div class="container">
            <div class="section-title">
                <h2>Nuestros valores</h2>
                <p>Principios que guían cada decisión y acción en nuestra empresa</p>
            </div>
            
            <div class="values-grid">
                @foreach($valores as $valor)
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="{{ $valor['icono'] }}"></i>
                        </div>
                        <h3>{{ $valor['titulo'] }}</h3>
                        <p>{{ $valor['descripcion'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECCIÓN DE MISIÓN Y VISIÓN -->
    <section class="mission-vision-section">
        <div class="container">
            <div class="mission-vision-container">
                <div class="mission-box">
                    <div class="mission-icon">
                        <i class="{{ $misionVision['mision']['icono'] }}"></i>
                    </div>
                    <h3>{{ $misionVision['mision']['titulo'] }}</h3>
                    <p>{{ $misionVision['mision']['descripcion'] }}</p>
                </div>
                
                <div class="vision-box">
                    <div class="vision-icon">
                        <i class="{{ $misionVision['vision']['icono'] }}"></i>
                    </div>
                    <h3>{{ $misionVision['vision']['titulo'] }}</h3>
                    <p>{{ $misionVision['vision']['descripcion'] }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Equipo -->
    <section class="team-section" id="equipo">
        <div class="container">
            <div class="section-title">
                <h2>Nuestro equipo</h2>
                <p>Profesionales apasionados por la nutrición animal y el desarrollo agropecuario</p>
            </div>
            
            <div class="team-grid">
                @foreach($equipo as $miembro)
                    <div class="team-member">
                        <div class="member-image">
                            <img src="{{ $miembro['imagen'] }}" alt="{{ $miembro['nombre'] }}">
                        </div>
                        <div class="member-info">
                            <h3>{{ $miembro['nombre'] }}</h3>
                            <p class="member-position">{{ $miembro['cargo'] }}</p>
                            <p>{{ $miembro['descripcion'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>¿Quieres unirte a nuestra familia de clientes satisfechos?</h2>
            <p>Contáctanos y descubre cómo podemos ayudarte a optimizar la nutrición de tus animales.</p>
            <a href="{{ route('contacto') }}" class="btn btn-secondary" style="background-color: white; color: var(--primary-color);">Contactar Ahora</a>
        </div>
    </section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/nosotros.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/nosotros.js') }}"></script>
@endpush