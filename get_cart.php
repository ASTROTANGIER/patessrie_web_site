<?php
/**
 * Récupérer le contenu du panier
 * Pâtisserie Haloui
 */

// Démarrage de session sécurisée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialiser le panier s'il n'existe pas
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Calculer le nombre total d'articles dans le panier
$cartCount = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartCount += $item['quantity'];
}

// Retourner une réponse JSON
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'cartCount' => $cartCount,
    'cart' => $_SESSION['cart']
]);
exit;
