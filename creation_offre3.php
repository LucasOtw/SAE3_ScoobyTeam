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
                <li><a href="#">Accueil</a></li>
                <li><a href="#" class="active">Publier</a></li>
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
            // Définir le chemin du fichier
            $file = 'coordonnees_bancaires.txt';

            // Initialiser les valeurs par défaut
            $iban = '';
            $bic = '';
            $nom = '';

            // Vérifier si le fichier existe déjà et récupérer les données
            if (file_exists($file)) {
                $fileContents = file($file, FILE_IGNORE_NEW_LINES);
                if (count($fileContents) >= 3) {
                    $nom = explode(": ", $fileContents[0])[1];
                    $iban = explode(": ", $fileContents[1])[1];
                    $bic = explode(": ", $fileContents[2])[1];
                }
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
                    // Créer le contenu à écrire dans le fichier
                    $data = "Nom: $nom\nIBAN: $iban\nBIC: $bic\n---\n";

                    // Ouvrir le fichier en mode "write" pour remplacer les données existantes
                    $fileHandle = fopen($file, 'w');

                    if ($fileHandle) {
                        // Écrire les nouvelles données dans le fichier
                        fwrite($fileHandle, $data);

                        // Fermer le fichier
                        fclose($fileHandle);

                        echo "Les informations bancaires ont été modifiées avec succès.";
                    } else {
                        echo "Impossible de modifier les informations. Veuillez réessayer.";
                    }
                } else {
                    echo "Veuillez remplir tous les champs.";
                }
            }
        ?>

        <h1>Publier une offre</h1>
        <h2>Ajouter une nouvelle carte</h2>

        <div class="form_carte">
            <form>
                <!-- Numero -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Numéro de compte *</legend>
                            <input type="text" id="numero" name="numero" placeholder="Numéro de compte *" required>
                        </fieldset>
                    </div>
                </div>

                <!-- BIC/IBAN -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>BIC/IBAN *</legend>
                            <input type="text" id="BIC/IBAN" name="BIC/IBAN" placeholder="BIC/IBAN *" required>
                        </fieldset>
                    </div>
                </div>

                <!-- Nom -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <legend>Nom du compte *</legend>
                            <input type="text" id="nom" name="nom" placeholder="Nom du compte *" required>
                        </fieldset>
                    </div>
                </div>

                <!-- Pays -->
                <div class="row">
                    <div class="col">
                        <fieldset>
                            <select id="country">
                                <option value="fr">France</option>
                                <option value="us">Etats-Unis</option>
                                <option value="au">Australie</option>
                                <option value="ch">Chine</option>
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="checkbox">
                    <input type="checkbox" id="save-info" checked>
                    <label for="save-info">Sauvegarder mes informations</label>
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
