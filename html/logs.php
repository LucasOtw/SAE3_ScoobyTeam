<?php
// logs.php

function writeLog($type, $message) {
    // Dossier logs dans web/
    $logDir = realpath(__DIR__ . '/..') . '/logs';

    // Créer le dossier logs s'il n'existe pas
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    // Fichier unique pour tous les logs 2FA
    $logFile = $logDir . '/2fa.log';

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
?>
