<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="wow.css">
    <link rel="stylesheet" href="products_new.css">
    <link rel="stylesheet" href="products_buttons.css?v=<?php echo time(); ?>">
    <title>Products - Patisserie Haloui</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- Logo -->
            <div class="nav-logo">
                <a href="index.html" class="logo-link">
                    <img src="imag/_design_a_professi_image_-removebg-preview.png" alt="Haloui - patisserie et confiserie" class="logo-image">
                </a> 
            </div>
            <!-- Desktop Navigation -->
            <div class="nav-menu">
                <a href="wow.php" class="nav-link">Accueil</a>
                <a href="products.php" class="nav-link active">Produits</a>
                <a href="#:" class="nav-link">Collections</a>
                <a href="about.php" class="nav-link">A-propos</a>
            </div>

            <!-- Order Button & Mobile Menu -->
            <div class="nav-actions">
                <a href="products.php" class="order-btn">Commander</a>
                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle mobile menu">
                    <span class="hamburger"></span>
                    <span class="hamburger"></span>
                    <span class="hamburger"></span>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <a href="index.php" class="mobile-nav-link">Home</a>
            <a href="products.php" class="mobile-nav-link">Products</a>
            <a href="collections.php" class="mobile-nav-link">Collections</a>
            <a href="about.php" class="mobile-nav-link">About Us</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <h1 class="hero-title">Our Creations</h1>
                <p class="hero-subtitle">Explore our delicious collection of handcrafted pastries and treats</p>
            </div>
        </section>

        <!-- Products Section -->
        <section class="products-section">
            <div class="container">
                <div class="products-layout">
                    <!-- Left Sidebar - Categories -->
                    <aside class="categories-sidebar">
                        <h3 class="sidebar-title">Categories</h3>
                        <div id="categoriesList" class="categories-list">
                            <!-- Categories will be loaded here -->
                        </div>
                        
                        <!-- Loading state for categories -->
                        <div id="categoriesLoading" class="loading-state">
                            <div class="loading-spinner"></div>
                            <p>Loading categories...</p>
                        </div>
                    </aside>

                    <!-- Main Content Area -->
                    <main class="products-main">
                        <!-- Featured Section -->
                        <div class="featured-section">
                            <div class="featured-image">
                                <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&h=250&fit=crop" alt="Featured Product" id="featuredImage">
                            </div>
                            <div class="featured-content">
                                <h2 id="featuredTitle">Delicious Handcrafted Cakes</h2>
                                <p id="featuredDescription">Discover our exquisite collection of handcrafted cakes, made with the finest ingredients and traditional techniques. Each cake is a masterpiece of flavor and artistry.</p>
                                <button class="view-all-btn" id="viewAllBtn">View All Products</button>
                            </div>
                        </div>

                        <!-- Category Products Section -->
                        <div class="category-products-section">
                            <!-- Category Header -->
                            <div class="category-header">
                                <h3 id="categoryName" class="category-name">Healthy Dish</h3>
                                <span id="categoryBadge" class="category-badge">New</span>
                                <button id="viewAllCategoryBtn" class="view-all-category-btn">View All Products</button>
                            </div>

                            <!-- Products Grid -->
                            <div id="productsGrid" class="products-grid">
                                <!-- Products will be loaded here -->
                            </div>

                            <!-- Loading state for products -->
                            <div id="productsLoading" class="loading-state" style="display: none;">
                                <div class="loading-spinner"></div>
                                <p>Loading products...</p>
                            </div>
                        </div>

                        <!-- Additional Category Sections -->
                        <div id="additionalCategories">
                            <!-- Additional category sections will be loaded here -->
                        </div>
                    </main>
                </div>
            </div>
        </section>
    </main>
    
     <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-main">
                <!-- Logo Section -->
                <div class="footer-logo">
                    <img src="imag/_design_a_professi_image_-removebg-preview.png" width="80" height="70" alt="Haloui Logo">
                </div>

                <!-- Newsletter Section -->
                <div class="newsletter-section">
                    <form class="newsletter-form" onsubmit="handleSubscribe(event)">
                        <input 
                            type="email" 
                            class="email-input" 
                            placeholder="Your email" 
                            required
                            aria-label="Email for newsletter"
                        >
                        <button type="submit" class="subscribe-btn">
                            <svg class="social-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
                            S’abonner
                        </button>
                    </form>
                    <p class="newsletter-text">Inscrivez-vous à notre newsletter pour des nouvelles gourmandes !</p>
                </div>

                <!-- Social Media Links -->
                <div class="social-links">
                    <a href="https://instagram.com/halawi" class="social-link" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg class="social-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    <a href="https://facebook.com/halawi" class="social-link" target="_blank" rel="noopener" aria-label="Facebook">
                        <svg class="social-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="mailto:info@halawi.com" class="social-link" aria-label="Email">
                        <svg class="social-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p class="copyright">© 2025 Haloui Pâtisserie. Tous droits réservés.</p>
                <div class="footer-links">
                    <a href="privacy.php">Politique de confidentialité</a>
                    <a href="terms.php">Conditions d’utilisation</a>
                    <a href="contact.php">Contactez-nous</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h3>Votre Panier</h3>
            <button class="close-cart" id="closeCart">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="cart-items" id="cartItemsContainer">
            <!-- Les articles du panier seront ajoutés ici dynamiquement -->
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span id="cartTotal">0.00 DH</span>
            </div>
            <a href="checkout.php" class="btn-checkout">Passer à la caisse</a>
            <button class="btn-continue-shopping" id="continueShopping">Continuer vos achats</button>
        </div>
    </div>
    <div class="cart-overlay" id="cartOverlay"></div>

    <!-- Notification de panier -->
    <div class="cart-notification" id="cartNotification">
        <div class="notification-content">
            <i class="fas fa-check-circle"></i>
            <p>Produit ajouté au panier!</p>
        </div>
    </div>

    <!-- Bouton flottant du panier -->
    <div class="floating-cart-btn" id="floatingCartBtn">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-counter" id="cartCounter">0</span>
    </div>

    <script>
        // Products Page JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
            loadFeaturedProducts();
        });

        // Load categories from API
        async function loadCategories() {
            const categoriesLoading = document.getElementById('categoriesLoading');
            const categoriesList = document.getElementById('categoriesList');
            
            try {
                categoriesLoading.style.display = 'block';
                const response = await fetch('api_get_all_categories.php');
                const result = await response.json();
                
                if (result.success) {
                    categoriesLoading.style.display = 'none';
                    categoriesList.innerHTML = result.data.map(category => `
                        <div class="category-item" data-id="${category.ID_CATEGORIE}" onclick="selectCategory(${category.ID_CATEGORIE}, '${category.NAME}')">
                            <span class="category-name">${category.NAME}</span>
                            <span class="category-count">${category.product_count || 0}</span>
                        </div>
                    `).join('');
                    
                    // Load first category by default
                    if (result.data.length > 0) {
                        selectCategory(result.data[0].ID_CATEGORIE, result.data[0].NAME);
                    }
                } else {
                    throw new Error(result.error || 'Failed to load categories');
                }
            } catch (error) {
                categoriesLoading.innerHTML = `
                    <div class="error-state">
                        <p>Error loading categories: ${error.message}</p>
                        <button onclick="loadCategories()" class="retry-btn">Retry</button>
                    </div>
                `;
                console.error('Error loading categories:', error);
            }
        }

        // Load featured products
        async function loadFeaturedProducts() {
            try {
                const response = await fetch('api_get_all_products.php');
                const result = await response.json();
                
                if (result.success && result.data.products_flat.length > 0) {
                    const featured = result.data.products_flat[0];
                    document.getElementById('featuredImage').src = featured.IMAGE_URL || 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&h=250&fit=crop';
                    document.getElementById('featuredTitle').textContent = featured.PRODUCT_NAME || 'Featured Product';
                    document.getElementById('featuredDescription').textContent = featured.PRODUCT_DESCRIPTION || 'Discover our featured product';
                }
            } catch (error) {
                console.error('Error loading featured products:', error);
            }
        }

        // Select category and load products
        async function selectCategory(categoryId, categoryName) {
            // Update active category
            document.querySelectorAll('.category-item').forEach(item => {
                item.classList.remove('active');
            });
            const categoryItem = document.querySelector(`[data-id="${categoryId}"]`);
            if (categoryItem) {
                categoryItem.classList.add('active');
            }
            
            // Update category header
            document.getElementById('categoryName').textContent = categoryName;
            
            // Load products for this category
            await loadProductsByCategory(categoryId);
        }

        // Load products by category
        async function loadProductsByCategory(categoryId) {
            const productsLoading = document.getElementById('productsLoading');
            const productsGrid = document.getElementById('productsGrid');
            
            try {
                productsLoading.style.display = 'block';
                productsGrid.innerHTML = '';
                
                const response = await fetch(`api_get_products_by_category.php?category_id=${categoryId}`);
                const result = await response.json();
                
                if (result.success) {
                    productsLoading.style.display = 'none';
                    
                    if (result.data.products.length > 0) {
                        productsGrid.innerHTML = result.data.products.map(product => `
                            <div class="product-card">
                                <img src="${product.IMAGE_URL || 'https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?w=200&h=150&fit=crop'}" 
                                     alt="${product.NAME}" class="product-image">
                                <div class="product-info">
                                    <h4 class="product-name">${product.NAME}</h4>
                                    <p class="product-description">${product.DESCRIPTION || ''}</p>
                                    <div class="product-price">${product.PRIX} DH</div>
                                    <div class="product-actions">
                                        <button class="btn-view" onclick="viewProduct(${product.ID_PRODUIT})">View</button>
                                        <button class="btn-add" onclick="addToCart(${product.ID_PRODUIT})">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        productsGrid.innerHTML = '<p class="no-products">No products found in this category.</p>';
                    }
                } else {
                    throw new Error(result.error || 'Failed to load products');
                }
            } catch (error) {
                productsLoading.style.display = 'none';
                productsGrid.innerHTML = `
                    <div class="error-state">
                        <p>Error loading products: ${error.message}</p>
                        <button onclick="loadProductsByCategory(${categoryId})" class="retry-btn">Retry</button>
                    </div>
                `;
                console.error('Error loading products:', error);
            }
        }

        // View product details
        function viewProduct(productId) {
            window.location.href = `product-detail.php?id=${productId}`;
        }

        // Add to cart
        function addToCart(productId) {
            console.log('Adding product to cart:', productId);
            
            // Ajouter directement au panier sans récupérer les détails du produit
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: productId,
                    quantity: 1
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Mettre à jour l'affichage du panier
                    updateCartDisplay(data.cart);
                    
                    // Afficher une notification
                    showCartNotification(`Produit ajouté au panier !`);
                    
                    // Mettre à jour le compteur du panier
                    updateCartCounter(data.cartCount);
                    
                    // Ouvrir le panier
                    openCart();
                } else {
                    showCartNotification('Erreur lors de l\'ajout au panier', 'error');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                showCartNotification('Erreur lors de l\'ajout au panier', 'error');
            });
        }

        // Mettre à jour l'affichage du panier
        function updateCartDisplay(cart) {
            const cartItemsContainer = document.getElementById('cartItemsContainer');
            const cartTotal = document.getElementById('cartTotal');
            
            if (!cart || cart.length === 0) {
                cartItemsContainer.innerHTML = `
                    <div class="empty-cart-message">
                        <div class="empty-cart-icon">
                            <i class="fas fa-shopping-basket"></i>
                        </div>
                        <p>Votre panier est vide</p>
                    </div>
                `;
                cartTotal.textContent = '0.00 DH';
                return;
            }
            
            let totalAmount = 0;
            let cartHTML = '';
            
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                totalAmount += itemTotal;
                
                cartHTML += `
                    <div class="cart-item" data-id="${item.id}">
                        <div class="cart-item-image">
                            <img src="${item.image || 'assets/images/placeholder.jpg'}" alt="${item.name}">
                        </div>
                        <div class="cart-item-details">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">${item.price.toFixed(2)} DH</div>
                            <div class="cart-item-quantity">
                                <button class="quantity-btn minus" onclick="updateCartItemQuantity(${item.id}, -1)">-</button>
                                <span class="quantity-value">${item.quantity}</span>
                                <button class="quantity-btn plus" onclick="updateCartItemQuantity(${item.id}, 1)">+</button>
                            </div>
                        </div>
                        <button class="remove-item" onclick="removeCartItem(${item.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `;
            });
            
            cartItemsContainer.innerHTML = cartHTML;
            cartTotal.textContent = totalAmount.toFixed(2) + ' DH';
        }

        // Mettre à jour la quantité d'un article du panier
        function updateCartItemQuantity(productId, change) {
            fetch('update_cart_item.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: productId,
                    change: change
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartDisplay(data.cart);
                    updateCartCounter(data.cartCount);
                } else {
                    showCartNotification('Erreur lors de la mise à jour du panier', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showCartNotification('Erreur lors de la mise à jour du panier', 'error');
            });
        }

        // Supprimer un article du panier
        function removeCartItem(productId) {
            fetch('remove_cart_item.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartDisplay(data.cart);
                    updateCartCounter(data.cartCount);
                    showCartNotification('Article supprimé du panier');
                } else {
                    showCartNotification('Erreur lors de la suppression de l\'article', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showCartNotification('Erreur lors de la suppression de l\'article', 'error');
            });
        }

        // Afficher une notification de panier
        function showCartNotification(message, type = 'success') {
            const notification = document.getElementById('cartNotification');
            const notificationContent = notification.querySelector('.notification-content');
            
            // Mettre à jour le contenu
            notificationContent.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <p>${message}</p>
            `;
            
            // Ajouter la classe pour l'animation
            notification.classList.add('show');
            
            // Masquer après 3 secondes
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Mettre à jour le compteur du panier
        function updateCartCounter(count) {
            const cartCounter = document.getElementById('cartCounter');
            cartCounter.textContent = count;
            cartCounter.classList.add('pulse');
            setTimeout(() => {
                cartCounter.classList.remove('pulse');
            }, 500);
        }

        // Ouvrir le panier
        function openCart() {
            document.getElementById('cartSidebar').classList.add('active');
            document.getElementById('cartOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Fermer le panier
        function closeCart() {
            document.getElementById('cartSidebar').classList.remove('active');
            document.getElementById('cartOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }

        // Initialiser les événements du panier
        document.addEventListener('DOMContentLoaded', function() {
            // Charger le panier au chargement de la page
            loadCart();
            
            // Événement pour ouvrir le panier
            document.getElementById('floatingCartBtn').addEventListener('click', openCart);
            
            // Événements pour fermer le panier
            document.getElementById('closeCart').addEventListener('click', closeCart);
            document.getElementById('cartOverlay').addEventListener('click', closeCart);
            document.getElementById('continueShopping').addEventListener('click', closeCart);
        });

        // Charger le panier depuis la session
        function loadCart() {
            fetch('get_cart.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartDisplay(data.cart);
                        updateCartCounter(data.cartCount);
                    }
                })
                .catch(error => {
                    console.error('Error loading cart:', error);
                });
        }

        // Newsletter subscription
        function handleSubscribe(event) {
            event.preventDefault();
            const email = event.target.querySelector('.email-input').value;
            alert(`Thank you for subscribing with email: ${email}`);
            event.target.reset();
        }
    </script>
</body>
</html>

