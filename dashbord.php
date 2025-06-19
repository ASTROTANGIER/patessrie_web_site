<?php
// Démarrage de session sécurisé
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connexion à la base de données avec PDO
try {
    require_once 'includes/db_connection.php';
} catch (Exception $e) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=patessrie;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

// Vérification du rôle utilisateur
$userRole = $_SESSION['user_role'] ?? 'viewer';

// Récupération des statistiques et données
try {
    // Statistiques de base
    $totalSales = $pdo->query("SELECT SUM(total_amount) as total_sales FROM orders WHERE status != 'annulee'")
                      ->fetch(PDO::FETCH_ASSOC)['total_sales'] ?? 0;

    $todayOrders = $pdo->query("SELECT COUNT(*) as today_orders FROM orders WHERE DATE(created_at) = CURDATE()")
                       ->fetch(PDO::FETCH_ASSOC)['today_orders'] ?? 0;

    $activeClients = $pdo->query("SELECT COUNT(DISTINCT client_name) as active_clients FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")
                         ->fetch(PDO::FETCH_ASSOC)['active_clients'] ?? 0;

    $lowStock = $pdo->query("SELECT COUNT(*) as low_stock FROM products WHERE stock <= 5 AND stock > 0")
                    ->fetch(PDO::FETCH_ASSOC)['low_stock'] ?? 0;

    // Commandes récentes
    $stmt = $pdo->query("
        SELECT o.order_id, o.client_name, o.total_amount, o.status, o.created_at,
            COUNT(oi.item_id) as items_count
        FROM orders o
        LEFT JOIN order_items oi ON o.order_id = oi.order_id
        GROUP BY o.order_id, o.client_name, o.total_amount, o.status, o.created_at
        ORDER BY o.created_at DESC
        LIMIT 5
    ");
    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Produits populaires
    $stmt = $pdo->query("
        SELECT p.NAME, p.IMAGE_URL, COUNT(oi.item_id) as order_count, SUM(oi.quantity) as total_quantity
        FROM products p
        JOIN order_items oi ON p.ID_PRODUIT = oi.product_id
        GROUP BY p.ID_PRODUIT, p.NAME, p.IMAGE_URL
        ORDER BY total_quantity DESC
        LIMIT 4
    ");
    $popularProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Données pour le graphique des ventes mensuelles
    $stmt = $pdo->query("
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month,
            SUM(total_amount) as monthly_sales
        FROM orders
        WHERE status != 'annulee'
        AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month ASC
    ");
    $monthlySales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formater les données pour Chart.js
    $months = [];
    $salesData = [];
    
    foreach ($monthlySales as $data) {
        $monthDate = date_create_from_format('Y-m', $data['month']);
        $months[] = date_format($monthDate, 'M');
        $salesData[] = $data['monthly_sales'];
    }
    
    // Si nous n'avons pas 6 mois de données, compléter avec des mois par défaut
    if (count($months) < 6) {
        $currentMonth = date('n');
        $defaultMonths = [];
        $defaultSales = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $monthNum = ($currentMonth - $i) > 0 ? ($currentMonth - $i) : (12 + ($currentMonth - $i));
            $defaultMonths[] = date('M', mktime(0, 0, 0, $monthNum, 1));
            $defaultSales[] = 0;
        }
        
        foreach ($months as $index => $month) {
            $pos = array_search($month, $defaultMonths);
            if ($pos !== false) {
                $defaultSales[$pos] = $salesData[$index];
            }
        }
        
        $months = $defaultMonths;
        $salesData = $defaultSales;
    }
    
    // Convertir en JSON pour JavaScript
    $monthsJson = json_encode($months);
    $salesDataJson = json_encode($salesData);

} catch (PDOException $e) {
    // En cas d'erreur, définir des valeurs par défaut
    $totalSales = 0;
    $todayOrders = 0;
    $activeClients = 0;
    $lowStock = 0;
    $recentOrders = [];
    $popularProducts = [];
    $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun"];
    $salesData = [0, 0, 0, 0, 0, 0];
    $monthsJson = json_encode($months);
    $salesDataJson = json_encode($salesData);
}

// Obtenir la date actuelle
$currentDate = date('d M Y');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pâtisserie Haloui</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/css/dashboard_new.css">
</head>
<body>
    <!-- Navbar horizontale -->
    <header class="navbar">
        <div class="navbar-brand">
            <span class="brand-name">Pâtisserie Haloui</span>
        </div>
        
        <div class="navbar-search">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Rechercher..." class="search-input">
            </div>
        </div>
        
        <div class="navbar-actions">
            <div class="date-display">
                <i class="far fa-calendar-alt"></i>
                <span><?php echo $currentDate; ?></span>
            </div>
            
            <button class="notification-btn">
                <i class="far fa-bell"></i>
                <span class="notification-badge">3</span>
            </button>
            
            <div class="user-profile">
                <img src="assets/images/user-avatar.jpg" alt="User" class="avatar">
                <div class="user-info">
                    <span class="user-name">Admin</span>
                    <span class="user-role"><?php echo ucfirst($userRole); ?></span>
                </div>
            </div>
        </div>
    </header>
    
    <div class="dashboard-container">
        <!-- Sidebar verticale -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item active">
                        <a href="dashbord.php" class="nav-link">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin_orders.php" class="nav-link">
                            <span>Commandes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin_products.php" class="nav-link">
                            <span>Produits</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin_categories.php" class="nav-link">
                            <span>Catégories</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin_clients.php" class="nav-link">
                            <span>Clients</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin_settings.php" class="nav-link">
                            <span>Paramètres</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>
        
        <!-- Contenu principal -->
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Tableau de Bord</h1>
                <p class="page-subtitle">Aperçu de votre activité</p>
            </div>
            
            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon primary">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-title">Total des ventes</h3>
                        <p class="stat-value"><?php echo number_format($totalSales, 2); ?> DH</p>
                        <p class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i> 12% depuis le mois dernier
                        </p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-title">Commandes aujourd'hui</h3>
                        <p class="stat-value"><?php echo $todayOrders; ?></p>
                        <p class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i> 5% depuis hier
                        </p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-title">Clients actifs</h3>
                        <p class="stat-value"><?php echo $activeClients; ?></p>
                        <p class="stat-trend positive">
                            <i class="fas fa-arrow-up"></i> 8% depuis le mois dernier
                        </p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-title">Stock faible</h3>
                        <p class="stat-value"><?php echo $lowStock; ?></p>
                        <p class="stat-trend negative">
                            <i class="fas fa-arrow-down"></i> Nécessite attention
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Graphique et Commandes récentes -->
            <div class="dashboard-grid">
                <div class="chart-card">
                    <div class="card-header">
                        <h2 class="card-title">Évolution des Ventes</h2>
                        <div class="card-actions">
                            <button class="btn-icon">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="250"></canvas>
                    </div>
                </div>
                
                <div class="orders-card">
                    <div class="card-header">
                        <h2 class="card-title">Commandes Récentes</h2>
                        <a href="admin_orders.php" class="view-all">Voir tout</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentOrders)): ?>
                            <p class="no-data">Aucune commande récente</p>
                        <?php else: ?>
                            <div class="recent-orders">
                                <?php foreach ($recentOrders as $order): ?>
                                    <div class="order-item">
                                        <div class="order-info">
                                            <h4 class="order-id">#<?php echo $order['order_id']; ?></h4>
                                            <p class="order-client"><?php echo htmlspecialchars($order['client_name']); ?></p>
                                        </div>
                                        <div class="order-details">
                                            <p class="order-amount"><?php echo number_format($order['total_amount'], 2); ?> DH</p>
                                            <?php 
                                            $statusClass = '';
                                            switch ($order['status']) {
                                                case 'en_attente':
                                                    $statusClass = 'status-pending';
                                                    $statusText = 'En attente';
                                                    break;
                                                case 'en_preparation':
                                                    $statusClass = 'status-processing';
                                                    $statusText = 'En préparation';
                                                    break;
                                                case 'en_livraison':
                                                    $statusClass = 'status-shipping';
                                                    $statusText = 'En livraison';
                                                    break;
                                                case 'livree':
                                                    $statusClass = 'status-delivered';
                                                    $statusText = 'Livrée';
                                                    break;
                                                case 'annulee':
                                                    $statusClass = 'status-cancelled';
                                                    $statusText = 'Annulée';
                                                    break;
                                                default:
                                                    $statusClass = '';
                                                    $statusText = ucfirst($order['status']);
                                            }
                                            ?>
                                            <span class="status-badge <?php echo $statusClass; ?>">
                                                <?php echo $statusText; ?>
                                            </span>
                                        </div>
                                        <div class="order-date">
                                            <p><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></p>
                                            <p class="time"><?php echo date('H:i', strtotime($order['created_at'])); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Produits populaires -->
            <div class="popular-products-section">
                <div class="section-header">
                    <h2 class="section-title">Produits Populaires</h2>
                    <a href="admin_products.php" class="view-all">Gérer les produits</a>
                </div>
                
                <div class="products-grid">
                    <?php if (empty($popularProducts)): ?>
                        <p class="no-data">Aucun produit vendu récemment</p>
                    <?php else: ?>
                        <?php foreach ($popularProducts as $product): ?>
                            <div class="product-card">
                                <div class="product-image">
                                    <?php if (!empty($product['IMAGE_URL'])): ?>
                                        <img src="<?php echo htmlspecialchars($product['IMAGE_URL']); ?>" alt="<?php echo htmlspecialchars($product['NAME']); ?>">
                                    <?php else: ?>
                                        <div class="no-image">
                                            <i class="fas fa-birthday-cake"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="product-details">
                                    <h3 class="product-name"><?php echo htmlspecialchars($product['NAME']); ?></h3>
                                    <div class="product-stats">
                                        <div class="stat">
                                            <i class="fas fa-shopping-cart"></i>
                                            <span><?php echo $product['order_count']; ?> commandes</span>
                                        </div>
                                        <div class="stat">
                                            <i class="fas fa-box"></i>
                                            <span><?php echo $product['total_quantity']; ?> unités</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique des ventes
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo $monthsJson; ?>,
                    datasets: [{
                        label: 'Ventes mensuelles (DH)',
                        data: <?php echo $salesDataJson; ?>,
                        backgroundColor: 'rgba(201, 162, 39, 0.2)',
                        borderColor: 'rgba(201, 162, 39, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: 'rgba(201, 162, 39, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#2d1810',
                            bodyColor: '#6b5b47',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + Number(context.raw).toLocaleString('fr-FR') + ' DH';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: "'Poppins', sans-serif"
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    family: "'Poppins', sans-serif"
                                },
                                callback: function(value) {
                                    return value.toLocaleString('fr-FR') + ' DH';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
