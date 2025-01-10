<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclure les fichiers nécessaires de PHPMailer
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars($_POST['nom'], ENT_QUOTES, 'UTF-8');
    $prenom = htmlspecialchars($_POST['prenom'], ENT_QUOTES, 'UTF-8');
    $theme = htmlspecialchars($_POST['theme'], ENT_QUOTES, 'UTF-8');
    $question = htmlspecialchars($_POST['textAreaAvis'], ENT_QUOTES, 'UTF-8');


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
    $mail->Subject = 'Support Plateforme - Nouvelle question soumise';


    // Construire le contenu de l'e-mail
    $mail->isHTML(true);

    
    // Corps du message (avec toutes les infos)
    $mail->Body = "
                <h1>Nouvelle question d'un utilisateur</h1>
                <p>Bonjour cher membre de la Scooby-Team : </p>
                <p><strong>{$prenom} {$nom}</strong> vous à poser une question.</p
                <p>Voici le thème de la question: <strong>{$theme}</strong></p>
                <br>
                <p>Contenu de la question :</p>
                <p><strong>{$question}</strong></p>
                <br>
                <p>Cordialement,</p>
                <p><strong>L’équipe ScoobyTeam</strong></p>
            ";


    // Si tout va bien, envoi de l'email
    if(!$mail->send()) {
        echo 'Erreur : ' . $mail->ErrorInfo;
    } else {
        echo 'Le message a été envoyé avec succès !';
    }
}
?>

