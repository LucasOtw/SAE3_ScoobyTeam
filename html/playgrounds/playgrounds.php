<?php

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../.security/config.php';

use OTPHP\TOTP;

$getSecret = $dbh->prepare('SELECT code_secret FROM tripenarvor._compte_otp WHERE code_compte = 2');
$getSecret->execute();

$secret = $getSecret->fetchColumn();
var_dump($secret);

$input = "638481";

$otp = TOTP::createFromSecret($secret);
var_dump($otp->verify($input));

?>