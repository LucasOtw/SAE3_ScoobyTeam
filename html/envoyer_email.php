<?php
// Inclure PHPMailer
require 'PHPMailer/PHPMailerAutoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $theme = $_POST['theme'];
    $question = $_POST['textAreaAvis'];

    // Créer une instance de PHPMailer
    $mail = new PHPMailer;

    // Configuration de PHPMailer
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Serveur SMTP de Gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'noreply.scoobyteam@gmail.com'; // Ton adresse email
    $mail->Password = 'yejz rjye ntfh ryjv'; // Ton mot de passe d'application
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Définir l'expéditeur et le destinataire
    $mail->setFrom('noreply.scoobyteam@gmail.com', 'ScoobyTeam');
    $mail->addAddress('noreply.scoobyteam@gmail.com' , 'Destinataire');

    // Sujet de l'email
    $mail->Subject = 'Nouvelle question soumise';

    // Corps du message (avec toutes les infos)
    $mail->Body    = "
        <h2>Information reçue</h2>
        <p><strong>Nom :</strong> $nom</p>
        <p><strong>Prénom :</strong> $prenom</p>
        <p><strong>Thème :</strong> $theme</p>
        <p><strong>Question :</strong> $question</p>
    ";

    // Si tout va bien, envoi de l'email
    if(!$mail->send()) {
        echo 'Erreur : ' . $mail->ErrorInfo;
    } else {
        echo 'Le message a été envoyé avec succès !';
    }
}
?>

