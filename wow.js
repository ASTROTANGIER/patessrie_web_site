// Mobile Menu Functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    // Toggle mobile menu
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenuBtn.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });
        
        // Close mobile menu when clicking on a link
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenuBtn.classList.remove('active');
                mobileMenu.classList.remove('active');
            });
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideNav = mobileMenuBtn.contains(event.target) || mobileMenu.contains(event.target);
            
            if (!isClickInsideNav && mobileMenu.classList.contains('active')) {
                mobileMenuBtn.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                mobileMenuBtn.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        });
    }

    // Background slider functionality
    const backgroundSlider = document.querySelector('.background-slider');
    if (backgroundSlider) {
        // Create slides dynamically
        const slides = [
            'imag/background-slide1.jpg',
            'imag/background-slide2.jpg',
            'imag/background-slide3.jpg'
        ];
        
        // Create and append slides
        slides.forEach((slide, index) => {
            const slideElement = document.createElement('div');
            slideElement.className = 'slide';
            slideElement.style.backgroundImage = `url(${slide})`;
            if (index === 0) slideElement.classList.add('active');
            backgroundSlider.appendChild(slideElement);
        });
        
        // Slider functionality
        const slideElements = document.querySelectorAll('.slide');
        let currentSlide = 0;
        
        function nextSlide() {
            slideElements[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slideElements.length;
            slideElements[currentSlide].classList.add('active');
        }
        
        // Start slider with interval
        let sliderInterval = setInterval(nextSlide, 5000);
        
        // Pause slider on hover
        if (backgroundSlider) {
            backgroundSlider.addEventListener('mouseenter', () => {
                clearInterval(sliderInterval);
            });
            
            backgroundSlider.addEventListener('mouseleave', () => {
                sliderInterval = setInterval(nextSlide, 5000);
            });
        }
    }
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});

// Active page highlighting
function highlightActivePage() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
    
    navLinks.forEach(link => {
        const linkPage = link.getAttribute('href');
        if (linkPage === currentPage || (currentPage === '' && linkPage === 'index.html')) {
            link.style.color = '#d97706';
            link.style.fontWeight = '600';
        }
    });
}

// Call the function when page loads
document.addEventListener('DOMContentLoaded', function() {
    highlightActivePage();
});

// Newsletter subscription handler
function handleSubscribe(event) {
    event.preventDefault();
    const emailInput = event.target.querySelector('.email-input');
    
    if (emailInput) {
        const email = emailInput.value;
        
        // Basic email validation
        if (email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            // Here you can add your newsletter subscription logic
            alert(`Thank you for subscribing with email: ${email}`);
            
            // Reset the form
            event.target.reset();
        } else {
            alert('Please enter a valid email address');
        }
    }
}

// Collection navigation
function goToProducts(category) {
    if (category) {
        window.location.href = `products.html?category=${category}`;
    }
}

// Products Page JavaScript

// Global variables
let categories = [];
let currentCategory = null;

// Category icons mapping
const categoryIcons = {
    'Halwa de Louze': '🎂',
    'Halwa Biyane': '🥐',
    'Sablie': '🍪',
    'Breads': '🍞',
    'croissant': '🍰',
};

// DOM elements
const loadingElement = document.getElementById('loading');
const categoriesGrid = document.getElementById('categoriesGrid');
const errorState = document.getElementById('errorState');
const productsSection = document.getElementById('productsSection');
const backToCategories = document.getElementById('backToCategories');
const categoryTitle = document.getElementById('categoryTitle');
const categoryDescription = document.getElementById('categoryDescription');
const productsGrid = document.getElementById('productsGrid');
const productsLoading = document.getElementById('productsLoading');

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    if (backToCategories) {
        backToCategories.addEventListener('click', showCategories);
    }
}

// Load categories from API
async function loadCategories() {
    try {
        showLoading();
        
        const response = await fetch('api_categories.php');
        const data = await response.json();
        
        if (data.success) {
            categories = data.data;
            displayCategories();
        } else {
            showError();
            console.error('API Error:', data.error);
        }
    } catch (error) {
        showError();
        console.error('Fetch Error:', error);
    }
}

