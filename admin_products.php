<?php
// Démarrage de session sécurisé
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification de l'authentification admin (commenté pour le développement)
/*if (!isset($_SESSION['admin_id'])) {
    header('Location: signin.php');
    exit;
}*/

// Inclusion des fichiers nécessaires
try {
    require_once 'includes/db_connection.php';
} catch (Exception $e) {
    // Si le fichier n'existe pas, créer la connexion directement
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=patessrie;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}

// Initialisation des variables
$message = '';
$messageType = '';
$editProduct = null;
$stockExists = false;
$products = [];
$categories = [];

// Vérifier si la table products existe
try {
    $checkTable = $pdo->query("SHOW TABLES LIKE 'products'");
    if ($checkTable->rowCount() == 0) {
        // La table n'existe pas, afficher un message
        $message = "La table 'products' n'existe pas. Veuillez exécuter le script d'installation.";
        $messageType = "warning";
    } else {
        // Vérifier si la colonne stock existe
        $checkStockColumn = $pdo->query("SHOW COLUMNS FROM products LIKE 'stock'");
        $stockExists = $checkStockColumn->rowCount() > 0;
        
        // Si la colonne stock n'existe pas, proposer de l'ajouter
        if (!$stockExists) {
            $message = "La colonne 'stock' n'existe pas dans la table 'products'. <a href='add_stock_column.php' class='btn btn-sm btn-primary'>Ajouter la colonne stock</a>";
            $messageType = "info";
        }
    }
} catch (PDOException $e) {
    $message = "Erreur lors de la vérification de la structure de la table : " . $e->getMessage();
    $messageType = "danger";
}

// Traitement des actions CRUD
// 1. Suppression d'un produit
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $productId = $_GET['delete'];
        
        // Vérifier si le produit existe
        $checkProduct = $pdo->prepare("SELECT NAME FROM products WHERE ID_PRODUIT = ?");
        $checkProduct->execute([$productId]);
        $product = $checkProduct->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            // Supprimer le produit
            $stmt = $pdo->prepare("DELETE FROM products WHERE ID_PRODUIT = ?");
            $stmt->execute([$productId]);
            
            $message = "Le produit \"" . htmlspecialchars($product['NAME']) . "\" a été supprimé avec succès.";
            $messageType = "success";
        } else {
            $message = "Produit non trouvé.";
            $messageType = "warning";
        }
    } catch (PDOException $e) {
        $message = "Erreur lors de la suppression : " . $e->getMessage();
        $messageType = "danger";
    }
}

// 2. Ajout d'un nouveau produit
if (isset($_POST['add_product'])) {
    try {
        // Validation des données
        $categoryId = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $imageUrl = trim($_POST['image_url'] ?? '');
        $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
        
        // Vérification des données obligatoires
        if (empty($name) || $price === false || $categoryId === false) {
            throw new Exception("Veuillez remplir tous les champs obligatoires.");
        }
        
        // Préparer la requête SQL en fonction de l'existence de la colonne stock
        if ($stockExists) {
            $stmt = $pdo->prepare("
                INSERT INTO products (ID_CATEGORIE, NAME, DESCRIPTION, PRIX, IMAGE_URL, stock) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$categoryId, $name, $description, $price, $imageUrl, $stock ?? 0]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO products (ID_CATEGORIE, NAME, DESCRIPTION, PRIX, IMAGE_URL) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$categoryId, $name, $description, $price, $imageUrl]);
        }
        
        $message = "Produit ajouté avec succès.";
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Erreur lors de l'ajout : " . $e->getMessage();
        $messageType = "danger";
    }
}

