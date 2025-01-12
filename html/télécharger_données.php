<?php
// Vérifie si la demande de téléchargement des données a été soumise
if (isset($_POST['dwl-data'])) {
    // Connexion à la base de données (remplacer avec ta propre logique de connexion)
    $dbh = new PDO('mysql:host=localhost;dbname=tripenarvor', 'root', '');

    // Récupération des avis de l'utilisateur
    $avis = $dbh->prepare("SELECT o.titre_offre, a.txt_avis, a.note 
                           FROM tripenarvor._avis a 
                           JOIN tripenarvor._offre o ON a.code_offre = o.code_offre
                           WHERE a.code_compte = :code_compte");
    $avis->bindValue(":code_compte", $compte['code_compte']); // À adapter avec ta propre variable
    $avis->execute();
    $result = $avis->fetchAll(PDO::FETCH_ASSOC);

    $tab_avis = [];
    foreach ($result as $res) {
        if (!array_key_exists($res['titre_offre'], $tab_avis)) {
            $tab_avis[$res['titre_offre']] = [];
        }
        $content = html_entity_decode($res['txt_avis'], ENT_QUOTES, 'UTF-8');
        $tab_avis[$res['titre_offre']][] = [
            'content' => $content,
            'note' => $res['note']
        ];
    }

    // Récupération des autres données utilisateur
    $data = [
        'Nom' => $monCompteMembre['nom'],
        'Prenom' => $monCompteMembre['prenom'],
        'Pseudo' => $monCompteMembre['pseudo'],
        'Email' => $compte['mail'],
        'Téléphone' => $compte['telephone'],
        'Liste_Avis' => $tab_avis
    ];

    // Préparation du fichier JSON
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // Envoi des en-têtes pour forcer le téléchargement du fichier
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="mes_donnees_PACT.json"');
    header('Content-Length: ' . strlen($jsonData));
    
    // Envoi du fichier JSON au navigateur
    echo $jsonData;
}
?>
