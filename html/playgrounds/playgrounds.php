<?php 

        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
        $username = "sae";
        $password = "philly-Congo-bry4nt";

        // Créer une instance PDO
        $dbh = new PDO($dsn, $username, $password);

        $afficheUtilisateur = $dbh->query("SELECT * FROM tripenarvor._compte WHERE code_compte = 1");
        $afficheUtilisateur = $afficheUtilisateur->fetch(PDO::FETCH_ASSOC);

echo "<pre>";
var_dump($afficheUtilisateur);
echo "</pre>";
?>