// Display categories
function displayCategories() {
    hideLoading();
    hideError();
    
    if (categories.length === 0) {
        categoriesGrid.innerHTML = '<p class="no-categories">No categories available.</p>';
        categoriesGrid.style.display = 'block';
        return;
    }
    
    const categoriesHTML = categories.map(category => {
        const icon = categoryIcons[category.NAME] || categoryIcons.default;
        const productText = category.product_count === 1 ? 'product' : 'products';
        
        return `
            <div class="category-card" onclick="selectCategory(${category.ID_CATEGORIE})">
                <div class="category-icon">${icon}</div>
                <h3 class="category-name">${escapeHtml(category.NAME)}</h3>
                <p class="category-description">${escapeHtml(category.DESCRIPTION || '')}</p>
                <p class="category-count">${category.product_count} ${productText}</p>
                <button class="view-products-btn" onclick="event.stopPropagation(); selectCategory(${category.ID_CATEGORIE})">
                    View All Products
                </button>
            </div>
        `;
    }).join('');
    
    categoriesGrid.innerHTML = categoriesHTML;
    categoriesGrid.style.display = 'grid';
}

// Select a category and load its products
async function selectCategory(categoryId) {
    try {
        currentCategory = categories.find(cat => cat.ID_CATEGORIE == categoryId);
        if (!currentCategory) {
            console.error('Category not found');
            return;
        }
        
        showProductsSection();
        showProductsLoading();
        
        const response = await fetch(`api_products.php?category_id=${categoryId}`);
        const data = await response.json();
        
        if (data.success) {
            displayProducts(data.data);
        } else {
            hideProductsLoading();
            productsGrid.innerHTML = '<p class="error-message">Failed to load products. Please try again.</p>';
            console.error('API Error:', data.error);
        }
    } catch (error) {
        hideProductsLoading();
        productsGrid.innerHTML = '<p class="error-message">Failed to load products. Please try again.</p>';
        console.error('Fetch Error:', error);
    }
}

// Display products
function displayProducts(data) {
    hideProductsLoading();
    
    const { category, products } = data;
    
    // Update category info
    categoryTitle.textContent = category.NAME;
    categoryDescription.textContent = category.DESCRIPTION || '';
    
    if (products.length === 0) {
        productsGrid.innerHTML = '<p class="no-products">Aucun nouveau produit trouvé.</p>';
        return;
    }
    
    const productsHTML = products.map(produit => {
        return `
            <div class="produit">
                <img src="${escapeHtml(produit.url_image)}" alt="${escapeHtml(produit.nom_produit)}" class="img-produit">
                <h3 class="nom-produit">${escapeHtml(produit.nom_produit)}</h3>
                <div class="prix-btn">
                    <p>${escapeHtml(produit.prix)} dh</p>
                    <a href="detaill.php?id=${escapeHtml(produit.produit_id)}" class="btn-detail">Voir</a>
                </div>
            </div>
        `;
    }).join('');
    
    productsGrid.innerHTML = productsHTML;
}

// Show categories section
function showCategories() {
    document.querySelector('.categories-section').style.display = 'block';
    productsSection.style.display = 'none';
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Show products section
function showProductsSection() {
    document.querySelector('.categories-section').style.display = 'none';
    productsSection.style.display = 'block';
    
    // Scroll to products section
    productsSection.scrollIntoView({ behavior: 'smooth' });
}

// Loading states
function showLoading() {
    loadingElement.style.display = 'block';
    categoriesGrid.style.display = 'none';
    errorState.style.display = 'none';
}

function hideLoading() {
    loadingElement.style.display = 'none';
}

function showError() {
    loadingElement.style.display = 'none';
    categoriesGrid.style.display = 'none';
    errorState.style.display = 'block';
}

function hideError() {
    errorState.style.display = 'none';
}

function showProductsLoading() {
    productsLoading.style.display = 'block';
    productsGrid.style.display = 'none';
}

function hideProductsLoading() {
    productsLoading.style.display = 'none';
    productsGrid.style.display = 'grid';
}

// Add to cart functionality (placeholder)
function addToCart(productId) {
    // This is a placeholder function
    // In a real application, you would implement cart functionality
    alert(`Product ${productId} added to cart! (This is a demo)`);
}

// Utility function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Newsletter subscription (from original wow.js)
function handleSubscribe(event) {
    event.preventDefault();
    const email = event.target.querySelector('.email-input').value;
    alert(`Thank you for subscribing with email: ${email}`);
    event.target.reset();
}

