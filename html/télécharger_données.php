// TELECHARGEMENT DES DONNEES (FORMAT JSON)
if (isset($_POST['dwl-data'])) {
    // Récupération des avis
    $avis = $dbh->prepare("SELECT o.titre_offre, a.txt_avis, a.note FROM tripenarvor._avis a 
                           JOIN tripenarvor._offre o ON a.code_offre = o.code_offre
                           WHERE a.code_compte = :code_compte");
    $avis->bindValue(":code_compte", $compte['code_compte']);
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

    // Préparation des données JSON
    $data = [
        'Nom' => $monCompteMembre['nom'],
        'Prenom' => $monCompteMembre['prenom'],
        'Pseudo' => $monCompteMembre['pseudo'],
        'Email' => $compte['mail'],
        'Téléphone' => $compte['telephone'],
        'Liste_Avis' => $tab_avis
    ];
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // Envoi des en-têtes pour le téléchargement
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="mes_donnees_PACT.json"');
    header('Content-Length: ' . strlen($jsonData));
    
    echo $jsonData;
}
?>
