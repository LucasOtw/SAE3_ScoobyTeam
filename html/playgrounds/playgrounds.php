<?php 

        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
        $username = "sae";
        $password = "philly-Congo-bry4nt";

        // Créer une instance PDO
        $dbh = new PDO($dsn, $username, $password);

        // Récupération de tout le contenu de "_compte"
        $recupComptes = $dbh->prepare('SELECT * FROM tripenarvor._compte');
        $recupComptes->execute();

        $lesComptes = $recupComptes->fetchAll(PDO::FETCH_ASSOC);

        $recupComptesPro = $dbh->prepare('
            SELECT c.*, p.raison_sociale 
            FROM tripenarvor._compte c
            JOIN tripenarvor._professionnel p ON c.code_compte = p.code_compte
        ');
        $recupComptesPro->execute();

    $raison_sociale = "Mon Entreprise";

    $verifRaisonSociale = $dbh->prepare("SELECT 1 FROM tripenarvor._professionnel WHERE raison_sociale = :raison_sociale");
    $verifRaisonSociale->bindValue(":raison_sociale",$raison_sociale);
    $verifRaisonSociale->execute();

    $existeRaisonSociale = $verifRaisonSociale->fetch();

echo "<pre>";
var_dump($existeRaisonSociale);

if($existeRaisonSociale){
        echo "Cette raison sociale est déjà prise !";
} else {
        echo "Aussi libre qu'une pute un jour de Noël.";
}

echo "</pre>";
?>
