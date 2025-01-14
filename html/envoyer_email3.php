<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

header('Content-Type: application/json'); // Définir la réponse comme JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply.scoobyteam@gmail.com';
        $mail->Password = 'yejz rjye ntfh ryjv'; // Remplacez par un mot de passe sécurisé ou un mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('noreply.scoobyteam@gmail.com', 'ScoobyTeam');
        $mail->addAddress($email);
        $mail->Subject = 'Bienvenue à la Newsletter PACT !';
        $mail->isHTML(true);
        $mail->Body = "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }
            .container {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                padding: 20px;
            }
            h1 {
                color: #333;
                font-size: 24px;
                margin-bottom: 20px;
            }
            p {
                font-size: 16px;
                color: #555;
                line-height: 1.5;
                margin-bottom: 15px;
            }
            .button {
                display: inline-block;
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                margin-top: 20px;
                font-size: 16px;
            }
            .footer {
                font-size: 12px;
                color: #888;
                text-align: center;
                margin-top: 30px;
            }
            .footer a {
                color: #888;
                text-decoration: none;
            }
            .footer a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Bienvenue dans la Newsletter PACT !</h1>
            <p>Bonjour et merci pour votre inscription à la newsletter de PACT ! Nous sommes ravis de vous accueillir parmi nos abonnés.</p>
            <p>Notre objectif est de vous offrir des contenus exclusifs, des informations pertinentes et des offres spéciales pour vous tenir informé des dernières nouveautés et événements de notre équipe.</p>
            <p>Voici ce que vous pouvez attendre de nos prochaines newsletters :</p>
            <ul>
                <li><strong>Des actualités :</strong> Les nouvelles offres de l'équipe et de nos partenaires.</li>
                <li><strong>Des offres spéciales :</strong>Un site fait pour vous.</li>
                <li><strong>Des événements à venir :</strong> Soyez les premiers informés des nouvelles offres.</li>
            </ul>
            <p>Restez à l'écoute pour des contenus enrichissants qui vous aideront à mieux suivre nos projets et nos succès !</p>
            <p>Nous vous remercions encore pour votre inscription. Si vous avez des questions ou des suggestions, n'hésitez pas à nous contacter à l'adresse suivante : <a href='mailto:noreply.scoobyteam@gmail.com'>support@pact.com</a>.</p>
            <a href='https://scooby-team.ventsdouest.dev' class='button'>Découvrez notre site web</a>
            <div class='footer'>
                <p>Vous ne souhaitez plus recevoir nos emails ? <a href='https://scooby-team.ventsdouest.dev/connexion_membre.php'>Cliquez ici pour vous désinscrire</a>.</p>
                <p>&copy; 2025 PACT. Tous droits réservés.</p>
            </div>
        </div>
    </body>
    </html>
";


        try {
            if ($mail->send()) {
                echo json_encode(['status' => 'success']); // Réponse JSON en cas de succès
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'envoi de l\'email.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Adresse email invalide.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Requête invalide.']);
}
?>
