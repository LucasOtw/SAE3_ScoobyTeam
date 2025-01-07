<?php

ob_start();
session_start();

$dsn = "pgsql:host=postgresdb;port=5432;dbname=sae;";
$username = "sae";
$password = "philly-Congo-bry4nt";

// Créer une instance PDO
$dbh = new PDO($dsn, $username, $password);

include("../recupInfosCompte.php");

if(!isset($_SESSION['aCreeUneOffre'])){
    $_SESSION['aCreeUneOffre'] = false;
}

if(!isset($_SESSION['ajoutOption'])){
    $_SESSION['ajoutOption'] = null; // cette session sert uniquement à éviter l'ajout d'une option si on l'a déjà fait
}

if(!isset($_SESSION['pro'])){
    // si on tente d'accéder à la page sans être connecté à un compte pro, on
    header('location: ../connexion_pro.php');
    exit;
}

if (!isset($_POST['valider']) && !isset($_POST['valider_plus_tard'])) {
    // Vérifier si une ou plusieurs sessions nécessaires ne sont pas définies
    if (!isset($_SESSION['crea_offre']) || !isset($_SESSION['crea_offre2']) || !isset($_SESSION['crea_offre3'])) {

        // Cas où SEULEMENT $_SESSION['crea_offre3'] manque
        if (!isset($_SESSION['crea_offre3']) 
            && isset($_SESSION['crea_offre']) 
            && isset($_SESSION['crea_offre2']) {

            // Vérifier si l'utilisateur est un professionnel privé
            if (isset($monComptePro['num_siren'])) {
                // Redirection obligatoire vers la page des boosts pour un professionnel privé
                header('location: ../etape_3_boost/creation_offre_spectacle_3.php');
                exit;
            }
        } else {
            header('location: ../etape_3_boost/creation_offre_spectacle3.php');
        }
    }
}

$infosCB = null;

// on vérifie si le pro a un compte bancaire
if($monComptePro['code_compte_bancaire']){
    // si le pro a un code de compte bancaire, on récupère ses infos
    $recupInfosCB = $dbh->prepare('SELECT * FROM tripenarvor._compte_bancaire WHERE code_compte_bancaire = :code_cb');
    $recupInfosCB->bindValue(":code_cb",$monComptePro['code_compte_bancaire']);
    $recupInfosCB->execute();

    $infosCB = $recupInfosCB->fetch(PDO::FETCH_ASSOC);
}

echo "<pre>";
var_dump($_SESSION['crea_offre']);
var_dump($_SESSION['crea_offre2']);
var_dump($_SESSION['crea_offre3']);
echo "</pre>";
  
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offre - Restaurant</title>
    <link rel="stylesheet" href="creation_offre3.css?">
</head>
<body>
    <header class="header_pro">
        <div class="logo">
            <img src="../images/logo_blanc_pro.png" alt="PACT Logo">
        </div>
        <nav>
            <ul>
                <li><a href="../mes_offres.php">Accueil</a></li>
                <li><a href="#" class="active">Publier</a></li>
                <li><a href="../consulter_compte_pro.php">Mon Compte</a></li>
            </ul>
        </nav>
    </header>
    <?php
    if(isset($monComptePro['num_siren'])){
        if(isset($_SESSION['crea_offre'])){
        ?>
            <div class="fleche_retour">
                <div>
                    <a href="../etape_3_boost/creation_offre_restaurant_4.php"><img src="../images/Bouton_retour.png" alt="retour"></a>
                </div>
            </div>
        
            <main class="main-creation-offre3">
        
                <h1>Publier une offre</h1>
                <h2>Ajouter une nouvelle carte</h2>
        
                <div class="form_carte">
                    <form action="#" method="POST">
                        <!-- Numero -->
                        <div class="row">
                            <div class="col">
                                <fieldset>
                                    <legend>IBAN *</legend>
                                    <input type="text" id="IBAN" name="IBAN" value="<?php echo ($infosCB) ? $infosCB['iban'] : ""; ?>" placeholder="IBAN *">
                                </fieldset>
                            </div>
                        </div>
        
                        <!-- BIC -->
                        <div class="row">
                            <div class="col">
                                <fieldset>
                                    <legend>BIC *</legend>
                                    <input type="text" id="BIC" name="BIC" value="<?php echo ($infosCB) ? $infosCB['bic'] : ""; ?>" placeholder="BIC *">
                                </fieldset>
                            </div>
                        </div>
        
                        <!-- Nom -->
                        <div class="row">
                            <div class="col">
                                <fieldset>
                                    <legend>Nom du compte *</legend>
                                    <input type="text" id="nom" name="nom" value="<?php echo ($infosCB) ? $infosCB['nom_compte'] : ""; ?>" placeholder="Nom du compte *">
                                </fieldset>
                            </div>
                        </div>
        
                        <div class="checkbox">
                            <input type="checkbox" id="cgu" name="cgu" required>
                            <label for="cgu">J’accepte les <a href="#">Conditions générales d’utilisation (CGU)</a></label>
                        </div>
        
                        <div class="boutons">
                            <button type="submit" name="valider" class="btn-primary">Valider</button>
                            <button type="submit" name="passer_cb">Plus tard...</button>
                        </div>
                    </form>
        
                    <div class="carte">
                        <img src="../images/carte_bancaire.png" alt="carte">
                    </div>
                </div>
        
                <p class="terms">En publiant votre offre, vous acceptez les conditions générales d'utilisation (CGU).</p>
        
            </main>
        <?php
    } else {
        ?>
      <div class="button-container">
          <img src="../images/verifier.png" alt="Succès" class="success-icon">
          <h1 class="success-message">Votre offre a été créée avec succès !</h1>
          <div class="buttons">
              <a href="../mes_offres.php" class="back-link-offres">Retourner à "Mes offres"</a>
              <a href="../telecharger_facture.php" class="back-link-facture">Télécharger ma facture</a>
          </div>
     </div>




            <?php
        }
    } else {
        if(isset($_SESSION['crea_offre'])){
           ?>
            <div>
                <form action="#" method="POST">
                    <input type="submit" name="creer_offre_gratuite" value="Créer une offre">
                </form>
            </div>
           <?php
        } else {
            ?>
            <div class="button-container">
                  <img src="../images/verifier.png" alt="Succès" class="success-icon">
                  <h1 class="success-message">Votre offre a été créée avec succès !</h1>
                  <div class="buttons">
                      <a href="../mes_offres.php" class="back-link-offres">Retourner à "Mes offres"</a>
                      <a href="../telecharger_facture.php" class="back-link-facture">Télécharger ma facture</a>
                  </div>
             </div>
           <?php
        }
        ?>
        <?php
    }
    ?>
    <!-- Footer -->
    <footer class="footer_pro">   
        <div class="footer-links">
            <div class="logo">
                <img src="../images/logo_blanc_pro.png" alt="PACT Logo">
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
                    <li><a href="creation_offre1.php">Publier</a></li>
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
                <a href="#"><img src="../images/Vector.png" alt="Facebook"></a>
                <a href="#"><img src="../images/Vector2.png" alt="Instagram"></a>
                <a href="#"><img src="../images/youtube.png" alt="YouTube"></a>
                <a href="#"><img src="../images/twitter.png" alt="Twitter"></a>
            </div>
        </div>
    </footer>
</body>
</html>
