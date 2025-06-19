<?php
/**
 * Page de commande - Pâtisserie Haloui
 * Permet aux clients de finaliser leur commande et paiement
 */

require_once 'config.php';
require_once 'db_pastrie.php';

// Démarrage de session sécurisée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupération du panier (à remplacer par une vraie logique de panier)
// Utiliser le panier de la session si disponible
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cartItems = $_SESSION['cart'];
} else {
    // Panier de démonstration si aucun panier n'existe dans la session
    $cartItems = [
        [
            'id' => 1,
            'name' => 'Gâteau au Chocolat',
            'price' => 35.00,
            'quantity' => 1,
            'image' => 'assets/images/products/gateau-chocolat.jpg'
        ],
        [
            'id' => 2,
            'name' => 'Éclair à la Vanille',
            'price' => 4.50,
            'quantity' => 2,
            'image' => 'assets/images/products/eclair-vanille.jpg'
        ],
        [
            'id' => 3,
            'name' => 'Macaron Framboise',
            'price' => 2.50,
            'quantity' => 6,
            'image' => 'assets/images/products/macaron-framboise.jpg'
        ]
    ];
    
    // Sauvegarder le panier de démonstration dans la session
    $_SESSION['cart'] = $cartItems;
}

// Calcul du total
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$shipping = 5.00;
$total = $subtotal + $shipping;

