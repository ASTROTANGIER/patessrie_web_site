<?php
/**
 * Gestion des commandes - Dashboard administrateur
 * Pâtisserie Haloui
 */

require_once 'db_pastrie.php';
require_once 'config.php';

// Démarrage de session sécurisée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification de l'authentification admin (à décommenter en production)
/*if (!isset($_SESSION['admin_id'])) {
    header('Location: signin.php');
    exit;
}*/

// Vérifier si les tables orders et order_items existent
try {
    // Vérifier si la table orders existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'orders'");
    $ordersTableExists = $stmt->rowCount() > 0;
    
    // Vérifier si la table order_items existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'order_items'");
    $orderItemsTableExists = $stmt->rowCount() > 0;
    
    // Définir la variable tableExists pour l'utiliser plus tard dans le code
    $tableExists = ($ordersTableExists && $orderItemsTableExists);
    
    // Si les tables n'existent pas, les créer
    if (!$ordersTableExists || !$orderItemsTableExists) {
        // Créer la table orders si elle n'existe pas
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
        
        // Créer la table order_items si elle n'existe pas
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
        
        // Vérifier à nouveau si les tables existent après la tentative de création
        $stmt = $pdo->query("SHOW TABLES LIKE 'orders'");
        $ordersTableExists = $stmt->rowCount() > 0;
        
        $stmt = $pdo->query("SHOW TABLES LIKE 'order_items'");
        $orderItemsTableExists = $stmt->rowCount() > 0;
        
        $tableExists = ($ordersTableExists && $orderItemsTableExists);
        
        if ($tableExists) {
            $message = "Les tables de commandes ont été créées avec succès.";
            $messageType = "success";
        }
    }
} catch (PDOException $e) {
    $error = "Erreur lors de la vérification ou création des tables : " . $e->getMessage();
    $tableExists = false;
}

// Récupérer le statut de filtre
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Requête SQL de base
$orders = [];
$statuses = [];

