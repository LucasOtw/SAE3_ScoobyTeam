<?php
require_once __DIR__ . '/vendor/autoload.php'; // Charge les librairies installées par Composer

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2)); // Remonte de 2 niveaux pour atteindre /docker/sae/
$dotenv->load();
?>