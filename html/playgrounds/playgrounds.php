<?php 

        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
        $username = "sae";
        $password = "philly-Congo-bry4nt";

        // Créer une instance PDO
        $dbh = new PDO($dsn, $username, $password);

        $mail2 = "pro@gmail.com";
        $mail = "Valentin.Londubat@etudiant.univ-rennes.fr";

        $existeUser = $dbh->prepare("SELECT * FROM tripenarvor._compte WHERE mail='$mail2'");
        $existeUser->execute();
        $existeUser->fetch();
        
        if(count($existeUser) > 0){
                // si l'utilisateur existe, on doit vérifier que c'est un membre
                echo "il existe";
        }

        var_dump($existeUser);

echo "</pre>";
?>
