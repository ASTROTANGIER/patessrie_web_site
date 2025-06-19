<?php
// Filename: create_orders_tables.php
// Script pour créer les tables nécessaires pour les commandes

require_once 'db_pastrie.php';

try {
    // Créer la table des commandes
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            order_id INT AUTO_INCREMENT PRIMARY KEY,
            client_name VARCHAR(255) NOT NULL,
            client_phone VARCHAR(50) NOT NULL,
            shipping_address TEXT NOT NULL,
            city VARCHAR(100) NOT NULL,
            zip_code VARCHAR(20) NOT NULL,
            total_amount DECIMAL(10, 2) NOT NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'en_attente',
            created_at DATETIME NOT NULL,
            updated_at DATETIME NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    
    // Créer la table des éléments de commande
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS order_items (
            item_id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            product_name VARCHAR(255) NOT NULL,
            quantity INT NOT NULL,
            unit_price DECIMAL(10, 2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    
    echo "Tables de commandes créées avec succès!";
} catch (PDOException $e) {
    echo "Erreur lors de la création des tables: " . $e->getMessage();
}
?>