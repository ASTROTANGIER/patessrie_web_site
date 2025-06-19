<?php
/**
 * Script pour supprimer un article du panier
 * Pâtisserie Haloui
 */

// Démarrage de session sécurisée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si la requête est de type POST et contient des données JSON
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($input['id'])) {
    $productId = $input['id'];
    
    // Vérifier si le panier existe dans la session
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Rechercher l'article dans le panier
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $productId) {
                // Supprimer l'article du panier
                unset($_SESSION['cart'][$key]);
                // Réindexer le tableau
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                break;
            }
        }
    }
    
    // Retourner une réponse JSON
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Article supprimé du panier',
        'cartCount' => count($_SESSION['cart'])
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
