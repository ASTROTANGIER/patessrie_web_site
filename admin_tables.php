<?php
// Démarrage de session sécurisé
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connexion à la base de données avec PDO
try {
    $pdo = new PDO('mysql:host=localhost;dbname=patessrie;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupération des produits
try {
    $stmt = $pdo->query("
        SELECT p.ID_PRODUIT, p.NAME, p.PRIX, p.DESCRIPTION, p.IMAGE_URL, c.NAME as CATEGORY_NAME
        FROM products p
        LEFT JOIN categories c ON p.ID_CATEGORIE = c.ID_CATEGORIE
        ORDER BY p.NAME
    ");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
    $productError = $e->getMessage();
}

// Récupération des catégories
try {
    $stmt = $pdo->query("
        SELECT c.ID_CATEGORIE, c.NAME, c.DESCRIPTION, 
               COUNT(p.ID_PRODUIT) as PRODUCT_COUNT
        FROM categories c
        LEFT JOIN products p ON c.ID_CATEGORIE = p.ID_CATEGORIE
        GROUP BY c.ID_CATEGORIE, c.NAME, c.DESCRIPTION
        ORDER BY c.NAME
    ");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
    $categoryError = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits et Catégories - Pâtisserie Haloui</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #c9a227;
            --secondary-color: #2d1810;
            --background-color: #FFFBF3;
            --card-bg: #ffffff;
            --text-color: #333;
            --light-text: #6b5b47;
            --border-color: #e5e7eb;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --border-radius: 10px;
            --box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }
        
        .nav-tabs {
            display: flex;
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 20px;
        }
        
        .nav-tab {
            padding: 10px 20px;
            cursor: pointer;
            font-weight: 500;
            color: var(--light-text);
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .nav-tab:hover {
            color: var(--primary-color);
        }
        
        .nav-tab.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }
        
        .tab-content {
            background-color: var(--card-bg);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
        }
        
        .tab-pane {
            display: none;
        }
        
        .tab-pane.active {
            display: block;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background-color: #f8f5f0;
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
        }
        
        .badge-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .badge-secondary {
            background-color: #f8f5f0;
            color: var(--secondary-color);
        }
        
        .action-btn {
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            margin-right: 5px;
        }
        
        .btn-edit {
            background-color: var(--info-color);
            color: white;
        }
        
        .btn-delete {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-add {
            background-color: var(--success-color);
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        .empty-message {
            text-align: center;
            padding: 20px;
            color: var(--light-text);
            font-style: italic;
        }
        
        .error-message {
            background-color: #fee2e2;
            color: var(--danger-color);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .action-btn {
                padding: 4px 8px;
                font-size: 0.7rem;
            }
            
            th, td {
                padding: 8px 10px;
            }
            
            .product-image {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gestion des Produits et Catégories</h1>
            <p>Consultez et gérez vos produits et catégories</p>
        </div>
        
        <div class="nav-tabs">
            <div class="nav-tab active" data-tab="products">
                <i class="fas fa-birthday-cake"></i> Produits
            </div>
            <div class="nav-tab" data-tab="categories">
                <i class="fas fa-tags"></i> Catégories
            </div>
        </div>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Products Tab -->
            <div class="tab-pane active" id="products">
                <a href="add_product.php" class="btn-add">
                    <i class="fas fa-plus"></i> Ajouter un produit
                </a>
                
                <?php if (isset($productError)): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> Erreur: <?php echo $productError; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($products)): ?>
                    <div class="empty-message">Aucun produit trouvé</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Prix</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($product['IMAGE_URL'])): ?>
                                                <img src="<?php echo htmlspecialchars($product['IMAGE_URL']); ?>" alt="<?php echo htmlspecialchars($product['NAME']); ?>" class="product-image">
                                            <?php else: ?>
                                                <div style="width: 60px; height: 60px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 5px;">
                                                    <i class="fas fa-birthday-cake" style="color: #ccc;"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($product['NAME']); ?></td>
                                        <td>
                                            <span class="badge badge-secondary">
                                                <?php echo htmlspecialchars($product['CATEGORY_NAME'] ?? 'Non catégorisé'); ?>
                                            </span>
                                        </td>
                                        <td><?php echo number_format($product['PRIX'], 2); ?> DH</td>
                                        <td>
                                            <?php 
                                                $description = $product['DESCRIPTION'] ?? '';
                                                echo htmlspecialchars(mb_substr($description, 0, 50)) . (mb_strlen($description) > 50 ? '...' : '');
                                            ?>
                                        </td>
                                        <td>
                                            <a href="edit_product.php?id=<?php echo $product['ID_PRODUIT']; ?>" class="action-btn btn-edit">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            <button class="action-btn btn-delete" 
                                                    onclick="confirmDelete('product', <?php echo $product['ID_PRODUIT']; ?>, '<?php echo addslashes($product['NAME']); ?>')">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Categories Tab -->
            <div class="tab-pane" id="categories">
                <a href="add_category.php" class="btn-add">
                    <i class="fas fa-plus"></i> Ajouter une catégorie
                </a>
                
                <?php if (isset($categoryError)): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> Erreur: <?php echo $categoryError; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($categories)): ?>
                    <div class="empty-message">Aucune catégorie trouvée</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Nombre de produits</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td>
                                            <span class="badge badge-primary">
                                                <?php echo htmlspecialchars($category['NAME']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                                $description = $category['DESCRIPTION'] ?? '';
                                                echo htmlspecialchars(mb_substr($description, 0, 100)) . (mb_strlen($description) > 100 ? '...' : '');
                                            ?>
                                        </td>
                                        <td><?php echo $category['PRODUCT_COUNT']; ?></td>
                                        <td>
                                            <a href="edit_category.php?id=<?php echo $category['ID_CATEGORIE']; ?>" class="action-btn btn-edit">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                            <button class="action-btn btn-delete" 
                                                    onclick="confirmDelete('category', <?php echo $category['ID_CATEGORIE']; ?>, '<?php echo addslashes($category['NAME']); ?>')">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour basculer entre les onglets
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.nav-tab');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Retirer la classe active de tous les onglets
                    tabs.forEach(t => t.classList.remove('active'));
                    
                    // Ajouter la classe active à l'onglet cliqué
                    this.classList.add('active');
                    
                    // Récupérer l'ID du contenu à afficher
                    const tabId = this.getAttribute('data-tab');
                    
                    // Masquer tous les contenus
                    document.querySelectorAll('.tab-pane').forEach(pane => {
                        pane.classList.remove('active');
                    });
                    
                    // Afficher le contenu correspondant
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
        
        // Fonction pour confirmer la suppression
        function confirmDelete(type, id, name) {
            const entityType = type === 'product' ? 'produit' : 'catégorie';
            
            if (confirm(`Êtes-vous sûr de vouloir supprimer ${entityType} "${name}" ?`)) {
                window.location.href = `delete_${type}.php?id=${id}`;
            }
        }
    </script>
</body>
</html>