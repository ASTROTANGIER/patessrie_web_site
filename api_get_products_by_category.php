
<?php
// Filename: api_get_products_by_category.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'db_pastrie.php';

// Get category_id from GET parameter
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

if ($category_id <= 0) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Invalid category ID"]);
    exit;
}

try {
    // First, get category information
    $stmt = $pdo->prepare("SELECT ID_CATEGORIE, NAME, DESCRIPTION FROM categories WHERE ID_CATEGORIE = ?");
    $stmt->execute([$category_id]);
    $category = $stmt->fetch();
    
    if (!$category) {
        http_response_code(404);
        echo json_encode(["success" => false, "error" => "Category not found"]);
        exit;
    }
    
    // Then, get products for this category
    // API pour récupérer les produits par catégorie
    $stmt = $pdo->prepare("
        SELECT 
            ID_PRODUIT, NAME, DESCRIPTION, PRIX, IMAGE_URL
        FROM products 
        WHERE ID_CATEGORIE = ?
        ORDER BY NAME
    ");
    $stmt->execute([$category_id]);
    $products = $stmt->fetchAll();
    
    echo json_encode([
        "success" => true, 
        "data" => [
            "category" => $category,
            "products" => $products
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>

