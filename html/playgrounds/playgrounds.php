<?php 

        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
        $username = "sae";
        $password = "philly-Congo-bry4nt";

        // Créer une instance PDO
        $dbh = new PDO($dsn, $username, $password);

        $mail = "Valentin.Londubat@etudiant.univ-rennes.fr";

        $existeUser = $dbh->prepare("SELECT 1 FROM tripenarvor._compte WHERE mail='$email'");
        $existeUser->execute();
        $existeUser->fetch();

        var_dump($existeUser);

echo "</pre>";
?>
