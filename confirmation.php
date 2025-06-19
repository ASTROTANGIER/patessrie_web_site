<?php
/**
 * Page de confirmation de commande - Pâtisserie Haloui
 */

require_once 'config.php';
require_once 'db_pastrie.php';

// Démarrage de session sécurisée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupérer l'ID de commande depuis l'URL ou la session
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : (isset($_SESSION['last_order_id']) ? $_SESSION['last_order_id'] : 0);

// Si aucun ID de commande n'est trouvé, rediriger vers la page d'accueil
if ($order_id <= 0) {
    header('Location: index.php');
    exit;
}

// Récupérer les détails de la commande
try {
    // Récupérer les informations de la commande
    $stmt = $pdo->prepare("
        SELECT * FROM orders WHERE order_id = ?
    ");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Si la commande n'existe pas, rediriger
    if (!$order) {
        header('Location: index.php');
        exit;
    }
    
    // Récupérer les articles de la commande
    $stmt = $pdo->prepare("
        SELECT * FROM order_items WHERE order_id = ?
    ");
    $stmt->execute([$order_id]);
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des détails de la commande : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/confirmation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Styles de base */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        
        .confirmation-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .confirmation-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo {
            margin-bottom: 1rem;
        }
        
        .logo a {
            text-decoration: none;
            color: #333;
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .confirmation-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            color: #4a4a4a;
        }
        
        .confirmation-subtitle {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
        }
        
        .confirmation-icon {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .confirmation-icon i {
            font-size: 5rem;
            color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
            border-radius: 50%;
            padding: 1.5rem;
        }
        
        .order-details {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .order-details h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: #4a4a4a;
            border-bottom: 1px solid #eee;
            padding-bottom: 0.5rem;
        }
        
        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .order-info-section h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #555;
        }
        
        .info-item {
            margin-bottom: 0.5rem;
        }
        
        .info-label {
            font-weight: 600;
            color: #666;
        }
        
        .info-value {
            color: #333;
        }
        
        .order-items {
            margin-top: 2rem;
        }
        
        .order-items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .order-items-table th {
            text-align: left;
            padding: 1rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
            color: #555;
        }
        
        .order-items-table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .order-items-table tr:last-child td {
            border-bottom: none;
        }
        
        .item-name {
            font-weight: 500;
        }
        
        .item-price, .item-total {
            text-align: right;
        }
        
        .order-summary {
            margin-top: 2rem;
            border-top: 1px solid #eee;
            padding-top: 1rem;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .summary-row.total {
            font-weight: 700;
            font-size: 1.2rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        
        .actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 1rem;
        }
        
        .btn-primary {
            background-color: #6c5ce7;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #5649c0;
        }
        
        .btn-outline {
            background-color: transparent;
            color: #6c5ce7;
            border: 1px solid #6c5ce7;
        }
        
        .btn-outline:hover {
            background-color: #6c5ce7;
            color: white;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border
</
</augment_code_snippet>

