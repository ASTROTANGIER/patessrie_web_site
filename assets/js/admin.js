/**
 * Admin Dashboard JavaScript
 * Patisserie Haloui
 */

document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const isMobile = window.innerWidth < 992;
        if (isMobile && sidebar && sidebar.classList.contains('active')) {
            if (!sidebar.contains(event.target) && event.target !== sidebarToggle) {
                sidebar.classList.remove('active');
            }
        }
    });
});

// Gestion de la suppression des produits
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les boutons de suppression
    const deleteButtons = document.querySelectorAll('.delete-product');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            
            if (confirm(`Êtes-vous sûr de vouloir supprimer le produit "${productName}" ?`)) {
                // Ici, vous pouvez implémenter la logique de suppression
                // Par exemple, rediriger vers delete_product.php?id=productId
                window.location.href = `delete_product.php?id=${productId}`;
            }
        });
    });
});


