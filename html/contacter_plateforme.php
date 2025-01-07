<?php
// Inclure le fichier pour la gestion du header (partie HTML du haut)
include('header.php');

// Si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $question = trim($_POST['question']);

    // Vérification de la validité des champs
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($question)) {
        // Destinataire du mail (ton adresse)
        $to = 'ton-email@exemple.com';
        $subject = 'Question depuis le formulaire de contact';
        $message = "De: $email\n\nMessage:\n$question";
        $headers = "From: $email";

        // Envoi de l'email
        if (mail($to, $subject, $message, $headers)) {
            $message_sent = "Votre question a bien été envoyée !";
        } else {
            $message_sent = "Une erreur est survenue lors de l'envoi de votre message. Veuillez réessayer.";
        }
    } else {
        $message_sent = "Veuillez remplir tous les champs correctement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aide</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header incluant le menu de navigation -->
    <header class="header-pc header_membre">
        <div class="logo-pc">
            <a href="voir_offres.php">
                <img src="images/logoBlanc.png" alt="PACT Logo">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="voir_offres.php">Accueil</a></li>
                <li><a href="connexion_pro.php">Publier</a></li>
                <li><a href="aide.php" class="active">Aide</a></li>
            </ul>
        </nav>
    </header>

  
    <main class="connexion_membre_main">
        <h2>Nous contacter</h2>
        <p>Si vous avez des questions, veuillez nous envoyer un message via le formulaire ci-dessous.</p>

        <!-- Formulaire pour poser une question -->
        <form action="aide.php" method="POST">
            <fieldset>
                <legend>Email</legend>
                <input type="email" name="email" placeholder="Votre adresse e-mail" required>
            </fieldset>
            <fieldset>
                <legend>Votre question</legend>
                <textarea name="question" placeholder="Posez votre question ici..." required></textarea>
            </fieldset>
            <button type="submit">Envoyer</button>
        </form>

        <!-- Message de confirmation ou d'erreur -->
        <?php if (isset($message_sent)): ?>
            <p><?= $message_sent; ?></p>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 VotreSite. Tous droits réservés.</p>
    </footer>
</body>
</html>

