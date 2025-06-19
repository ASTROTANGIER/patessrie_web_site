<?php
// Démarrage de session sécurisé
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

// Vérifier si la table products existe
try {
    $checkTable = $pdo->query("SHOW TABLES LIKE 'products'");
    if ($checkTable->rowCount() == 0) {
        // La table n'existe pas
        $message = "La table 'products' n'existe pas. Veuillez exécuter le script d'installation.";
        $messageType = "warning";
    } else {
        // Vérifier si la colonne stock existe déjà
        $checkStockColumn = $pdo->query("SHOW COLUMNS FROM products LIKE 'stock'");
        if ($checkStockColumn->rowCount() > 0) {
            $message = "La colonne 'stock' existe déjà dans la table 'products'.";
            $messageType = "info";
        } else {
            // Ajouter la colonne stock
            $pdo->exec("ALTER TABLE products ADD COLUMN stock INT DEFAULT 0");
            
            // Mettre à jour tous les produits existants avec un stock par défaut
            $pdo->exec("UPDATE products SET stock = 10");
            
            $message = "La colonne 'stock' a été ajoutée avec succès à la table 'products'. Tous les produits ont reçu un stock par défaut de 10 unités.";
            $messageType = "success";
        }
    }
} catch (PDOException $e) {
    $message = "Erreur lors de l'ajout de la colonne 'stock' : " . $e->getMessage();
    $messageType = "danger";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout de la colonne Stock - Administration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar inclus depuis un fichier séparé -->
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <div class="main-content">
            <div class="header">
                <h1>Gestion de la structure de la base de données</h1>
                <a href="admin_products.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Retour aux produits
                </a>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <h2>Modification de la structure de la table 'products'</h2>
                    <p>Cette page permet d'ajouter la colonne 'stock' à la table 'products' pour permettre la gestion des stocks.</p>
                    
                    <div class="actions" style="margin-top: 20px;">
                        <a href="admin_products.php" class="btn btn-primary">Retour à la gestion des produits</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>