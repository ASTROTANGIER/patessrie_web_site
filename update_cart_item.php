<?php
/**
 * Mettre à jour la quantité d'un article du panier
 * Pâtisserie Haloui
 */

// Démarrage de session sécurisée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si la requête est en POST et contient des données JSON
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $input) {
    // Initialiser le panier s'il n'existe pas
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $productId = $input['id'] ?? 0;
    $change = $input['change'] ?? 0;
    
    // Mettre à jour la quantité
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productId) {
            // Calculer la nouvelle quantité
            $newQuantity = $item['quantity'] + $change;
            
            // Si la quantité est 0 ou moins, supprimer l'article
            if ($newQuantity <= 0) {
                unset($_SESSION['cart'][$key]);
            } else {
                $_SESSION['cart'][$key]['quantity'] = $newQuantity;
            }
            break;
        }
    }