<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="wow.css">
    <title>Patisserie Haloui</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        /* Styles pour tous les boutons */
        .order-btn,
        .cta-button,
        .subscribe-btn {
            background-color: #F0C808 !important;
            color: white !important;
            transition: all 0.3s ease !important;
        }
        
        .order-btn:hover,
        .cta-button:hover,
        .subscribe-btn:hover {
            background-color: #c9a227 !important;
            transform: translateY(-3px) !important;
        }
        
        /* Style spécifique pour le bouton CTA */
        .cta-button {
            background-color: #F0C808 !important;
            color: white !important;
            padding: 15px 40px !important;
            border: none !important;
            border-radius: 25px !important;
            font-size: 1rem !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            text-decoration: none !important;
            display: inline-block !important;
            font-family: inherit !important;
        }
        
        .cta-button:hover {
            background-color: #c9a227 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3) !important;
        }
        
        /* Style pour le bouton d'abonnement */
        .subscribe-btn {
            background: #F0C808 !important;
            color: white !important;
            border: none !important;
            padding: 0 20px !important;
            border-radius: 0 25px 25px 0 !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            transition: background 0.3s ease !important;
        }
        
        .subscribe-btn:hover {
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
                <a href="index.html" class="logo-link">
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
                <a href="products.php" class="order-btn">Order</a>
                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle mobile menu">
                    <span class="hamburger"></span>
                    <span class="hamburger"></span>
                    <span class="hamburger"></span>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <a href="wow.php" class="mobile-nav-link">Home</a>
            <a href="products.php" class="mobile-nav-link">Products</a>
            <a href="#" class="mobile-nav-link">Collections</a>
            <a href="about.php" class="mobile-nav-link">A-propos</a>
        </div>
    </nav>
    <section class="notre-histoire-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Notre Histoire</h2>
                <p class="section-description">
                    Depuis sa création en 1985, Élégance Naturelle s'est imposée comme une référence dans l'univers de la beauté naturelle. Notre passion pour les ingrédients authentiques et notre savoir-faire artisanal nous permettent de créer des produits d'exception qui révèlent votre beauté naturelle.
                </p>
            </div>

            <div class="content-wrapper">
                <div class="values-section">
                    <h3>Nos Valeurs</h3>
                    <ul class="values-list">
                        <li>Authenticité et transparence dans tous nos processus de fabrication</li>
                        <li>Respect de l'environnement et développement durable</li>
                        <li>Innovation constante pour des formules toujours plus efficaces</li>
                        <li>Engagement envers la beauté naturelle et le bien-être de nos clients</li>
                    </ul>
                </div>

                <div class="image-container">
                    <img src  class="product-image">
                </div>
            </div>

            <div class="quote-section">
                <p class="quote-text">
                    "Chaque formule que nous créons raconte une histoire, celle de votre beauté et de votre bien-être."
                </p>
            </div>

            <div class="cta-section">
                <button class="cta-button">Découvrir</button>
            </div>
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
