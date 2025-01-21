<?php
require_once '../vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    // Affiche toutes les variables d'environnement chargées
    print_r($_ENV);
} catch (Exception $e) {
    echo "Error loading .env file: " . $e->getMessage();
}