// 3. Mise à jour d'un produit existant
if (isset($_POST['update_product'])) {
    try {
        // Validation des données
        $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $categoryId = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $imageUrl = trim($_POST['image_url'] ?? '');
        $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
        
        // Vérification des données obligatoires
        if (empty($name) || $price === false || $categoryId === false || $productId === false) {
            throw new Exception("Veuillez remplir tous les champs obligatoires.");
        }
        
        // Préparer la requête SQL en fonction de l'existence de la colonne stock
        if ($stockExists) {
            $stmt = $pdo->prepare("
                UPDATE products 
                SET ID_CATEGORIE = ?, NAME = ?, DESCRIPTION = ?, PRIX = ?, IMAGE_URL = ?, stock = ?
                WHERE ID_PRODUIT = ?
            ");
            $stmt->execute([$categoryId, $name, $description, $price, $imageUrl, $stock ?? 0, $productId]);
        } else {
            $stmt = $pdo->prepare("
                UPDATE products 
                SET ID_CATEGORIE = ?, NAME = ?, DESCRIPTION = ?, PRIX = ?, IMAGE_URL = ?
                WHERE ID_PRODUIT = ?
            ");
            $stmt->execute([$categoryId, $name, $description, $price, $imageUrl, $productId]);
        }
        
        $message = "Produit mis à jour avec succès.";
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Erreur lors de la mise à jour : " . $e->getMessage();
        $messageType = "danger";
    }
}

// Récupération des produits
try {
    // Vérifier si la table existe avant de récupérer les produits
    $checkTable = $pdo->query("SHOW TABLES LIKE 'products'");
    if ($checkTable->rowCount() > 0) {
        // Construire la requête SQL en fonction de l'existence de la colonne stock
        $sql = "
            SELECT p.ID_PRODUIT, p.NAME, p.DESCRIPTION, p.PRIX, p.IMAGE_URL, 
                c.NAME as CATEGORY_NAME, c.ID_CATEGORIE";
        
        if ($stockExists) {
            $sql .= ", p.stock";
        }
        
        $sql .= " FROM products p
                LEFT JOIN categories c ON p.ID_CATEGORIE = c.ID_CATEGORIE
                ORDER BY p.NAME";
        
        $stmt = $pdo->query($sql);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $message = "Erreur lors de la récupération des produits : " . $e->getMessage();
    $messageType = "danger";
    $products = [];
}

// Récupération des catégories pour les formulaires
try {
    // Vérifier si la table existe avant de récupérer les catégories
    $checkTable = $pdo->query("SHOW TABLES LIKE 'categories'");
    if ($checkTable->rowCount() > 0) {
        $stmt = $pdo->query("SELECT ID_CATEGORIE, NAME FROM categories ORDER BY NAME");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $message = "La table 'categories' n'existe pas. Veuillez exécuter le script d'installation.";
        $messageType = "warning";
    }
} catch (PDOException $e) {
    $categories = [];
}

// Récupération d'un produit spécifique pour l'édition
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    try {
        // Construire la requête SQL en fonction de l'existence de la colonne stock
        $sql = "
            SELECT ID_PRODUIT, ID_CATEGORIE, NAME, DESCRIPTION, PRIX, IMAGE_URL";
        
        if ($stockExists) {
            $sql .= ", stock";
        }
        
        $sql .= " FROM products WHERE ID_PRODUIT = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_GET['edit']]);
        $editProduct = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$editProduct) {
            $message = "Produit non trouvé.";
            $messageType = "warning";
        }
    } catch (PDOException $e) {
        $message = "Erreur lors de la récupération du produit : " . $e->getMessage();
        $messageType = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits - Administration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        /* Styles pour les modals */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            width: 90%;
            max-width: 700px;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .modal-title {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .close {
            font-size: 1.5rem;
            font-weight: 700;
            color: #6c757d;
            cursor: pointer;
        }
        
        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .form-group {
            flex: 1;
            margin-bottom: 1rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
        
        .required {
            color: #dc3545;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }
        
        /* Styles pour les badges et images */
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .product-image-placeholder {
            width: 60px;
            height: 60px;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            color: #ccc;
            font-size: 1.5rem;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 20px;
        }
        
        .badge-primary {
            background-color: #4e73df;
            color: white;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar inclus depuis un fichier séparé -->
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <div class="main-content">
            <div class="header">
                <h1>Gestion des Produits</h1>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Ajouter un produit
                </button>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                <th>Prix</th>
                                <?php if ($stockExists): ?>
                                    <th>Stock</th>
                                <?php endif; ?>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="<?php echo $stockExists ? '8' : '7'; ?>" class="text-center">
                                        <div class="empty-state">
                                            <i class="fas fa-birthday-cake"></i>
                                            <p>Aucun produit trouvé</p>
                                            <button class="btn btn-primary btn-sm" onclick="openAddModal()">
                                                Ajouter un produit
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['ID_PRODUIT']; ?></td>
                                        <td>
                                            <?php if (!empty($product['IMAGE_URL'])): ?>
                                                <img src="<?php echo htmlspecialchars($product['IMAGE_URL']); ?>" alt="<?php echo htmlspecialchars($product['NAME']); ?>" class="product-image">
                                            <?php else: ?>
                                                <div class="product-image-placeholder">
                                                    <i class="fas fa-birthday-cake"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($product['NAME']); ?></td>
                                        <td>
                                            <span class="badge badge-primary">
                                                <?php echo htmlspecialchars($product['CATEGORY_NAME'] ?? 'Non catégorisé'); ?>
                                            </span>
                                        </td>
                                        <td><?php echo number_format($product['PRIX'], 2); ?> DH</td>
                                        <?php if ($stockExists): ?>
                                            <td>
                                                <?php if (isset($product['stock']) && $product['stock'] <= 5): ?>
                                                    <span class="badge badge-danger"><?php echo $product['stock']; ?></span>
                                                <?php else: ?>
                                                    <?php echo $product['stock'] ?? 'N/A'; ?>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                        <td>
                                            <?php 
                                                $description = $product['DESCRIPTION'] ?? '';
                                                echo htmlspecialchars(mb_substr($description, 0, 50)) . (mb_strlen($description) > 50 ? '...' : '');
                                            ?>
                                        </td>
                                        <td class="actions">
                                            <a href="?edit=<?php echo $product['ID_PRODUIT']; ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $product['ID_PRODUIT']; ?>, '<?php echo addslashes($product['NAME']); ?>')" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal pour ajouter un produit -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Ajouter un produit</h2>
                <span class="close" onclick="closeAddModal()">&times;</span>
            </div>
            <form action="" method="post" class="product-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nom du produit <span class="required">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Catégorie <span class="required">*</span></label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">Sélectionner une catégorie</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['ID_CATEGORIE']; ?>">
                                    <?php echo htmlspecialchars($category['NAME']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Prix (DH) <span class="required">*</span></label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required>
                    </div>
                    <?php if ($stockExists): ?>
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" id="stock" name="stock" class="form-control" min="0" value="0">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="image_url">URL de l'image</label>
                    <input type="text" id="image_url" name="image_url" class="form-control">
                    <small class="form-text">Laissez vide pour utiliser une image par défaut</small>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Annuler</button>
                    <button type="submit" name="add_product" class="btn btn-success">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal pour éditer un produit -->
    <?php if ($editProduct): ?>
        <div id="editModal" class="modal" style="display: block;">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Modifier le produit</h2>
                    <span class="close" onclick="closeEditModal()">&times;</span>
                </div>
                <form action="" method="post" class="product-form">
                    <input type="hidden" name="product_id" value="<?php echo $editProduct['ID_PRODUIT']; ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_name">Nom du produit <span class="required">*</span></label>
                            <input type="text" id="edit_name" name="name" class="form-control" value="<?php echo htmlspecialchars($editProduct['NAME']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_category_id">Catégorie <span class="required">*</span></label>
                            <select id="edit_category_id" name="category_id" class="form-control" required>
                                <option value="">Sélectionner une catégorie</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['ID_CATEGORIE']; ?>" <?php echo ($category['ID_CATEGORIE'] == $editProduct['ID_CATEGORIE']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['NAME']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_price">Prix (DH) <span class="required">*</span></label>
                            <input type="number" id="edit_price" name="price" class="form-control" step="0.01" min="0" value="<?php echo $editProduct['PRIX']; ?>" required>
                        </div>
                        <?php if ($stockExists): ?>
                            <div class="form-group">
                                <label for="edit_stock">Stock</label>
                                <input type="number" id="edit_stock" name="stock" class="form-control" min="0" value="<?php echo $editProduct['stock'] ?? 0; ?>">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="edit_image_url">URL de l'image</label>
                        <input type="text" id="edit_image_url" name="image_url" class="form-control" value="<?php echo htmlspecialchars($editProduct['IMAGE_URL'] ?? ''); ?>">
                        <small class="form-text">Laissez vide pour utiliser une image par défaut</small>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea id="edit_description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($editProduct['DESCRIPTION'] ?? ''); ?></textarea>
                    </div>
                    <div class="modal-footer">
                        <a href="admin_products.php" class="btn btn-secondary">Annuler</a>
                        <button type="submit" name="update_product" class="btn btn-success">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
    </div>

    <script>
        // Fonctions pour gérer les modals
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }
        
        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }
        
        function closeEditModal() {
            window.location.href = 'admin_products.php';
        }
        
        // Confirmation de suppression
        function confirmDelete(productId, productName) {
            if (confirm(`Êtes-vous sûr de vouloir supprimer le produit "${productName}" ?`)) {
                window.location.href = `admin_products.php?delete=${productId}`;
            }
        }
        
        // Fermer les modals si on clique en dehors
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>





