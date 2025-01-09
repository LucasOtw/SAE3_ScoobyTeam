<?php
// Inclure PHPMailer
require 'phpmailer/PHPMailerAutoload.php';

// Vérifier que la méthode de la requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $theme = htmlspecialchars($_POST['theme']);
    $question = htmlspecialchars($_POST['textAreaAvis']);

    // Créer une instance de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurer le serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'harry.rajic56@gmail.com'; // Ton adresse Gmail
        $mail->Password = 'ORD22IANJc?'; // Ton mot de passe Gmail (ou mot de passe d'application)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Définir l'expéditeur et le destinataire
        $mail->setFrom('ton_email@gmail.com', 'Nom du site');
        $mail->addAddress('harry.rajic56@gmail.com', 'La Scooby-team');

        // Définir le sujet et le corps du message
        $mail->isHTML(false); // Si tu veux envoyer un e-mail en texte brut
        $mail->Subject = "Demande de contact de $prenom $nom";
        $mail->Body = "
        Nom : $nom
        Prénom : $prenom
        Thème : $theme
        
        Question :
        $question
        ";

        // Envoi de l'email
        $mail->send();
        echo "Votre message a été envoyé avec succès.";
    } catch (Exception $e) {
        echo "Le message n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
    }
} else {
    // Si ce n'est pas une requête POST
    echo "Une erreur s'est produite. Veuillez réessayer.";
}
?>
