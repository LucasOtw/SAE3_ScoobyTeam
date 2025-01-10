<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclure les fichiers nécessaires de PHPMailer
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

// Créer une instance de PHPMailer
$mail = new PHPMailer(true);

try {
    // Activer le mode debug pour voir les erreurs détaillées
    $mail->SMTPDebug = 2; // 0 = Désactiver, 2 = Debug complet

    // Configuration du serveur SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Adresse du serveur SMTP (par ex., Gmail)
    $mail->SMTPAuth = true; // Activer l'authentification SMTP
    $mail->Username = 'noreply.scoobyteam@gmail.com'; // Remplace par ton adresse e-mail
    $mail->Password = 'yejz rjye ntfh ryjv'; // Remplace par le mot de passe d'application
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Type de chiffrement (TLS recommandé)
    $mail->Port = 587; // Port SMTP pour TLS (587 pour Gmail)

    // Informations de l'expéditeur et du destinataire
    $mail->setFrom('valentin.samson@etudiant.univ-rennes.fr', 'Ton Nom'); // Adresse et nom de l'expéditeur
    $mail->addAddress('harry.rajic56@gmail.com', 'Nom du Destinataire'); // Adresse et nom du destinataire

    // Contenu de l'e-mail
    $mail->isHTML(true); // Permet d'envoyer des e-mails HTML
    $mail->Subject = 'Sujet du message';
    $mail->Body = '<h1>Voici un e-mail envoyé avec PHPMailer</h1><p>Ceci est un message HTML.</p>';
    $mail->AltBody = 'Ceci est une version texte du message (pour les clients qui n\'affichent pas HTML).';

    // Envoyer l'e-mail
    if ($mail->send()) {
        echo 'Le message a été envoyé avec succès !';
    }
} catch (Exception $e) {
    echo "Erreur lors de l'envoi du message : {$mail->ErrorInfo}";
}
?>
