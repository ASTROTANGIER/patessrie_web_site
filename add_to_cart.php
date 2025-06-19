<?php
/**
 * Ajouter un produit au panier
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
    $quantity = $input['quantity'] ?? 1;
    
    // Vérifier si le produit existe déjà dans le panier
    $productExists = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productId) {
            // Incrémenter la quantité
            $_SESSION['cart'][$key]['quantity'] += $quantity;
            $productExists = true;
            break;
        }
    }
    
    // Si le produit n'existe pas dans le panier, récupérer ses informations et l'ajouter
    if (!$productExists) {
        require_once 'db_pastrie.php';
        
        try {
            $stmt = $pdo->prepare("
                SELECT ID_PRODUIT, NAME, PRIX, IMAGE_URL 
                FROM products 
                WHERE ID_PRODUIT = ?
            ");
            $stmt->execute([$productId]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product) {
                $_SESSION['cart'][] = [
                    'id' => $product['ID_PRODUIT'],
                    'name' => $product['NAME'],
                    'price' => $product['PRIX'],
                    'image' => $product['IMAGE_URL'],
                    'quantity' => $quantity
                ];
            } else {
                // Produit non trouvé
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Produit non trouvé'
                ]);
                exit;
            }
        } catch (Exception $e) {
            // Erreur de base de données
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Erreur de base de données: ' . $e->getMessage()
            ]);
            exit;
        }
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
        'message' => 'Produit ajouté au panier',
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


