/**
 * Checkout Page JavaScript
 * Pâtisserie Haloui
 */

document.addEventListener('DOMContentLoaded', function() {
    // Gestion des onglets de paiement
    const paymentTabs = document.querySelectorAll('.payment-tab');
    const paymentForms = document.querySelectorAll('.payment-form');
    
    paymentTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Retirer la classe active de tous les onglets
            paymentTabs.forEach(t => t.classList.remove('active'));
            
            // Ajouter la classe active à l'onglet cliqué
            this.classList.add('active');
            
            // Masquer tous les formulaires
            paymentForms.forEach(form => form.classList.remove('active'));
            
            // Afficher le formulaire correspondant
            const method = this.getAttribute('data-method');
            document.querySelector(`.${method}-form`).classList.add('active');
        });
    });
    
    // Gestion des boutons de quantité
    const minusBtns = document.querySelectorAll('.quantity-btn.minus');
    const plusBtns = document.querySelectorAll('.quantity-btn.plus');
    const removeBtns = document.querySelectorAll('.remove-item');
    
    // Fonction pour mettre à jour les totaux
    function updateTotals() {
        let subtotal = 0;
        const cartItems = document.querySelectorAll('.cart-item');
        
        cartItems.forEach(item => {
            const price = parseFloat(item.querySelector('.item-price').textContent.replace('DH', '').trim());
            const quantity = parseInt(item.querySelector('.quantity-value').textContent);
            const itemTotal = price * quantity;
            
            item.querySelector('.item-total').textContent = itemTotal.toFixed(2) + ' DH';
            subtotal += itemTotal;
        });
        
        // Mise à jour du sous-total
        document.querySelector('.summary-row:first-child span:last-child').textContent = subtotal.toFixed(2) + ' DH';
        
        // Récupération des frais de livraison
        const shipping = parseFloat(document.querySelector('.summary-row:nth-child(2) span:last-child').textContent.replace('DH', '').trim());
        
        // Mise à jour du total
        const total = subtotal + shipping;
        document.querySelector('.summary-row.total span:last-child').textContent = total.toFixed(2) + ' DH';
        
        // Mise à jour du bouton de paiement
        document.querySelector('.btn-payment').textContent = `Payer ${total.toFixed(2)} DH maintenant`;
        
        // Mettre à jour le panier dans la session via AJAX
        updateCartSession();
    }
    
    // Fonction pour mettre à jour le panier dans la session
    function updateCartSession() {
        const cartItems = document.querySelectorAll('.cart-item');
        const updatedCart = [];
        
        cartItems.forEach(item => {
            const id = item.querySelector('.quantity-btn').getAttribute('data-id');
            const name = item.querySelector('.item-name').textContent;
            const price = parseFloat(item.querySelector('.item-price').textContent.replace('DH', '').trim());
            const quantity = parseInt(item.querySelector('.quantity-value').textContent);
            const image = item.querySelector('.item-image img').getAttribute('src');
            
            updatedCart.push({
                id: id,
                name: name,
                price: price,
                quantity: quantity,
                image: image
            });
        });
        
        // Envoyer les données mises à jour au serveur
        fetch('update_cart_session.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                cart: updatedCart
            })
        })
        .catch(error => {
            console.error('Error updating cart session:', error);
        });
    }
    
    // Gestion des boutons moins
    minusBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const quantityEl = this.nextElementSibling;
            let quantity = parseInt(quantityEl.textContent);
            
            if (quantity > 1) {
                quantity--;
                quantityEl.textContent = quantity;
                updateTotals();
            }
        });
    });
    
    // Gestion des boutons plus
    plusBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const quantityEl = this.previousElementSibling;
            let quantity = parseInt(quantityEl.textContent);
            
            quantity++;
            quantityEl.textContent = quantity;
            updateTotals();
        });
    });
    
    // Gestion des boutons de suppression
    removeBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const cartItem = this.closest('.cart-item');
            const productId = this.getAttribute('data-id');
            
            // Animation de suppression
            cartItem.style.opacity = '0';
            cartItem.style.height = '0';
            cartItem.style.margin = '0';
            cartItem.style.padding = '0';
            cartItem.style.overflow = 'hidden';
            cartItem.style.transition = 'all 0.3s ease';
            
            // Suppression après l'animation
            setTimeout(() => {
                cartItem.remove();
                updateTotals();
                
                // Vérifier s'il reste des articles dans le panier
                const remainingItems = document.querySelectorAll('.cart-item');
                if (remainingItems.length === 0) {
                    // Rediriger vers la page des produits
                    window.location.href = 'products.php';
                }
            }, 300);
            
            // Supprimer l'article du panier via AJAX
            fetch('remove_cart_item.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: productId
                })
            })
            .catch(error => {
                console.error('Error removing item:', error);
            });
        });
    });
    
    // Validation du formulaire de paiement
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            // Vérification des champs obligatoires
            const requiredFields = this.querySelectorAll('input[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#dc3545';
                    isValid = false;
                } else {
                    field.style.borderColor = '#e5e7eb';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Faire défiler jusqu'au premier champ invalide
                const firstInvalid = this.querySelector('input[required]:invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                // Afficher un message d'erreur
                alert('Veuillez remplir tous les champs obligatoires.');
            }
        });
    }
});

