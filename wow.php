<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pâtisserie Haloui - Accueil</title>
    <link rel="stylesheet" href="wow.css">
    <!-- Ajout du nouveau fichier CSS pour les styles de boutons -->
    <link rel="stylesheet" href="button-styles.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Styles en ligne pour les boutons */
        .order-btn {
            background-color: #F0C808 !important;
        }
        .order-btn:hover {
            background-color: #c9a227 !important;
        }
        
        .cta-btn.primary {
            background: #F0C808 !important;
        }
        .cta-btn.primary:hover {
            background: #c9a227 !important;
        }
        
        .cta-btn.secondary:hover {
            background: #c9a227 !important;
        }
        
        .online-button {
            background-color: #F0C808 !important;
        }
        .online-button:hover {
            background-color: #c9a227 !important;
        }
        
        .custom-order-btn {
            background: #F0C808 !important;
        }
        .custom-order-btn:hover {
            background: #c9a227 !important;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <!-- Logo -->
            <div class="nav-logo">
                <a href="index.php" class="logo-link">
                    <img src="imag/_design_a_professi_image_-removebg-preview.png" alt="Haloui - patisserie et confiserie" class="logo-image">
                </a> 
            </div>
            <!-- Desktop Navigation -->
            <div class="nav-menu">
                <a href="wow.php" class="nav-link">Accueil</a>
                <a href="products.php" class="nav-link">Produits</a>
                <a href="#" class="nav-link">Collections</a>
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
            <a href="index.php" class="mobile-nav-link">Accueil</a>
            <a href="products.php" class="mobile-nav-link">Produits</a>
            <a href="collections.php" class="mobile-nav-link">Collections</a>
            <a href="about.php" class="mobile-nav-link">A-propos</a>
        </div>
    </nav>
    
    <main class="main-content">
        <!-- Background Slider -->
        <div class="background-slider">    
            <div class="slider-overlay"></div>
            <!-- Slides will be dynamically added by JavaScript -->
        </div>
        
        <!-- Content over the slider -->
        <div class="content-overlay">
            <div class="container">
                <div class="hero-section">
                    <h1 class="animated-title">Bienvenue chez Haloui</h1>
                    <p class="animated-subtitle">Pâtisserie et confiserie</p>
                    <p class="animated-description">Découvrez nos pâtisseries et desserts artisanaux haut de gamme.</p>
                    <div class="cta-buttons">
                        <a href="products.php" class="cta-btn primary">Explorer les produits</a>
                        <a href="products.php" class="cta-btn secondary">Commander</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <main class="main-content-collection">
        <!-- Collections Hero Section -->
        <section class="collections-hero">
            <div class="container-hero">
                <h1 class="collections-title">Collections</h1>
                <p class="collections-subtitle"> Une sélection raffinée pour chaque occasion</p>
            </div>
        </section>

        <!-- Collections Grid -->
        <section class="collections-grid">
            <div class="container-hero-grid">
                <div class="collections-wrapper">
                    
                    <!-- Ramadan Delights -->
                    <div class="collection-card" onclick="goToProducts('ramadan')">
                        <div class="collection-image">
                            <div class="collection-overlay">
                                <img src="imag/istockphoto-518468635-612x612.jpg" alt="Ramadan Delights">
                            </div>
                        </div>
                        <div class="collection-content">
                            <h3 class="collection-title">Gourmandises du Ramadan</h3>
                            <p class="collection-description">Pâtisseries traditionnelles du Ramadan, idéales pour partager un moment d’Iftar en famille ou entre amis.</p>
                        </div>
                    </div>

                    <!-- Wedding Favors -->
                    <div class="collection-card" onclick="goToProducts('wedding')">
                        <div class="collection-image">
                            <div class="collection-overlay">
                                <img src="imag/istockphoto-508459346-612x612.jpg" alt="Wedding Favors">
                            </div>
                        </div>
                        <div class="collection-content">
                            <h3 class="collection-title">Souvenirs de mariage</h3>
                            <p class="collection-description">Des douceurs exquisées pour vos célébrations les plus spéciales.</p>
                        </div>
                    </div>

                    <!-- Modern Sweets -->
                    <div class="collection-card" onclick="goToProducts('modern')">
                        <div class="collection-image">
                            <div class="collection-overlay">
                                <img src="imag/istockphoto-1867271285-612x612.jpg" alt="Modern Sweets">
                            </div>
                        </div>
                        <div class="collection-content">
                            <h3 class="collection-title">Pâtisseries modernes </h3>
                            <p class="collection-description">Saveurs innovantes et designs élégants pour un palais moderne.</p>
                        </div>
                    </div>

                    <!-- Savory Bites -->
                    <div class="collection-card" onclick="goToProducts('savory')">
                        <div class="collection-image">
                            <div class="collection-overlay">
                                <img src="imag/istockphoto-497959594-612x612.jpg" alt="Savory Bites">
                            </div>
                        </div>
                        <div class="collection-content">
                            <h3 class="collection-title"> Bouchées salées</h3>
                            <p class="collection-description">Une sélection de gourmandises salées raffinées pour chaque événement.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <!-- Best Sellers Section -->
    <section class="best-sellers">
        <div class="container">
            <!-- Best Sellers Header -->
            <div class="best-sellers-header">
                <div class="section-title">
                    <i class="gift-icon">🎁</i>
                    <h2>Meilleures ventes</h2>
                </div>
                <div class="promo-banner">
                    <span class="promo-text">Offre limitée : achetez 2 tartes aux fraises, recevez 1 macaron gratuit !</span>
                    <i class="arrow-icon">→</i>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="best-sellers-grid">
                <div class="product-item">
                    <div class="product-image-circle">
                        <img src="imag/product 1.jpg" alt="Cornes de Gazelle">
                    </div>
                    <h3 class="product-title">Cornes de Gazelle </h3>
                    <p class="product-description">Croissants aux amandes beurrés avec un délicat arôme de fleur d’oranger. </p>
                </div>

                <div class="product-item">
                    <div class="product-image-circle">
                        <img src="imag/istockphoto-508459346-612x612.jpg" alt="Briouat Amande">
                    </div>
                    <h3 class="product-title">Briouat aux amandes</h3>
                    <p class="product-description"> Triangles de pâte croustillante garnis d’une riche pâte d’amande et de miel.</p>
                </div>

                <div class="product-item">
                    <div class="product-image-circle">
                        <img src="imag/istockphoto-916575452-612x612.jpg" alt="Chebakia">
                    </div>
                    <h3 class="product-title">Chebakia</h3>
                    <p class="product-description">Pâtisseries traditionnelles au sésame et au miel, un incontournable du Ramadan.</p>
                </div>

                <div class="product-item">
                    <div class="product-image-circle">
                        <img src="imag/istockphoto-472496034-612x612.jpg" alt="Pistachio Éclair">
                    </div>
                    <h3 class="product-title">Basstila amandes</h3>
                    <p class="product-description"> Touche moderne : pâte à choux garnie de crème de pistache et feuille d’or.</p>
                </div>

                <div class="product-item">
                    <div class="product-image-circle">
                        <img src="imag/istockphoto-1867271285-612x612.jpg" alt="Mini Quiche Kefta">
                    </div>
                    <h3 class="product-title">Mini quiche à la kefta </h3>
                    <p class="product-description">Quiche salée à la kefta aux épices et herbes marocaines.</p>
                </div>

                <div class="product-item">
                    <div class="product-image-circle">
                        <img src="imag/istockphoto-1294321365-612x612.jpg" alt="Coconut Truffles">
                    </div>
                    <h3 class="product-title">Truffes à la noix de coco </h3>
                    <p class="product-description"> Boules fondantes à la noix de coco avec chocolat blanc et amande</p>
                </div>
            </div>

            <!-- View All Products Button -->
            <div class="view-all-container">
                <a href="products.php" class="view-all-btn">
                    Voir tous les produits
                    <span class="btn-arrow">▶</span>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Birthday Specials & Custom Orders Section -->
    <section class="birthday-specials">
        <div class="container">
            <div class="birthday-content">
                <div class="birthday-text">
                    <h2 class="birthday-title"> Offres spéciales anniversaire & commandes personnalisées</h2>
                    <p class="birthday-description">
                        Célébrez votre journée spéciale avec un gâteau sur mesure, adapté à vos souhaits.
Nous réalisons des gâteaux d’anniversaire élégants aux tons pastel ainsi que des douceurs pour toutes les occasions.
                    </p>
                    <a href="products.php" class="custom-order-btn">
                        <i class="envelope-icon">✉</i>
                        Demander une commande personnalisée
                    </a>
                </div>
                <div class="birthday-image">
                    <div class="cake-showcase">
                        <img src="imag/Mango Cake_Stencil Cake work.jpg" width="350" height="350" alt="Elegant Birthday Cake with Pink Roses">
                    </div>  
                </div>
            </div>
        </div>
    </section>
    <section class="our-story-section">
        <div class="story-container">
            <div class="story-image">
                <img src="imag/istockphoto-147933M3Alm.jpg" alt="Professional chef preparing pastries in kitchen">
            </div>
            <div class="story-content">
                <h2 class="story-title">Notre histoire</h2>
                <p class="story-text">
                    Fondée à Tanger, <span class="highlight">Halawi</span> apporte au monde les saveurs raffinées et l’art de la pâtisserie marocaine. Nos recettes allient traditions précieuses et élégance contemporaine, en utilisant uniquement les meilleurs ingrédients pour chaque occasion.
                </p>
                <a href="about.php" class="learn-more-btn">En savoir plus</a>
            </div>
        </div>
    </section>
    
    <section class="order-section">
        <h2 class="order-title">Commandez votre gourmandise marocaine</h2>
        <p class="order-subtitle">Prêt à ravir vos sens ? Passez votre commande dès aujourd’hui pour une livraison ou un retrait.</p>
        
        <div class="order-buttons">
            <!-- WhatsApp Button - Replace the phone number with your actual WhatsApp number -->
            <a href="https://wa.me/212600000000" class="order-button whatsapp-button" target="_blank" rel="noopener">
                <svg class="button-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Commander via WhatsApp
            </a>
            
            <!-- Order Online Button -->
            <a href="products.php" class="order-button online-button">
                <svg class="button-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5-7V8h-2v4H8l4 4 4-4h-2z"/>
                </svg>
                Commander en ligne
            </a>
        </div>
    </section>
    
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
    <script src="wow.js"></script>
</body>
</html>
