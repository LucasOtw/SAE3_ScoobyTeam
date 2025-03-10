<?php

$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_ROOT_PASSWORD'];

echo "DSN : " . $dsn;
// Créer une instance PDO
$dbh = new PDO($dsn, $username, $password);

// UPDATE DE BDD

$var1 = "../images/offres/MeinKraft/minecraft.jpg";
$var2 = "images/offres/MeinKrafte/minecraft.jpg";

$req = $dbh->prepare("UPDATE tripenarvor._image SET url_image = :nouv_val WHERE url_image = :ancienne_val");
$req->bindValue(":nouv_val", $var2);
$req->bindValue(":ancienne_val", $var1);
if ($req->execute()) {
        echo "Hello World !";
}
?>