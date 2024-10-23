<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier coordonnées bancaires</title>
    <link rel="stylesheet" href="creation_offre3.css">
</head>
<body>
    <header class="header_pro">
        <div class="logo">
            <img src="images/logo_blanc_pro.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="mes_offres.php">Accueil</a></li>
                <li><a href="creation_offre1.php" class="active">Publier</a></li>
                <li><a href="#">Mon Compte</a></li>
            </ul>
        </nav>
    </header>

    <div class="fleche_retour">
        <div>
            <img src="images/Bouton_retour.png" alt="retour">
        </div>
    </div>

    <div class="header-controls">
        <div>
            <img id="etapes" src="images/FilArianne3.png" alt="Étapes" width="80%" height="80%">
        </div>
    </div>

    <main class="main-creation-offre3">

    <?php
        // Connexion à la base de données PostgreSQL
        $dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
        $username = "sae";
        $password = "philly-Congo-bry4nt";

        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }

        // Initialiser les valeurs par défaut
        $iban = '';
        $bic = '';
        $nom = '';

        // Requête pour récupérer les informations bancaires
        $sql = "SELECT iban, bic, nom_compte FROM _compte_bancaire LIMIT 1";
        $stmt = $pdo->query($sql);
        if ($stmt && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $iban = $row['iban'];
            $bic = $row['bic'];
            $nom = $row['nom_compte'];
        }

        // Si le formulaire est soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Récupérer les données du formulaire
            $iban = htmlspecialchars($_POST['IBAN']);
            $bic = htmlspecialchars($_POST['BIC']);
            $nom = htmlspecialchars($_POST['nom']);
            $cgu = isset($_POST['cgu']) ? true : false;

            // Vérifier que tous les champs sont bien remplis
            if (!empty($iban) && !empty($bic) && !empty($nom) && $cgu) {
                // Mise à jour des informations dans la base de données
                $sql = "UPDATE _compte_bancaire SET iban = :iban, bic = :bic, nom_compte = :nom_compte WHERE code_compte_bancaire = (SELECT code_compte_bancaire FROM _compte_bancaire LIMIT 1)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['iban' => $iban, 'bic' => $bic, 'nom_compte' => $nom]);

                echo "Les informations bancaires ont été modifiées avec succès.";
            } else {
                echo "Veuillez remplir tous les champs.";
            }
        }
        ?>

        <h1>Publier une offre</h1>
        <h2>Ajouter une nouvelle carte</h2>

        <div class="form_carte">
            <form action="#" method="POST">
                <!-- Numero -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>IBAN *</legend>
                            <input type="text" id="IBAN" name="IBAN" value="<?php echo $iban; ?>" placeholder="IBAN *" required>
                        </fieldset>
                    </div>
                </div>

                <!-- BIC -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>BIC *</legend>
                            <input type="text" id="BIC" name="BIC" value="<?php echo $bic; ?>" placeholder="BIC *" required>
                        </fieldset>
                    </div>
                </div>

                <!-- Nom -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Nom du compte *</legend>
                            <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>" placeholder="Nom du compte *" required>
                        </fieldset>
                    </div>
                </div>

                <div class="checkbox">
                    <input type="checkbox" id="cgu" name="cgu" required>
                    <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
                </div>

                <div class="boutons">
                    <button type="submit" class="btn-primary">Valider</button>
                    <button class="btn-secondary">Je l'ajouterai plus tard
                        <img src="images/fleche_droite.png" alt="fleche_droite">
                    </button>
                </div>
            </form>

            <div class="carte">
                <img src="images/carte_bancaire.png" alt="carte">
            </div>
        </div>

        <p class="terms">En publiant votre offre, vous acceptez les conditions générales d'utilisation (CGU).</p>

    </main>
    <!-- Footer -->
    <footer class="footer_pro">   
        <div class="footer-links">
            <div class="logo">
                <img src="images/logo_blanc_pro.png" alt="PACT Logo">
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
                    <li><a href="#">Accueil</a></li>
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
        </div>

        <div class="footer-bottom">
            <div class="social-icons">
                <a href="#"><img src="images/Vector.png" alt="Facebook"></a>
                <a href="#"><img src="images/Vector2.png" alt="Instagram"></a>
                <a href="#"><img src="images/youtube.png" alt="YouTube"></a>
                <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
            </div>
        </div>
    </footer>
</body>
</html>
