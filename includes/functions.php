<?php
/**
 * Fonctions utilitaires
 * Pâtisserie Haloui - Administration
 */

/**
 * Vérifie si l'utilisateur est un administrateur
 * 
 * @return bool True si l'utilisateur est admin, false sinon
 */
function isAdmin() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Génère une alerte HTML formatée
 * 
 * @param string $message Le message à afficher
 * @param string $type Le type d'alerte (success, danger, warning, info)
 * @return string Le HTML de l'alerte
 */
function generateAlert($message, $type = 'info') {
    return '<div class="alert alert-' . $type . '">' . $message . '</div>';
}

/**
 * Tronque un texte à une longueur donnée
 * 
 * @param string $text Le texte à tronquer
 * @param int $length La longueur maximale
 * @param string $append Le texte à ajouter si tronqué
 * @return string Le texte tronqué
 */
function truncateText($text, $length = 50, $append = '...') {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    
    return htmlspecialchars(mb_substr($text, 0, $length)) . $append;
}

/**
 * Formate un prix avec le symbole de devise
 * 
 * @param float $price Le prix à formater
 * @param string $currency Le symbole de la devise
 * @return string Le prix formaté
 */
function formatPrice($price, $currency = 'DH') {
    return number_format($price, 2) . ' ' . $currency;
}

/**
 * Vérifie si une colonne existe dans une table
 * 
 * @param PDO $pdo L'objet PDO
 * @param string $table Le nom de la table
 * @param string $column Le nom de la colonne
 * @return bool True si la colonne existe, false sinon
 */
function columnExists($pdo, $table, $column) {
    $stmt = $pdo->query("SHOW COLUMNS FROM $table LIKE '$column'");
    return $stmt->rowCount() > 0;
}