<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aide</title>
    <link rel="stylesheet" href="contacter_plateforme.css">
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

