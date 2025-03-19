<?php

require_once __DIR__ . ("/../../.security/config.php");

$test = $dbh->prepare("SELECT * FROM tripenarvor._compte");
$test->execute();

echo "<pre>";
var_dump($test->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";

?>