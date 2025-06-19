<?php
/**
 * Configuration principale de l'application
 */

// Informations de l'application
define('APP_NAME', 'Pâtisserie Haloui');
define('APP_VERSION', '1.0.0');

// Configuration de la base de données
$db_config = [
    'host' => 'localhost',
    'dbname' => 'patessrie',
    'user' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
];

// Fonction de redirection avec message
function redirect($url, $message = null, $type = 'info') {
    if ($message) {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type
        ];
    }
    header("Location: $url");
    exit;
}

// Fonction pour formater les prix
function formatPrice($price) {
    return number_format($price, 2) . ' DH';
}

// Fonction pour formater les dates
function formatDate($date, $format = 'd/m/Y H:i') {
    return date($format, strtotime($date));
}

// Classe de connexion à la base de données
class Database {
    private $pdo;
    
    public function __construct($config) {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            $this->pdo = new PDO($dsn, $config['user'], $config['password'], $options);
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }
    
    public function fetchOne($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch();
    }
    
    public function fetchAll($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}

// Initialisation de la connexion à la base de données
$db = new Database($db_config);

// Classe pour les logs
class Logger {
    public static function error($message, $context = []) {
        // Simple logging to error_log for now
        error_log("ERROR: $message - " . json_encode($context));
    }
}