try {
    // Ne récupérer les commandes que si les tables existent
    if ($tableExists) {
        $sql = "
            SELECT 
                o.order_id, 
                o.client_name, 
                o.client_phone, 
                o.total_amount, 
                o.status, 
                o.created_at,
                COUNT(oi.item_id) as item_count
            FROM orders o
            LEFT JOIN order_items oi ON o.order_id = oi.order_id
        ";

        // Ajouter le filtre de statut si nécessaire
        if ($status_filter !== 'all') {
            $sql .= " WHERE o.status = :status";
        }

        // Grouper et trier
        $sql .= " GROUP BY o.order_id ORDER BY o.created_at DESC";

        $stmt = $pdo->prepare($sql);
        
        // Lier le paramètre de statut si nécessaire
        if ($status_filter !== 'all') {
            $stmt->bindParam(':status', $status_filter, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Récupérer les statuts disponibles pour le filtre
        $stmt = $pdo->query("SELECT DISTINCT status FROM orders ORDER BY status");
        $statuses = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
} catch (PDOException $e) {
    $error = "Erreur de base de données : " . $e->getMessage();
}

// Traitement des actions
if (isset($_POST['action']) && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $action = $_POST['action'];
    
    if ($action === 'update_status' && isset($_POST['status'])) {
        try {
            $new_status = $_POST['status'];
            $stmt = $pdo->prepare("UPDATE orders SET status = :status, updated_at = NOW() WHERE order_id = :order_id");
            $stmt->execute([
                'status' => $new_status,
                'order_id' => $order_id
            ]);
            
            // Rediriger pour éviter la soumission multiple du formulaire
            header("Location: admin_orders.php?status=$status_filter&updated=1");
            exit;
        } catch (PDOException $e) {
            $error = "Erreur lors de la mise à jour du statut : " . $e->getMessage();
        }
    }
    
    if ($action === 'view_details') {
        // Rediriger vers la page de détails de la commande
        header("Location: admin_order_details.php?id=$order_id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des commandes - Pâtisserie Haloui</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar inclus depuis un fichier séparé -->
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <div class="main-content">
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h3 mb-0 text-gray-800">Gestion des commandes</h1>
                    <p class="text-muted">Suivi et gestion des commandes clients</p>
                </div>
            </div>

            <?php if (isset($_GET['updated'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> Le statut de la commande a été mis à jour avec succès.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if (!$tableExists): ?>
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> Les tables de commandes n'existent pas. 
                    <a href="create_orders_tables.php" class="alert-link">Cliquez ici pour créer les tables</a>.
                </div>
            <?php else: ?>
                <!-- Filter Bar -->
                <div class="filter-bar">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <form action="admin_orders.php" method="get" class="d-flex align-items-center">
                                <label for="status" class="me-2">Filtrer par statut:</label>
                                <select name="status" id="status" class="form-select form-select-sm me-2" style="width: auto;">
                                    <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>Tous les statuts</option>
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?php echo $status; ?>" <?php echo $status_filter === $status ? 'selected' : ''; ?>>
                                            <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Filtrer</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-shopping-cart me-2"></i> Liste des commandes
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Client</th>
                                        <th>Téléphone</th>
                                        <th>Montant</th>
                                        <th>Articles</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($orders)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-3">Aucune commande trouvée</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td>#<?php echo $order['order_id']; ?></td>
                                                <td><?php echo htmlspecialchars($order['client_name']); ?></td>
                                                <td><?php echo htmlspecialchars($order['client_phone']); ?></td>
                                                <td><?php echo number_format($order['total_amount'], 2); ?> DH</td>
                                                <td><?php echo $order['item_count']; ?></td>
                                                <td>
                                                    <?php 
                                                    $statusClass = '';
                                                    switch ($order['status']) {
                                                        case 'en_attente':
                                                            $statusClass = 'en_attente';
                                                            break;
                                                        case 'en_preparation':
                                                            $statusClass = 'en_preparation';
                                                            break;
                                                        case 'expedie':
                                                            $statusClass = 'expedie';
                                                            break;
                                                        case 'livree':
                                                            $statusClass = 'livree';
                                                            break;
                                                        case 'annulee':
                                                            $statusClass = 'annulee';
                                                            break;
                                                        default:
                                                            $statusClass = '';
                                                    }
                                                    ?>
                                                    <span class="status-badge <?php echo $statusClass; ?>">
                                                        <?php echo ucfirst(str_replace('_', ' ', $order['status'])); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <form method="post" action="admin_orders.php" class="d-inline">
                                                            <input type="hidden" name="action" value="view_details">
                                                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                            <button type="submit" class="btn btn-info btn-action">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-warning btn-action" data-bs-toggle="modal" data-bs-target="#statusModal" data-order-id="<?php echo $order['order_id']; ?>" data-status="<?php echo $order['status']; ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Modal pour changer le statut -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Modifier le statut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="statusForm" method="post" action="admin_orders.php">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="order_id" id="modalOrderId">
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Nouveau statut</label>
                            <select name="status" id="modalStatus" class="form-select" required>
                                <option value="en_attente">En attente</option>
                                <option value="en_preparation">En préparation</option>
                                <option value="expedie">Expédiée</option>
                                <option value="livree">Livrée</option>
                                <option value="annulee">Annulée</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" form="statusForm" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script pour remplir le modal avec les données de la commande
        document.addEventListener('DOMContentLoaded', function() {
            const statusModal = document.getElementById('statusModal');
            if (statusModal) {
                statusModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const orderId = button.getAttribute('data-order-id');
                    const status = button.getAttribute('data-status');
                    
                    const modalOrderId = document.getElementById('modalOrderId');
                    const modalStatus = document.getElementById('modalStatus');
                    
                    modalOrderId.value = orderId;
                    modalStatus.value = status;
                });
            }
            
            // Auto-submit du formulaire de filtre
            const statusFilter = document.getElementById('status');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });
    </script>
</body>
</html>



