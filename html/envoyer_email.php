<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
var_dump(file_exists('phpmailer/src/PHPMailer.php'));

// Inclure les fichiers PHPMailer nécessaires
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $theme = htmlspecialchars($_POST['theme']);
    $question = htmlspecialchars($_POST['textAreaAvis']);

    // Initialiser PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurer le serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Serveur SMTP (ici Gmail)
        $mail->SMTPAuth = true;
        $mail->Username = 'harry.rajic56@gmail.com'; // Adresse Gmail
        $mail->Password = 'ORD22IANJc?'; // Mot de passe ou mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Sécurisation STARTTLS
        $mail->Port = 587;

        // Configurer l'expéditeur et le destinataire
        $mail->setFrom('ton_email@gmail.com', 'Nom du site'); // L'expéditeur
        $mail->addAddress('destinataire_email@example.com', 'Destinataire'); // Le destinataire

        // Définir le sujet et le contenu du message
        $mail->Subject = "Demande de contact : $theme";
        $mail->Body = "Nom : $nom\nPrénom : $prenom\nThème : $theme\n\nQuestion :\n$question";

        // Envoyer l'e-mail
        $mail->send();
        echo "Le message a été envoyé avec succès.";
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi du message : {$mail->ErrorInfo}";
    }
} else {
    echo "Requête non valide.";
}
?>
