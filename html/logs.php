<?php
// logs.php

function writeLog($type, $message) {
    $logDir = __DIR__ . '/logs';

    // Créer le dossier logs s'il n'existe pas
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    // Format du fichier de log : un fichier par jour
    $logFile = $logDir . '/log_' . date('Y-m-d') . '.log';

    // Format de la date
    $date = date('Y-m-d H:i:s');

    // Format du message
    $logMessage = "[$date][$type] $message" . PHP_EOL;

    // Écriture dans le fichier
    file_put_contents($logFile, $logMessage, FILE_APPEND);
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

// Exemple d'utilisation
// logValidation('Utilisateur connecté avec succès.');
// logWarning('Mot de passe expiré détecté pour l\'utilisateur.');
// logError('Impossible de se connecter à la base de données.');
?>
