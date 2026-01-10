// Filtrar productos por categoría
document.addEventListener('DOMContentLoaded', function() {
    // Filtrar productos por categoría
    document.querySelectorAll('.category-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Remover clase activa de todos los botones
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Agregar clase activa al botón clickeado
            this.classList.add('active');
            
            // Obtener categoría seleccionada
            const category = this.dataset.category;
            
            // Mostrar/ocultar productos
            document.querySelectorAll('.product-card').forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Ver detalles del producto
    document.querySelectorAll('.view-product-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = parseInt(this.dataset.id);
            const product = productos.find(p => p.id === productId);
            
            if (product) {
                viewProduct(product);
            }
        });
    });

    // Cerrar modal
    document.getElementById('closeModal').addEventListener('click', closeModal);
    
    // Cerrar modal al hacer clic fuera del contenido
    document.getElementById('productModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Cerrar modal con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
});

// Ver detalles del producto
function viewProduct(product) {
    // Actualizar contenido del modal
    document.getElementById('modalTitle').textContent = product.nombre;
    document.getElementById('modalProductTitle').textContent = product.nombre;
    document.getElementById('modalCategory').textContent = product.categoria;
    document.getElementById('modalCategoryFull').textContent = product.categoria_completa;
    document.getElementById('modalDescription').textContent = product.descripcion;
    document.getElementById('modalPrice').textContent = `$${product.precio.toFixed(2)}`;
    document.getElementById('modalWeight').textContent = product.peso;
    document.getElementById('modalStock').textContent = `${product.stock} unidades`;
    document.getElementById('modalImage').src = product.imagen;
    document.getElementById('modalImage').alt = product.nombre;
    
    // Actualizar estado
    const statusElement = document.getElementById('modalStatus');
    statusElement.textContent = product.stock > 0 ? 'Disponible' : 'Agotado';
    statusElement.className = product.stock > 0 ? 'detail-value in-stock' : 'detail-value low-stock';
    
    // Mostrar modal
    document.getElementById('productModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    document.body.style.paddingRight = '15px'; // Compensar scrollbar
}

// Cerrar modal
function closeModal() {
    document.getElementById('productModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.body.style.paddingRight = '0';
}