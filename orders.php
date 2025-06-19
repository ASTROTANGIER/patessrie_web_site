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

// Récupérer le statut de filtre
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Requête SQL de base
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

// Préparer et exécuter la requête
$stmt = $pdo->prepare($sql);
if ($status_filter !== 'all') {
    $stmt->bindParam(':status', $status_filter);
}
$stmt->execute();
$orders = $stmt->fetchAll();

// Traitement des actions
if (isset($_POST['action']) && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $action = $_POST['action'];
    
    if ($action === 'update_status' && isset($_POST['status'])) {
        $new_status = $_POST['status'];
        $stmt = $pdo->prepare("UPDATE orders SET status = :status, updated_at = NOW() WHERE order_id = :order_id");
        $stmt->execute([
            'status' => $new_status,
            'order_id' => $order_id
        ]);
        
        // Rediriger pour éviter la soumission multiple du formulaire
        header("Location: orders.php?status=$status_filter&updated=1");
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
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar (inclure votre sidebar ici) -->
        
        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1>Gestion des commandes</h1>
                <div class="admin-actions">
                    <a href="dashbord.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                    </a>
                </div>
            </div>
            
            <!-- Notification de mise à jour -->
            <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>Statut de la commande mis à jour avec succès.</div>
                </div>
            <?php endif; ?>
            
            <!-- Filtres -->
            <div class="filters-bar">
                <div class="filters">
                    <a href="orders.php" class="filter-link <?php echo $status_filter === 'all' ? 'active' : ''; ?>">
                        Toutes les commandes
                    </a>
                    <a href="orders.php?status=en_attente" class="filter-link <?php echo $status_filter === 'en_attente' ? 'active' : ''; ?>">
                        En attente
                    </a>
                    <a href="orders.php?status=en_preparation" class="filter-link <?php echo $status_filter === 'en_preparation' ? 'active' : ''; ?>">
                        En préparation
                    </a>
                    <a href="orders.php?status=en_livraison" class="filter-link <?php echo $status_filter === 'en_livraison' ? 'active' : ''; ?>">
                        En livraison
                    </a>
                    <a href="orders.php?status=livree" class="filter-link <?php echo $status_filter === 'livree' ? 'active' : ''; ?>">
                        Livrées
                    </a>
                    <a href="orders.php?status=annulee" class="filter-link <?php echo $status_filter === 'annulee' ? 'active' : ''; ?>">
                        Annulées
                    </a>
                </div>
                <div class="search-bar">
                    <input type="text" id="orderSearch" placeholder="Rechercher une commande..." class="search-input">
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            
            <!-- Liste des commandes -->
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Téléphone</th>
                            <th>Produits</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="8" class="empty-table">
                                    <div class="empty-state">
                                        <i class="fas fa-shopping-cart"></i>
                                        <p>Aucune commande trouvée</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['order_id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['client_name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['client_phone']); ?></td>
                                    <td><?php echo $order['item_count']; ?> article(s)</td>
                                    <td><?php echo number_format($order['total_amount'], 2); ?> DH</td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $order['status']; ?>">
                                            <?php 
                                                $status_labels = [
                                                    'en_attente' => 'En attente',
                                                    'en_preparation' => 'En préparation',
                                                    'en_livraison' => 'En livraison',
                                                    'livree' => 'Livrée',
                                                    'annulee' => 'Annulée'
                                                ];
                                                echo $status_labels[$order['status']] ?? $order['status'];
                                            ?>
                                        </span>
                                    </td>
                                    <td class="actions-cell">
                                        <a href="order_detail.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-outline">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                        <button class="btn btn-sm btn-primary" onclick="showStatusModal(<?php echo $order['order_id']; ?>)">
                                            <i class="fas fa-edit"></i> Statut
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <!-- Modal pour changer le statut -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Modifier le statut</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="statusForm" method="post" action="orders.php">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="order_id" id="modalOrderId">
                    
                    <div class="form-group">
                        <label for="status">Nouveau statut</label>
                        <select name="status" id="status" class="form-control">
                            <option value="en_attente">En attente</option>
                            <option value="en_preparation">En préparation</option>
                            <option value="en_livraison">En livraison</option>
                            <option value="livree">Livrée</option>
                            <option value="annulee">Annulée</option>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline" onclick="closeModal()">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Fonctions pour le modal
       