<?php
// Fonction pour charger le fichier .env manuellement
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

// Charger les variables d'environnement
loadEnv(__DIR__ . '/.env');

// Récupération des variables d'environnement
$host = 'postgresdb';
$port = $_ENV['PGDB_PORT'] ?? '5432';
$dbname = $_ENV['DB_NAME'] ?? 'sae';
$username = $_ENV['DB_USER'] ?? 'sae';
$password = $_ENV['DB_ROOT_PASSWORD'] ?? '';

// Chaîne de connexion
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
