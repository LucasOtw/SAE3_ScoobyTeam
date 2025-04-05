<?php

require __DIR__ . '/../../vendor/autoload.php';

$isActiveOTP = $dbh->prepare('SELECT is_active FROM tripenarvor._compte_otp WHERE code_compte = :code_compte');
$isActiveOTP->bindValue(":code_compte", 2);
$isActiveOTP->execute();

$isActiveOTP = $isActiveOTP->fetchColumn();

var_dump($isActiveOTP);

?>