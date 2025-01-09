<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $theme = htmlspecialchars($_POST['theme']);
    $question = htmlspecialchars($_POST['textAreaAvis']);
    
    // Destinataire de l'email
    $destinataire = "valentin.samson@etudiant.univ-rennes.fr"; // Remplacer par l'adresse à laquelle tu veux envoyer les messages
    
    // Sujet de l'email
    $sujet = "Demande de contact de " . $prenom . " " . $nom;
    
    // Corps du message
    $message = "
    Nom : $nom
    Prénom : $prenom
    Thème : $theme
    
    Question :
    $question
    ";
    
    // En-têtes de l'email
    $headers = "From: noreply@tonsite.com\r\n"; // Remplacer avec une adresse email valide si nécessaire
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Envoi de l'email
    if (mail($destinataire, $sujet, $message, $headers)) {
        echo "Votre message a été envoyé avec succès.";
    } else {
        echo "Erreur lors de l'envoi de votre message.";
    }
}
?>