// Vérifier si les tables orders et order_items existent
try {
    // Vérifier si la table orders existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'orders'");
    $ordersTableExists = $stmt->rowCount() > 0;
    
    // Vérifier si la table order_items existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'order_items'");
    $orderItemsTableExists = $stmt->rowCount() > 0;
    
    // Si les tables n'existent pas, les créer
    if (!$ordersTableExists) {
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
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }
    
    if (!$orderItemsTableExists) {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS order_items (
                item_id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_name VARCHAR(255) NOT NULL,
                quantity INT NOT NULL,
                unit_price DECIMAL(10, 2) NOT NULL,
                FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }
} catch (PDOException $e) {
    $errorMessage = "Erreur lors de la vérification ou création des tables : " . $e->getMessage();
}

// Traitement du formulaire lors de la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
    $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';
    $zipCode = isset($_POST['zipCode']) ? trim($_POST['zipCode']) : '';
    
    // Validation simple des données
    $errors = [];
    
    if (empty($firstName)) $errors[] = "Le prénom est requis";
    if (empty($lastName)) $errors[] = "Le nom est requis";
    if (empty($phone)) $errors[] = "Le téléphone est requis";
    if (empty($address)) $errors[] = "L'adresse est requise";
    if (empty($city)) $errors[] = "La ville est requise";
    if (empty($zipCode)) $errors[] = "Le code postal est requis";
    
    // Si pas d'erreurs, enregistrer la commande
    if (empty($errors)) {
        try {
            // Début de la transaction
            $pdo->beginTransaction();
            
            // Insertion de la commande
            $stmt = $pdo->prepare("
                INSERT INTO orders (client_name, client_phone, shipping_address, city, zip_code, total_amount, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, 'en_attente', NOW())
            ");
            
            $clientName = $firstName . ' ' . $lastName;
            $stmt->execute([$clientName, $phone, $address, $city, $zipCode, $total]);
            
            // Récupération de l'ID de la commande
            $orderId = $pdo->lastInsertId();
            
            // Insertion des articles de la commande
            $stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_name, quantity, unit_price)
                VALUES (?, ?, ?, ?)
            ");
            
            foreach ($cartItems as $item) {
                $stmt->execute([
                    $orderId,
                    $item['name'],
                    $item['quantity'],
                    $item['price']
                ]);
            }
            
            // Validation de la transaction
            $pdo->commit();
            
            // Enregistrer l'ID de commande dans la session
            $_SESSION['last_order_id'] = $orderId;
            
            // Vider le panier
            $_SESSION['cart'] = [];
            
            // Rediriger vers la page de confirmation
            header("Location: confirmation.php?order_id=$orderId");
            exit;
            
        } catch (PDOException $e) {
            // Annulation de la transaction en cas d'erreur
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $errorMessage = "Une erreur est survenue lors de l'enregistrement de votre commande: " . $e->getMessage();
        }
    } else {
        $errorMessage = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser votre commande - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .error-message i {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <header class="checkout-header">
            <div class="logo">
                <a href="index.php">
                    <span>Pâtisserie Haloui</span>
                </a>
            </div>
            <div class="checkout-steps">
                <div class="step active">
                    <span class="step-number">1</span>
                    <span class="step-name">Panier</span>
                </div>
                <div class="step active">
                    <span class="step-number">2</span>
                    <span class="step-name">Paiement</span>
                </div>
                <div class="step active">
                    <span class="step-number">3</span>
                    <span class="step-name">Livraison</span>
                </div>
            </div>
        </header>

        <?php if (isset($errorMessage)): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $errorMessage; ?>
        </div>
        <?php endif; ?>

        <main class="checkout-main">
            <div class="checkout-content">
                <!-- Informations client -->
                <section class="checkout-section client-info">
                    <h2 class="section-title">Informations de livraison</h2>
                    <form id="checkout-form" method="post" action="checkout.php">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">Prénom</label>
                                <input type="text" id="firstName" name="firstName" required value="<?php echo isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="lastName">Nom</label>
                                <input type="text" id="lastName" name="lastName" required value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : ''; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Téléphone</label>
                            <input type="tel" id="phone" name="phone" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="address">Adresse</label>
                            <input type="text" id="address" name="address" required value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">Ville</label>
                                <input type="text" id="city" name="city" required value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="zipCode">Code postal</label>
                                <input type="text" id="zipCode" name="zipCode" required value="<?php echo isset($_POST['zipCode']) ? htmlspecialchars($_POST['zipCode']) : ''; ?>">
                            </div>
                        </div>
                    </form>
                </section>

                <!-- Résumé du panier -->
                <section class="checkout-section cart-summary">
                    <h2 class="section-title">Votre commande</h2>
                    <div class="cart-items">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="cart-item">
                                <div class="item-image">
                                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                </div>
                                <div class="item-details">
                                    <h3 class="item-name"><?php echo $item['name']; ?></h3>
                                    <div class="item-price"><?php echo number_format($item['price'], 2); ?> DH</div>
                                </div>
                                <div class="item-quantity">
                                    <button type="button" class="quantity-btn minus" data-id="<?php echo $item['id']; ?>">-</button>
                                    <span class="quantity-value"><?php echo $item['quantity']; ?></span>
                                    <button type="button" class="quantity-btn plus" data-id="<?php echo $item['id']; ?>">+</button>
                                </div>
                                <div class="item-total">
                                    <?php echo number_format($item['price'] * $item['quantity'], 2); ?> DH
                                </div>
                                <button type="button" class="remove-item" data-id="<?php echo $item['id']; ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="cart-actions">
                        <a href="products.php" class="btn btn-outline">Continuer vos achats</a>
                    </div>
                </section>
            </div>

            <!-- Section de paiement -->
            <aside class="payment-sidebar">
                <div class="payment-summary">
                    <h2 class="section-title">Résumé</h2>
                    <div class="summary-row">
                        <span>Sous-total</span>
                        <span><?php echo number_format($subtotal, 2); ?> DH</span>
                    </div>
                    <div class="summary-row">
                        <span>Frais de livraison</span>
                        <span><?php echo number_format($shipping, 2); ?> DH</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span><?php echo number_format($total, 2); ?> DH</span>
                    </div>
                </div>

                <div class="payment-methods">
                    <h2 class="section-title">Méthode de paiement</h2>
                    <div class="payment-tabs">
                        <button type="button" class="payment-tab active" data-method="card">Carte bancaire</button>
                        <button type="button" class="payment-tab" data-method="paypal">PayPal</button>
                    </div>

                    <div class="payment-form card-form active">
                        <div class="form-group">
                            <label for="cardNumber">Numéro de carte</label>
                            <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 5678 9012 3456">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="cardExpiry">Date d'expiration</label>
                                <input type="text" id="cardExpiry" name="cardExpiry" placeholder="MM/AA">
                            </div>
                            <div class="form-group">
                                <label for="cardCvv">CVV</label>
                                <input type="text" id="cardCvv" name="cardCvv" placeholder="123">
                            </div>
                        </div>
                    </div>

                    <div class="payment-form paypal-form">
                        <p class="paypal-info">Vous serez redirigé vers PayPal pour finaliser votre paiement.</p>
                        <div class="paypal-logo">
                            <i class="fab fa-paypal"></i> PayPal
                        </div>
                    </div>

                    <button type="submit" form="checkout-form" class="btn btn-payment">
                        Payer <?php echo number_format($total, 2); ?> DH maintenant
                    </button>
                    <p class="secure-payment">
                        <i class="fas fa-lock"></i> Votre paiement est sécurisé
                    </p>
                </div>
            </aside>
        </main>
    </div>
    <script src="assets/js/checkout.js"></script>
</body>
</html>


