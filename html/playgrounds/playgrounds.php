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
        $existeUser = $existeUser->fetch(PDO::FETCH_ASSOC);
        
        if(count($existeUser) > 0){
                // si l'utilisateur existe, on doit vérifier que c'est un membre
                echo "il existe";
                $verifMembre = $dbh->prepare("SELECT * FROM tripenarvor._membre WHERE code_compte = :code_compte");
                $verifMembre->bindValue(":code_compte",$existeUser['code_compte']);
                $verifMembre->execute();

                $estMembre = $verifMembre->fetch(PDO::FETCH_ASSOC);
                if(count($estMembre) > 0){
                        echo "C'est un membre";
                } else {
                        echo "Who the hell are you ?";
                }
        }
echo "<pre>";
var_dump($existeUser);
echo "</pre>";
?>
