<?php
/**
 * Mise à jour du panier dans la session
 * Pâtisserie Haloui
 */

// Démarrage de session sécurisée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si la requête est de type POST et contient des données JSON
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($input['cart'])) {
    // Mettre à jour le panier dans la session
    $_SESSION['cart'] = $input['cart'];
    
    // Calculer le nombre total d'articles dans le panier
    $cartCount = 0;
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
    
    // Retourner une réponse JSON
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Panier mis à jour',
        'cartCount' => $cartCount,
        'cart' => $_SESSION['cart']
    ]);
    exit;
} else {
    // Retourner une erreur
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Requête invalide'
    ]);
    exit;
}