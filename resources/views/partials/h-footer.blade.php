<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-column">
                <h3>Nupilca C.A</h3>
                <p>Líderes en producción y distribución de alimentos balanceados para animales de granja.</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <div class="footer-column">
                <h3>Enlaces rápidos</h3>
                <ul>
                    <li><a href="{{ route('Homepage.index') }}">Inicio</a></li>
                    <li><a href="{{ route('servicios') }}">Servicios</a></li>
                    <li><a href="{{ route('nosotros') }}">Nosotros</a></li>
                    <li><a href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Servicios</h3>
                <ul>
                    <li><a href="{{ route('servicios') }}">Producción de alimentos</a></li>
                    <li><a href="{{ route('servicios') }}">Despacho de alimento</a></li>
                    <li><a href="{{ route('servicios') }}">Asesoría técnica</a></li>
                    <li><a href="{{ route('servicios') }}">Productos especializados</a></li>
                </ul>
            </div>
        </div>
        
        <div class="copyright">
            <p>&copy; {{ date('Y') }} Nupilca C.A. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>