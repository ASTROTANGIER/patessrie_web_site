<?php
/**
 * Connexion sécurisée à la base de données
 * Ce fichier est inclus par le dashboard et d'autres pages admin
 */

// Vérification que config.php a été chargé
if (!defined('APP_NAME')) {
    require_once 'config.php';
}

// Vérification supplémentaire de sécurité
if (!isset($_SESSION)) {
    session_start();
}

// Vérification du temps d'inactivité (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    redirect('../auth/admin-signin.php', 'Votre session a expiré. Veuillez vous reconnecter.', 'warning');
}

// Mise à jour du temps d'activité
$_SESSION['last_activity'] = time();