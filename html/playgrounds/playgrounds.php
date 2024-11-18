<?php 

        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
        $username = "sae";
        $password = "philly-Congo-bry4nt";

        // Créer une instance PDO
        $dbh = new PDO($dsn, $username, $password);

        // Récupération de tout le contenu de "_compte"
        $recupComptes = $dbh->prepare('SELECT * FROM tripenarvor.compte');
        $recupComptes->execute();

        $lesComptes = $recupComptes->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
var_dump($lesComptes);
echo "</pre>";
?>
