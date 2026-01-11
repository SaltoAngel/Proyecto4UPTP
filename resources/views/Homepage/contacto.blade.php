@extends('layouts.home')

@section('title', 'Contacto - Nupilca C.A')

@section('content')
    <!-- Hero Section para contacto -->
    <section class="contact-hero">
        <div class="container">
            <h1>Contáctanos</h1>
            <p>Estamos aquí para atender tus consultas, asesorarte en la nutrición de tus animales y brindarte el mejor servicio. ¡Escríbenos!</p>
        </div>
    </section>

    <!-- Sección de contacto principal -->
    <section class="contact-section">
        <div class="container">
            <div class="section-title">
                <h2>Ponte en contacto</h2>
                <p>Desde aquí podremos atenderte y responder tus preguntas sobre nuestros productos y servicios</p>
            </div>
            
            <div class="contact-container">
                <!-- Información de contacto -->
                <div class="contact-info">
                    <div class="contact-card">
                        <h3><i class="fas fa-map-marker-alt"></i> Nuestra ubicación</h3>
                        <div class="contact-details">
                            <div class="contact-item">
                                <i class="fas fa-map-pin"></i>
                                <div>
                                    <p><strong>Dirección principal:</strong></p>
                                    <p>{{ $contacto['direccion'] }}</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-warehouse"></i>
                                <div>
                                    <p><strong>Planta de producción:</strong></p>
                                    <p>{{ $contacto['planta'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-card">
                        <h3><i class="fas fa-phone-alt"></i> Teléfonos y correos</h3>
                        <div class="contact-details">
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <p><strong>Atención al cliente:</strong></p>
                                    <p>{{ $contacto['telefono'] }}</p>
                                    <p>{{ $contacto['email'] }}</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <p><strong>Información general:</strong></p>
                                    <p>{{ $contacto['email'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Formulario de contacto -->
                <div class="contact-form-container" id="formulario">
                    <div class="form-header">
                        <h3>Envíanos un mensaje</h3>
                        <p>Completa el formulario y nos pondremos en contacto.</p>
                    </div>
                    
                    <form class="contact-form" id="contactForm" action="{{ route('contacto.enviar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nombre">Nombre completo *</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Correo electrónico *</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="asunto">Asunto *</label>
                            <select id="asunto" name="asunto" class="form-control" required>
                                @foreach($asuntos as $valor => $texto)
                                    <option value="{{ $valor }}">{{ $texto }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="mensaje">Mensaje *</label>
                            <textarea id="mensaje" name="mensaje" class="form-control" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem;">
                            <i class="fas fa-paper-plane"></i> Enviar mensaje
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Mapa -->
    <section class="map-section">
        <div class="container">
            <div class="section-title">
                <h2>Ubicación</h2>
                <p>Visita nuestras instalaciones o encuentra la ruta más conveniente para recibir nuestros productos</p>
            </div>
            
            <div class="map-container">
                <div class="map-placeholder">
                    <i class="fas fa-map-marked-alt"></i>
                    <h3>Ubicación de nuestra sede principal</h3>
                    <p>GRP3+786, Av. Cv Sur, Acarigua 3301, Portuguesa</p>
                    <p style="margin-top: 10px;">
                        <a href="{{ $contacto['google_maps'] }}" target="_blank" class="btn btn-primary" style="margin-top: 15px;">
                            <i class="fas fa-directions"></i> Ver en Google Maps
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Horarios de atención -->
    <section class="hours-section">
        <div class="container">
            <div class="section-title">
                <h2>Horarios de atención</h2>
                <p>Estamos disponibles para atenderte en diferentes horarios según el servicio que necesites</p>
            </div>
            
            <div class="hours-container">
                @foreach($horarios as $tipo => $horario)
                    <div class="hours-card">
                        <h3>{{ $horario['titulo'] }}</h3>
                        <ul class="hours-list">
                            @foreach($horario['horarios'] as $item)
                                <li>
                                    <span class="day">{{ $item['dias'] }}</span>
                                    <span class="time">{{ $item['horas'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Preguntas frecuentes -->
    <section class="faq-section">
        <div class="container">
            <div class="section-title">
                <h2>Preguntas frecuentes</h2>
                <p>Respuestas a las consultas más comunes de nuestros clientes</p>
            </div>
            
            <div class="faq-container">
                @foreach($preguntasFrecuentes as $index => $faq)
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>{{ $faq['pregunta'] }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>{{ $faq['respuesta'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>¿Listo para mejorar la alimentación de tus animales?</h2>
            <p>Contáctanos hoy mismo y descubre cómo podemos ayudarte a optimizar la nutrición de tu granja.</p>
            <a href="#formulario" class="btn btn-secondary" style="background-color: white; color: var(--primary-color);">Enviar Mensaje Ahora</a>
        </div>
    </section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/contacto.css') }}">
@endpush

@push('scripts')
<script>
    // Pasar datos de Laravel a JavaScript
    const contacto = @json($contacto);
</script>
<script src="{{ asset('js/contacto.js') }}"></script>
@endpush