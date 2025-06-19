<?php
// Simple test file to verify products are working
echo "<h1>Test des Produits - Patisserie Haloui</h1>";

require_once 'db_pastrie.php';

try {
    echo "<h2>Test de connexion à la base de données</h2>";
    echo "<p style='color: green;'>✓ Connexion réussie</p>";
    
    // Test categories
    echo "<h2>Catégories disponibles:</h2>";
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY NAME");
    $categories = $stmt->fetchAll();
    
    if (empty($categories)) {
        echo "<p style='color: red;'>Aucune catégorie trouvée. Exécutez setup_database.php d'abord.</p>";
    } else {
        echo "<ul>";
        foreach ($categories as $cat) {
            echo "<li><strong>" . htmlspecialchars($cat['NAME']) . "</strong> - " . htmlspecialchars($cat['DESCRIPTION']) . "</li>";
        }
        echo "</ul>";
    }
    
    // Test products
    echo "<h2>Produits disponibles:</h2>";
    $stmt = $pdo->query("
        SELECT p.*, c.NAME as category_name 
        FROM products p 
        JOIN categories c ON p.ID_CATEGORIE = c.ID_CATEGORIE 
        ORDER BY c.NAME, p.NAME
    ");
    $products = $stmt->fetchAll();
    
    if (empty($products)) {
        echo "<p style='color: red;'>Aucun produit trouvé. Exécutez setup_database.php d'abord.</p>";
    } else {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Prix</th><th>Description</th><th>Image</th></tr>";
        foreach ($products as $prod) {
            echo "<tr>";
            echo "<td>" . $prod['ID_PRODUIT'] . "</td>";
            echo "<td><strong>" . htmlspecialchars($prod['NAME']) . "</strong></td>";
            echo "<td>" . htmlspecialchars($prod['category_name']) . "</td>";
            echo "<td>" . number_format($prod['PRIX'], 2) . " DH</td>";
            echo "<td>" . htmlspecialchars($prod['DESCRIPTION']) . "</td>";
            echo "<td>";
            if ($prod['IMAGE_URL']) {
                echo "<img src='" . htmlspecialchars($prod['IMAGE_URL']) . "' alt='Product' style='width: 50px; height: 50px; object-fit: cover;' onerror='this.style.display=\"none\"'>";
            } else {
                echo "Pas d'image";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<hr>";
    echo "<h2>Tests des APIs:</h2>";
    echo "<ul>";
    echo "<li><a href='api_get_all_categories.php' target='_blank'>API Catégories</a></li>";
    echo "<li><a href='api_get_all_products.php' target='_blank'>API Tous les Produits</a></li>";
    echo "<li><a href='api_get_products_by_category.php?category_id=1' target='_blank'>API Produits par Catégorie (ID=1)</a></li>";
    echo "</ul>";
    
    echo "<hr>";
    echo "<h2>Pages de test:</h2>";
    echo "<ul>";
    echo "<li><a href='products.php' target='_blank'>Page Produits (JavaScript/AJAX)</a></li>";
    echo "<li><a href='products_server_side.php' target='_blank'>Page Produits (Server-side PHP)</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Erreur: " . $e->getMessage() . "</p>";
    echo "<p>Assurez-vous que:</p>";
    echo "<ul>";
    echo "<li>MySQL est démarré</li>";
    echo "<li>La base de données 'pastrie' existe</li>";
    echo "<li>Les tables sont créées (exécutez setup_database.php)</li>";
    echo "</ul>";
}
?>
