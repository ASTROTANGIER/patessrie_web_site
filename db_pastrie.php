<?php
/**
 * Connexion à la base de données - Pâtisserie Haloui
 * Fichier centralisé pour toutes les connexions à la base de données
 */

// Constantes de configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'patessrie');
define('DB_USER', 'root');
define('DB_PASS', ''); // Use your database password if you have one
define('DB_CHARSET', 'utf8mb4');

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (\PDOException $e) {
    // In a real application, you would log this error and show a generic message
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Database connection failed."]);
    exit;
}
?>
