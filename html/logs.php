<?php
// logs.php

// Heure française
date_default_timezone_set('Europe/Paris');

function writeLog($type, $message) {
    // Dossier logs (on remonte de html/ à web/)
    $logDir = dirname(__DIR__) . '/logs';

    // Créer le dossier logs s'il n'existe pas
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    // Fichier centralisé
    $logFile = $logDir . '/2fa.log';

    // Heure au format français
    $date = date('d/m/Y H:i:s');

    // Couleurs ANSI pour terminal (optionnel)
    switch ($type) {
        case 'VALIDATION':
            $colorStart = "\033[32m"; // Vert
            break;
        case 'WARNING':
            $colorStart = "\033[33m"; // Jaune
            break;
        case 'ERROR':
            $colorStart = "\033[31m"; // Rouge
            break;
        default:
            $colorStart = "\033[0m";  // Reset
    }

    $colorReset = "\033[0m";

    // Format du message
    $logMessage = "[$date][$type] $message";

    // Écriture dans le fichier (sans couleur, pour lisibilité)
    file_put_contents($logFile, $logMessage . PHP_EOL, FILE_APPEND);

    // Affichage en couleur si exécuté depuis un terminal
    if (php_sapi_name() === 'cli') {
        echo $colorStart . $logMessage . $colorReset . PHP_EOL;
    }
}

function logValidation($message) {
    writeLog('VALIDATION', $message);
}

function logWarning($message) {
    writeLog('WARNING', $message);
}

function logError($message) {
    writeLog('ERROR', $message);
}
?>
