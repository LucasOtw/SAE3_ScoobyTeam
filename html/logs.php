<?php
// logs.php

function writeLog($type, $message) {
    // On remonte de html/ à web/
    $logDir = dirname(__DIR__) . '/logs';

    // Créer le dossier logs s'il n'existe pas
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }

    // Fichier unique
    $logFile = $logDir . '/2fa.log';

    // Date et format
    $date = date('Y-m-d H:i:s');
    $logMessage = "[$date][$type] $message" . PHP_EOL;

    // Écriture
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
