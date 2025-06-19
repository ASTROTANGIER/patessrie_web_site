<?php
// Script pour créer la table des catégories
require_once 'db_pastrie.php';

try {
    // Créer la table des catégories
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            ID_CATEGORIE INT AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(100) NOT NULL,
            DESCRIPTION TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    
    // Vérifier si la table est vide et ajouter des catégories par défaut
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        // Ajouter quelques catégories par défaut
        $defaultCategories = [
            ['Gâteaux', 'Tous nos gâteaux pour toutes les occasions'],
            ['Pâtisseries orientales', 'Pâtisseries traditionnelles marocaines'],
            ['Viennoiseries', 'Croissants, pains au chocolat et autres délices'],
            ['Biscuits', 'Biscuits et petits fours']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO categories (NAME, DESCRIPTION) VALUES (?, ?)");
        
        foreach ($defaultCategories as $category) {
            $stmt->execute($category);
        }
        
        echo "Table des catégories créée avec succès et catégories par défaut ajoutées!";
    } else {
        echo "Table des catégories déjà existante.";
    }
} catch (PDOException $e) {
    echo "Erreur lors de la création de la table des catégories: " . $e->getMessage();
}
?>