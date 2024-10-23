<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes offres</title>
    <link rel="stylesheet" href="mes_offres.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
</head>
<body>
     <header>
        <div class="logo">
            <img src="images/logoBlanc.png" alt="PAVCT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="mes_offres.php" class="active">Accueil</a></li>
                <li><a href="creation_offre1.php">Publier</a></li>
                <li><a href="ajout_bancaire.php">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="profile">
            <div class="banner">
                <img src="images/Fond.png" alt="Bannière de profil">
            </div>
            <div class="profile-info">
                <img class="profile-picture" src="images/PhotodeProfil.png" alt="Profil utilisateur">
                <h1>Ti Al Lannec</h1>
                <p>Lal.lannec@pact.fr | 0109202548</p>
            </div>
        </section>
    
        <section class="tabs">
            <ul>
                <li><a href="#" class="active">informations personnelles</a></li>
                <li><a href="#" class="active">Offres</a></li>
                <li><a href="ajout_bancaire.php">Compte bancaire</a></li>
            </ul>
        </section>
    
        <section class="offers">
            <h2>Vos offres</h2>
<?php

// Check if the session variable is set
if (!isset($_SESSION["compte"])) {
    echo "Erreur: Vous devez être connecté pour voir vos offres.";
    exit; // Stop script execution
}

try {
    $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
    $username = "sae";
    $password = "philly-Congo-bry4nt";
    // Créer une instance PDO
    $dbh = new PDO($dsn, $username, $password);
    
    // Adjust the table name if necessary
    $stmt = $dbh->prepare('SELECT * FROM tripenarvor.offre WHERE professionnel = :professionnel');
    $stmt->execute(['professionnel' => $_SESSION["compte"]]);
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="offer-card">
            <div class="offer-image">
                <img src="images/hotel.jpg" alt="<?php echo htmlspecialchars($row['titre_offre']); ?>">
                <div class="offer-rating">
                    <span class="star">★</span>
                    <span class="rating"><?php echo round($row['note_moyenne'], 1); ?></span>
                </div>
                <div class="offer-status">
                    <?php echo $row['en_ligne'] ? '<span class="status-dot"></span> En Ligne' : '<span class="status-dot"></span> Hors Ligne'; ?>
                </div>
            </div>
            <div class="offer-info">
                <h3><?php echo htmlspecialchars($row['titre_offre']); ?></h3>
                <p class="category"><?php echo htmlspecialchars($row['type_offre']); ?></p>
                <p class="update"><span class="update-icon">⟳</span> Update <?php echo date_diff(date_create($row['date_derniere_modif']), date_create('today'))->days; ?>j</p>
                <p class="last-update">Mis à jour le <?php echo date_format(date_create($row['date_derniere_modif']), 'd/m/Y'); ?></p>
                <p class="offer-type">Offre Standard</p>
                <p class="price"><?php echo $row['tarif']?>€</p>
            </div>
            <button class="add-btn">+</button>
        </div>
        <?php
    }
    
    $dbh = null;
    
} catch (PDOException $e) {
    print "Erreur!: ". $e->getMessage(). "<br/>";
    die();
}
?>

            <button class="image-button">
            <span class="button-text">Publier une offre</span>
            </button>
        </section>    
    </main>
    
    <footer>
        <div class="newsletter">
            <div class="newsletter-content">
                <h2>Inscrivez-vous à notre Newsletter</h2>
                <p>PACT</p>
                <p>Redécouvrez la Bretagne !</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Votre adresse mail" required>
                    <button type="submit">S'inscrire</button>
                </form>
            </div>
            <div class="newsletter-image">
                <img src="images/BoiteAuxLettres.png" alt="Boîte aux lettres">
            </div>
        </div>
        
        <div class="footer-links">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Mentions Légales</a></li>
                    <li><a href="#">RGPD</a></li>
                    <li><a href="#">Nous connaître</a></li>
                    <li><a href="#">Nos partenaires</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mes_offres.php">Accueil</a></li>
                    <li><a href="#">Publier</a></li>
                    <li><a href="#">Historique</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">CGU</a></li>
                    <li><a href="#">Signaler un problème</a></li>
                    <li><a href="#">Nous contacter</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Presse</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">Notre équipe</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="social-icons">
                <a href="#"><img src="images/Vector.png" alt="Facebook"></a>
                <a href="#"><img src="images/Vector2.png" alt="Instagram"></a>
                <a href="#"><img src="images/youtube.png" alt="YouTube"></a>
                <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
           
    </footer>
</body>
</html>
