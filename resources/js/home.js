// Menú móvil mejorado
const menuToggle = document.querySelector('.menu-toggle');
const navLinks = document.querySelector('.nav-links');
const navButtons = document.querySelector('.nav-buttons');

// Crear una copia de los botones para el menú móvil
const mobileButtons = navButtons.cloneNode(true);
mobileButtons.classList.add('mobile');
mobileButtons.style.display = 'none';

// Insertar los botones después del menú
navLinks.parentNode.insertBefore(mobileButtons, navLinks.nextSibling);

menuToggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    
    // Mostrar/ocultar botones en móvil
    if (window.innerWidth <= 768) {
        if (navLinks.classList.contains('active')) {
            mobileButtons.style.display = 'flex';
        } else {
            mobileButtons.style.display = 'none';
        }
    }
});

// Cerrar menú al hacer clic en un enlace
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        navLinks.classList.remove('active');
        if (window.innerWidth <= 768) {
            mobileButtons.style.display = 'none';
        }
    });
});

// Cerrar menú al hacer clic en cualquier botón dentro del menú móvil
document.querySelectorAll('.mobile .btn').forEach(button => {
    button.addEventListener('click', () => {
        navLinks.classList.remove('active');
        mobileButtons.style.display = 'none';
    });
});

// Redimensionamiento de ventana
window.addEventListener('resize', () => {
    // Si la ventana es más grande que 768px, asegurarse de que el menú esté visible
    if (window.innerWidth > 768) {
        navLinks.classList.remove('active');
        mobileButtons.style.display = 'none';
    }
});