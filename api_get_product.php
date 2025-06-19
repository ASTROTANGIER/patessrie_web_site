<?php
// Filename: api_get_product.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'db_pastrie.php';

// Get product_id from GET parameter
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Invalid product ID"]);
    exit;
}

try {
    // Get product information
    $stmt = $pdo->prepare("
        SELECT 
            p.ID_PRODUIT, 
            p.NAME, 
            p.DESCRIPTION, 
            p.PRIX, 
            p.IMAGE_URL,
            c.ID_CATEGORIE,
            c.NAME as CATEGORY_NAME
        FROM products p
        INNER JOIN categories c ON p.ID_CATEGORIE = c.ID_CATEGORIE
        WHERE p.ID_PRODUIT = ?
    ");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        http_response_code(404);
        echo json_encode(["success" => false, "error" => "Product not found"]);
        exit;
    }
    
    echo json_encode([
        "success" => true, 
        "data" => $product
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>