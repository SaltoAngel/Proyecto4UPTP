@extends('layouts.home')

@section('title', 'Nupilca C.A - Inicio')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h2>Alimentos de calidad para tu granja</h2>
            <p>Somos líderes en producción y distribución de alimentos balanceados para gallinas, vacas, cerdos, caballos y más. Calidad garantizada para el crecimiento saludable de tus animales.</p>
            <a href="{{ route('productos') }}" class="btn btn-primary">Conoce nuestros productos</a>
        </div>
    </section>

    <!-- Nueva sección: Concepto de Nutrición Animal -->
    <section class="nutrition-concept">
        <div class="container">
            <div class="nutrition-container">
                <div class="nutrition-text">
                    <h2>Nutrición animal</h2>
                    <p>La nutrición animal es la ciencia que estudia la alimentación de los animales, especialmente en la producción ganadera. Se centra en proporcionar una dieta equilibrada que contenga todos los nutrientes esenciales en las proporciones adecuadas para cada especie, edad y propósito productivo.</p>
                    
                    <p>Una correcta nutrición no solo garantiza el crecimiento y desarrollo saludable de los animales, sino que también influye directamente en su productividad, calidad de los productos derivados (carne, leche, huevos) y en la rentabilidad de la explotación ganadera.</p>
                    
                    <div class="nutrition-highlights">
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="highlight-text">
                                <h4>Nutrientes esenciales</h4>
                                <p>Proteínas, carbohidratos, grasas, vitaminas y minerales en equilibrio perfecto.</p>
                            </div>
                        </div>
                        
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <div class="highlight-text">
                                <h4>Salud y bienestar</h4>
                                <p>Una nutrición adecuada previene enfermedades y mejora la calidad de vida animal.</p>
                            </div>
                        </div>
                        
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="highlight-text">
                                <h4>Maximiza la productividad</h4>
                                <p>Mejora los índices de conversión alimenticia y rentabilidad de la granja.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="nutrition-image">
                    <img src="{{ asset('img/cerdo-gallina.jpg') }}" alt="Nutrición animal">
                </div>
            </div>
        </div>
    </section>

    <!-- Slider automático -->
    <div class="slider-container">
        <div class="slider">
            <div class="slide">
                <img src="https://i.pinimg.com/736x/51/70/0e/51700e6e8c8d11432f791bb0782ba44f.jpg" alt="Gallinas en granja">
                <div class="slide-content">
                    <h3>Alimentos para gallinas</h3>
                    <p>Nutrición especializada para una producción óptima de huevos y carne.</p>
                </div>
            </div>
            <div class="slide">
                <img src="https://i.pinimg.com/1200x/cf/ac/44/cfac445d8e744dea69f1232a4115e927.jpg" alt="Vacas en pastizal">
                <div class="slide-content">
                    <h3>Nutrición para ganado</h3>
                    <p>Alimentos balanceados para el desarrollo saludable de tu ganado.</p>
                </div>
            </div>
            <div class="slide">
                <img src="https://i.pinimg.com/736x/c8/20/b7/c820b79c61f0c48ad8fb5b069c22a848.jpg" alt="Cerdos en granja">
                <div class="slide-content">
                    <h3>Alimentación para cerdos</h3>
                    <p>Fórmulas especializadas para cada etapa de crecimiento porcino.</p>
                </div>
            </div>
            <div class="slide">
                <img src="https://i.pinimg.com/1200x/e5/90/a0/e590a077162a027054399c51cd3d939a.jpg" alt="Caballos en establo">
                <div class="slide-content">
                    <h3>Nutrición equina</h3>
                    <p>Alimentos de alta calidad para el rendimiento y salud de tus caballos.</p>
                </div>
            </div>
        </div>
        <div class="slider-nav">
            <div class="slider-dot active"></div>
            <div class="slider-dot"></div>
            <div class="slider-dot"></div>
            <div class="slider-dot"></div>
        </div>
    </div>

    <!-- Sección de servicios preview -->
    <section class="services-preview">
        <div class="container">
            <div class="section-title">
                <h2>Nuestros servicios</h2>
                <p>Ofrecemos soluciones integrales para la alimentación y cuidado de tus animales de granja.</p>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <img src="https://images.unsplash.com/photo-1589923188651-268a9765e432?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Producción de alimentos">
                    <div class="service-card-content">
                        <h3>Producción de alimentos</h3>
                        <p>Fabricamos alimentos balanceados de alta calidad para todas las especies de granja.</p>
                        <a href="{{ route('servicios') }}#produccion" class="btn btn-primary" style="margin-top: 15px; padding: 8px 15px;">Ver más</a>
                    </div>
                </div>
                
                <div class="service-card">
                    <img src="https://img.freepik.com/fotos-premium/camion-cargado-sacos-que-contienen-cacahuetes-transporte-india_729664-198.jpg" alt="Despacho de alimento">
                    <div class="service-card-content">
                        <h3>Despacho de alimento</h3>
                        <p>Red de distribución que llega a todas las regiones del país con entregas oportunas.</p>
                        <a href="{{ route('servicios') }}#distribucion" class="btn btn-primary" style="margin-top: 15px; padding: 8px 15px;">Ver más</a>
                    </div>
                </div>
                
                <div class="service-card">
                    <img src="https://media.istockphoto.com/id/1413761479/es/foto/pareja-madura-que-se-re%C3%BAne-con-asesor-financiero-para-inversiones.jpg?s=612x612&w=0&k=20&c=48v-6w9CkK-uOyD2d5uTChS9EOlCv-bTELZaWw6jCd4=" alt="Asesoría técnica">
                    <div class="service-card-content">
                        <h3>Asesoría técnica</h3>
                        <p>Asesoramiento personalizado para optimizar la alimentación y manejo de tus animales.</p>
                        <a href="{{ route('servicios') }}#asesoria" class="btn btn-primary" style="margin-top: 15px; padding: 8px 15px;">Ver más</a>
                    </div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="{{ route('servicios') }}" class="btn btn-secondary">Ver todos los servicios</a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>¿Listo para mejorar la alimentación de tus animales?</h2>
            <p>Contáctanos hoy mismo y descubre cómo podemos ayudarte a optimizar la nutrición de tu granja.</p>
            <a href="{{ route('contacto') }}" class="btn btn-secondary" style="margin-top: 20px;">Contactar ahora</a>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Slider automático
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    const totalSlides = slides.length;
    
    function showSlide(n) {
        currentSlide = (n + totalSlides) % totalSlides;
        document.querySelector('.slider').style.transform = `translateX(-${currentSlide * 100}%)`;
        
        // Actualizar dots activos
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });
    }
    
    function nextSlide() {
        showSlide(currentSlide + 1);
    }
    
    // Configurar navegación por dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
        });
    });
    
    // Cambiar slide automáticamente cada 5 segundos
    setInterval(nextSlide, 5000);
</script>
@endpush