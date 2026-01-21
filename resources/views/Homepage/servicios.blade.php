@extends('layouts.home')

@section('title', 'Servicios - Nupilca C.A')

@section('content')
    <!-- Hero Section para servicios -->
    <section class="services-hero">
        <div class="container">
            <h1>Nuestros servicios agropecuarios</h1>
            <p>Soluciones integrales para la producción, nutrición y cuidado de tus animales de granja. Calidad garantizada para el crecimiento saludable de tu negocio agropecuario.</p>
        </div>
    </section>

    <!-- Sección de servicios completos -->
    <section class="services-section">
        <div class="container">
            <div class="section-title">
                <h2>Servicios especializados</h2>
                <p>Ofrecemos una amplia gama de servicios diseñados para optimizar la producción y salud de tus animales</p>
            </div>
            
            <!-- Producción de alimentos -->
            <div class="main-service" id="produccion">
                <div class="service-image">
                    <img src="https://4368135.fs1.hubspotusercontent-na1.net/hubfs/4368135/carros-de-alimentacion-1.jpg" alt="Producción de alimentos">
                </div>
                <div class="service-content">
                    <h3>Producción de alimentos balanceados</h3>
                    <p>Fabricamos alimentos de alta calidad nutricional para todas las especies de granja, utilizando ingredientes seleccionados y procesos controlados que garantizan la máxima eficiencia alimenticia.</p>
                    
                    <ul class="service-features">
                        <li><i class="fas fa-check"></i> Fórmulas especializadas por especie y etapa de desarrollo</li>
                        <li><i class="fas fa-check"></i> Control de calidad en cada etapa de producción</li>
                        <li><i class="fas fa-check"></i> Ingredientes 100% nutritivos</li>
                        <li><i class="fas fa-check"></i> Procesos certificados y estandarizados</li>
                    </ul>
                </div>
            </div>
            
            <!-- Distribución -->
            <div class="main-service" id="distribucion">
                <div class="service-content">
                    <h3>Despacho de alimento</h3>
                    <p>Contamos con despacho en nuestra sede, donde nuestros clientes podrán retirar sus pedidos de nuestros productos, garantizando frescura y calidad en cada entrega.</p>
                    
                    <ul class="service-features">
                        <li><i class="fas fa-check"></i> Entregas programadas y urgentes</li>
                        <li><i class="fas fa-check"></i> Flota especializada para productos agropecuarios</li>
                        <li><i class="fas fa-check"></i> Despacho de pedidos personalizados</li>
                    </ul>
                </div>
                <div class="service-image">
                    <img src="https://img.freepik.com/fotos-premium/camion-cargado-sacos-que-contienen-cacahuetes-transporte-india_729664-198.jpg" alt="Distribución">
                </div>
            </div>
            
            <!-- Asesoría técnica -->
            <div class="main-service" id="asesoria">
                <div class="service-image">
                    <img src="https://media.istockphoto.com/id/1413761479/es/foto/pareja-madura-que-se-re%C3%BAne-con-asesor-financiero-para-inversiones.jpg?s=612x612&w=0&k=20&c=48v-6w9CkK-uOyD2d5uTChS9EOlCv-bTELZaWw6jCd4=" alt="Asesoría técnica">
                </div>
                <div class="service-content">
                    <h3>Asesoría técnica especializada</h3>
                    <p>Nuestro equipo de nutricionistas brinda asesoramiento personalizado para optimizar la alimentación, manejo y salud de tus animales, maximizando la productividad de tu granja mediante una buena formulación del alimento.</p>
                    
                    <ul class="service-features">
                        <li><i class="fas fa-check"></i> Diagnóstico nutricional gratuito</li>
                        <li><i class="fas fa-check"></i> Planes de alimentación personalizados</li>
                        <li><i class="fas fa-check"></i> Capacitación en manejo animal</li>
                        <li><i class="fas fa-check"></i> Visitas técnicas a granjas</li>
                    </ul>
                </div>
            </div>
            
            <!-- Servicios adicionales en grid 
            <div class="section-title" style="margin-top: 80px;">
                <h2>Servicios Complementarios</h2>
                <p>Soluciones adicionales para el manejo integral de tu operación agropecuaria</p>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <img src="https://www.icovv.com/wp-content/uploads/2025/08/veterinario-en-bata-de-laboratorio-de-pie-en-el-establo-scaled.jpg" alt="Nutrición especializada">
                    <div class="service-card-content">
                        <h4>Nutrición Especializada</h4>
                        <p>Formulaciones específicas para animales con necesidades nutricionales particulares o en etapas críticas de desarrollo.</p>
                    </div>
                </div>
                
                <div class="service-card">
                    <img src="https://images.unsplash.com/photo-1596733430284-f7437764b1a9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Análisis de suelo">
                    <div class="service-card-content">
                        <h4>Análisis de Suelo y Forrajes</h4>
                        <p>Evaluación de la calidad nutricional de pastos y suelos para optimizar la alimentación del ganado en pastoreo.</p>
                    </div>
                </div>
                
                <div class="service-card">
                    <img src="https://images.unsplash.com/photo-1592394675778-4239b838fb2c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80" alt="Consultoría en granjas">
                    <div class="service-card-content">
                        <h4>Consultoría en Manejo de Granjas</h4>
                        <p>Asesoría integral para la planificación, organización y mejora continua de las operaciones en granjas productivas.</p>
                    </div>
                </div>
            </div>-->
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="contacto">
        <div class="container">
            <h2>¿Necesitas un servicio personalizado?</h2>
            <p>Contáctanos para diseñar un plan de servicios adaptado a las necesidades específicas de tu granja.</p>
            <a href="{{ route('contacto') }}" class="btn btn-secondary" style="background-color: white; color: var(--primary-color);">Solicitar Presupuesto</a>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Estilos específicos para la página de servicios */
    .services-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1701959665726-f989d7f3754b?q=80&w=1032&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 120px 0;
        text-align: center;
    }
    
    .services-hero h1 {
        font-size: 3.5rem;
        margin-bottom: 20px;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    .services-hero p {
        font-size: 1.3rem;
        max-width: 800px;
        margin: 0 auto 30px;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    /* Sección de servicios completos */
    .services-section {
        padding: 80px 0;
        background-color: white;
    }
    
    .section-title {
        text-align: center;
        margin-bottom: 50px;
    }
    
    .section-title h2 {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 15px;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    .section-title p {
        color: #666;
        max-width: 700px;
        margin: 0 auto;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    /* Servicio principal */
    .main-service {
        display: flex;
        align-items: center;
        gap: 50px;
        margin-bottom: 80px;
        padding: 40px;
        background-color: #f0f7f0;
        border-radius: 15px;
    }
    
    .main-service:nth-child(even) {
        flex-direction: row-reverse;
    }
    
    .service-image {
        flex: 1;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .service-image img {
        width: 100%;
        height: 350px;
        object-fit: cover;
        transition: transform 0.5s;
        max-width: 100%;
    }
    
    .service-image:hover img {
        transform: scale(1.05);
    }
    
    .service-content {
        flex: 1;
    }
    
    .service-content h3 {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 20px;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    .service-content p {
        margin-bottom: 20px;
        font-size: 1.1rem;
        text-align: justify;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    .service-features {
        list-style: none;
        margin: 25px 0;
    }
    
    .service-features li {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        text-align: justify;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    .service-features i {
        color: var(--primary-color);
        margin-right: 10px;
        flex-shrink: 0;
    }
    
    /* Grid de servicios adicionales */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 50px;
    }
    
    .service-card {
        background-color: var(--light-color);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
    }
    
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .service-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        max-width: 100%;
    }
    
    .service-card-content {
        padding: 25px;
    }
    
    .service-card h4 {
        color: var(--primary-color);
        margin-bottom: 10px;
        font-size: 1.4rem;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    .service-card p {
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    /* Tabla de comparación */
    .comparison-table {
        margin: 80px 0;
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }
    
    .comparison-table h3 {
        text-align: center;
        padding: 30px;
        background-color: var(--primary-color);
        color: white;
        margin: 0;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th, td {
        padding: 20px;
        text-align: left;
        border-bottom: 1px solid #eee;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    th {
        background-color: #f9f9f9;
        font-weight: 600;
        color: var(--primary-color);
    }
    
    tr:hover {
        background-color: #f5f5f5;
    }
    
    .check {
        color: var(--primary-color);
        font-size: 1.2rem;
    }
    
    /* CTA Section */
    .cta {
        background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        color: white;
        text-align: center;
        padding: 80px 0;
        margin-top: 50px;
    }
    
    .cta h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    .cta p {
        font-size: 1.2rem;
        max-width: 700px;
        margin: 0 auto 30px;
        overflow-wrap: break-word;
        word-wrap: break-word;
    }
    
    /* Responsive - Breakpoints principales */
    @media (max-width: 1200px) {
        .services-hero h1 {
            font-size: 3rem;
        }
        
        .services-hero p {
            font-size: 1.2rem;
        }
        
        .main-service {
            gap: 40px;
            padding: 35px;
        }
    }
    
    @media (max-width: 1024px) {
        .services-hero {
            padding: 80px 0;
        }
        
        .services-hero h1 {
            font-size: 2.8rem;
        }
        
        .services-hero p {
            font-size: 1.1rem;
        }
        
        .services-section {
            padding: 70px 0;
        }
        
        .section-title h2 {
            font-size: 2.2rem;
        }
        
        .service-content h3 {
            font-size: 1.8rem;
        }
        
        .service-content p {
            font-size: 1rem;
        }
        
        .main-service {
            gap: 35px;
            padding: 30px;
            margin-bottom: 60px;
        }
        
        .service-image img {
            height: 300px;
        }
        
        .cta h2 {
            font-size: 2.2rem;
        }
        
        .cta p {
            font-size: 1.1rem;
        }
        
        .services-grid {
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }
    }
    
    @media (max-width: 992px) {
        .main-service {
            flex-direction: column;
            text-align: center;
        }
        
        .main-service:nth-child(even) {
            flex-direction: column;
        }
        
        .service-image, .service-content {
            width: 100%;
        }
        
        .service-image img {
            height: 280px;
        }
        
        .service-features {
            text-align: left;
        }
        
        .services-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
        
        table {
            display: block;
            overflow-x: auto;
        }
    }
    
    @media (max-width: 768px) {
        .services-hero {
            padding: 70px 0;
        }
        
        .services-hero h1 {
            font-size: 2.4rem;
        }
        
        .services-hero p {
            font-size: 1.1rem;
            padding: 0 15px;
        }
        
        .services-section {
            padding: 60px 0;
        }
        
        .section-title {
            margin-bottom: 40px;
        }
        
        .section-title h2 {
            font-size: 2rem;
        }
        
        .section-title p {
            padding: 0 15px;
        }
        
        .main-service {
            padding: 25px 20px;
            margin-bottom: 50px;
            gap: 30px;
        }
        
        .service-content h3 {
            font-size: 1.7rem;
        }
        
        .service-image img {
            height: 250px;
        }
        
        .service-features li {
            font-size: 0.95rem;
        }
        
        .services-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .service-card img {
            height: 180px;
        }
        
        .service-card-content {
            padding: 20px;
        }
        
        .cta {
            padding: 60px 0;
        }
        
        .cta h2 {
            font-size: 2rem;
        }
        
        .cta p {
            font-size: 1.1rem;
            padding: 0 15px;
        }
        
        .comparison-table {
            margin: 50px 0;
        }
        
        .comparison-table h3 {
            padding: 20px;
            font-size: 1.3rem;
        }
        
        th, td {
            padding: 15px 10px;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 576px) {
        .services-hero {
            padding: 60px 0;
        }
        
        .services-hero h1 {
            font-size: 2rem;
            padding: 0 10px;
        }
        
        .services-hero p {
            font-size: 1rem;
            padding: 0 10px;
        }
        
        .services-section {
            padding: 50px 0;
        }
        
        .section-title h2 {
            font-size: 1.8rem;
        }
        
        .section-title p {
            font-size: 1rem;
        }
        
        .main-service {
            padding: 20px 15px;
            margin-bottom: 40px;
            gap: 25px;
        }
        
        .service-content h3 {
            font-size: 1.5rem;
        }
        
        .service-content p {
            font-size: 0.95rem;
        }
        
        .service-image img {
            height: 220px;
        }
        
        .service-features li {
            font-size: 0.9rem;
        }
        
        .service-card h4 {
            font-size: 1.2rem;
        }
        
        .cta {
            padding: 50px 0;
        }
        
        .cta h2 {
            font-size: 1.8rem;
        }
        
        .cta p {
            font-size: 1rem;
        }
    }
    
    @media (max-width: 480px) {
        .services-hero h1 {
            font-size: 1.8rem;
        }
        
        .services-hero p {
            font-size: 0.95rem;
        }
        
        .btn {
            padding: 10px 18px;
            font-size: 0.9rem;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            display: block;
        }
        
        .main-service .btn {
            width: auto;
            display: inline-block;
        }
        
        .section-title h2 {
            font-size: 1.7rem;
        }
        
        .service-content h3 {
            font-size: 1.4rem;
        }
        
        .service-image img {
            height: 200px;
        }
        
        .service-features {
            margin: 20px 0;
        }
        
        .service-features li {
            font-size: 0.85rem;
            align-items: flex-start;
        }
        
        .service-card-content {
            padding: 15px;
        }
        
        .cta h2 {
            font-size: 1.6rem;
        }
    }
    
    @media (max-width: 360px) {
        .services-hero h1 {
            font-size: 1.6rem;
        }
        
        .services-hero p {
            font-size: 0.9rem;
        }
        
        .section-title h2 {
            font-size: 1.5rem;
        }
        
        .section-title p {
            font-size: 0.9rem;
        }
        
        .main-service {
            padding: 15px 10px;
            margin-bottom: 30px;
            gap: 20px;
        }
        
        .service-content h3 {
            font-size: 1.3rem;
        }
        
        .service-content p {
            font-size: 0.9rem;
        }
        
        .service-image img {
            height: 180px;
        }
        
        .service-card img {
            height: 160px;
        }
        
        .service-card h4 {
            font-size: 1.1rem;
        }
        
        .service-card p {
            font-size: 0.9rem;
        }
        
        .cta h2 {
            font-size: 1.4rem;
        }
        
        .cta p {
            font-size: 0.9rem;
        }
    }
    
    /* Mejoras para orientación horizontal en móviles */
    @media (max-height: 500px) and (orientation: landscape) {
        .services-hero {
            padding: 40px 0;
        }
        
        .services-hero h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .services-hero p {
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        
        .main-service {
            padding: 20px;
            margin-bottom: 40px;
        }
        
        .service-image img {
            height: 200px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Scroll suave para anclas
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
</script>
@endpush