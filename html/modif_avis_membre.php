<?php
ob_start(); // bufferisation, ça devrait marcher ?
session_start();

require_once('recupInfosCompte.php');

if (!isset($_SESSION['membre'])) {
    header('location: connexion_membre.php');
    exit;
}

// Vérifie si le formulaire a été soumis    
require_once __DIR__ . ("/../.security/config.php");

// Créer une instance PDO avec les bons paramètres
$dbh = new PDO($dsn, $username, $password);


/*
echo "<pre>";
   var_dump($image_offre);
echo "</pre>";
echo "<pre>";
var_dump($compte);
echo "</pre>";
*/


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['unAvis'])) {
        // echo "<pre>";
        // var_dump(unserialize($_POST['unAvis']));
        // echo "</pre>";

        $avis = unserialize($_POST['unAvis']);
        $_SESSION['modif_avis'] = $avis;
        $details_offre = $_SESSION['modif_avis'];

        $stmt = $dbh->prepare('SELECT url_image FROM tripenarvor._son_image natural join tripenarvor._image WHERE code_offre = :code_offre;');
    $stmt->execute([':code_offre' => $details_offre["code_offre"]]);
    $image_offre = $stmt->fetch(PDO::FETCH_NUM);
    }
    if (isset($_POST['modifier'])) {
        echo "test";
        $modifOk = false;
        if ($_POST['isAnswer'] == 0) {
            if ((isset($_POST['note']) && !empty($_POST['note'])) && (isset($_POST['textAreaAvis']) && !empty($_POST['textAreaAvis']))) {
                $modifOk = true;
            }
        } else { // alors c'est une réponse
            if (isset($_POST['textAreaAvis']) && !empty($_POST['textAreaAvis'])) {
                $modifOk = true;
            }
        }

        if ($modifOk === true) {
            // si les modifications sont ok

            $champsModifies = array();
            $params = array();

            $n_texteAvis = trim($_POST['textAreaAvis']);
            $n_note = (int) $_POST['note'];
            $code_avis = (int) $_POST['code_avis'];


            $modifications = [];
            $params = [];

            if ($n_note !== $_SESSION['modif_avis']['note']) {
                $modifications[] = "note = :note";
                $params[':note'] = $n_note;
            }

            if ($n_texteAvis !== trim($_SESSION['modif_avis']['txt_avis'])) {
                $modifications[] = "txt_avis = :texteAvis";
                $params[':texteAvis'] = $n_texteAvis;
            }

            if (!empty($modifications)) {
                $sql = "UPDATE tripenarvor._avis SET " . implode(', ', $modifications) . " WHERE code_avis = :codeAvis";
                $params[':codeAvis'] = $code_avis;

                $stmt = $dbh->prepare($sql);
                $stmt->execute($params);
            }

            header('location: detail_offre.php');
            exit;
        } else {
            ?>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>

                $(document).ready(function () {
                    function afficherPOPup() {
                        $("#customConfirmBox").fadeIn();
                    }
                    // Fermer la popup quand l'utilisateur clique sur "Fermer"
                    $('#confirmButton').on('click', function () {
                        $('#customConfirmBox').fadeOut();
                    });
                    afficherPOPup();
                });

            </script>
            <?php
        }
    }
}
if (isset($_SESSION['modif_avis'])) {
    $avis = $_SESSION['modif_avis'];
}

$note = $avis['note'];

// On vérifie si cet avis est une réponse

$isAnswer = $dbh->prepare('SELECT COUNT(*) FROM tripenarvor._reponse
WHERE code_reponse = :code_rep');
$isAnswer->bindValue(':code_rep', $avis['code_avis']);
$isAnswer->execute();

$isAnswer = $isAnswer->fetchColumn();

?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logoPin_vert.png" width="16px" height="32px">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un avis</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
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
                <li><a href="consulter_compte_membre.php" class="active">Mon Compte</a></li>
            </ul>
        </nav>
    </header>


    <header class="header-tel header_membre">
        <div class="logo-tel">
            <a href="voir_offres.php"><img src="images/logoNoirVert.png" alt="PACT Logo"></a>
        </div>
    </header>
    <main class="main_poster_avis">
        <div class="poster_un_avis_container">
            <div class="poster_un_avis_back_button">
                <form id="back_button" action="detail_offre.php" method="POST">
                    <input type="hidden" name="uneOffre"
                        value="<?php echo htmlspecialchars(serialize($details_offre)); ?>">
                    <a href="detail_offre.php"><img src="images/Bouton_retour.png" class="back-button"></a>
                </form>
                <h1 class="titre_poster_un_avis_format_tel">Modifier un avis</h1>
            </div>
            <h1 class="poster_un_avis_titre">Récapitulatif</h1>
            <div class="poster_un_avis_recap_card">
                <div class="poster_un_avis_info">
                    <h2 class="poster_un_avis_nom">
                        <?php echo $details_offre["titre_offre"]; ?><!-- - <?php // echo $details_offre["titre_offre"]; ?>-->
                    </h2>
                    <p class="poster_un_avis_location">📍 <?php echo $details_offre["ville"]; ?>,
                        <?php echo $details_offre["code_postal"]; ?>
                    </p>
                    <form id="form-voir-offre" action="detail_offre.php" method="POST">
                        <input type="hidden" name="uneOffre"
                            value="<?php echo htmlspecialchars(serialize($details_offre)); ?>">
                        <input id="btn-voir-offre" class="poster_un_avis_btn_offre" type="submit" name="vueDetails"
                            value="Voir l'offre &#10132;">
                    </form>
                </div>
                <div class="poster_un_avis_images">
                    <img src="<?php echo $image_offre[0]; ?>" alt="" class="poster_un_avis_image">
                </div>
            </div>

            <!-- Popup de confirmation -->
            <div class="custom-confirm" id="customConfirmBox">
                <div class="custom-confirm-content">
                    <p>Vous ne pouvez pas poster d'avis sans notation ou de message !</p>
                    <button id="confirmButton">Fermer</button>
                </div>
            </div>

            <form id="avisForm" action="#" method="POST">
                <div class="poster_un_avis_section">
                    <h2 class="poster_un_avis_section_titre">Votre avis</h2>

                    <textarea placeholder="Écrivez votre avis ici..." class="poster_un_avis_textarea"
                        name="textAreaAvis" id="textAreaAvis"><?php echo trim($avis['txt_avis']); ?></textarea>
                    <p class="message-erreur avis-vide">Vous devez remplir ce champ</p>
                    <p class="message-erreur avis-trop-long">L'avis ne doit pas dépasser 500 caractères.</p>
                    <?php
                    if ($isAnswer === 0) {
                        ?>
                        <div class="poster_un_avis_footer">

                            <div class="poster_un_avis_note">
                                <h2 class="poster_un_avis_note_titre">Votre note</h2>

                                <fieldset class="notation">
                                    <input type="radio" id="star5" name="note" value="5" />
                                    <label for="star5" title="5 étoiles"></label>

                                    <input type="radio" id="star4" name="note" value="4" />
                                    <label for="star4" title="4 étoiles"></label>

                                    <input type="radio" id="star3" name="note" value="3" />
                                    <label for="star3" title="3 étoiles"></label>

                                    <input type="radio" id="star2" name="note" value="2" />
                                    <label for="star2" title="2 étoiles"></label>

                                    <input type="radio" id="star1" name="note" value="1" />
                                    <label for="star1" title="1 étoile"></label>
                                </fieldset>
                                <p class="message-erreur pas-de-note">Veuillez sélectionner une note valide.</p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <p class="poster_un_avis_disclaimer">En publiant votre avis, vous acceptez les conditions générales
                        d'utilisation (CGU).</p>
                    <div class="poster_un_avis_buttons">
                        <!--<button class="poster_un_avis_btn_annuler">Annuler</button>-->
                        <input type="hidden" name="code_avis" value="<?php echo $avis['code_avis']; ?>">
                        <input type="hidden" name="isAnswer" value="<?php echo $isAnswer; ?>">
                        <input type="hidden" name="modifier" value="1">
                        <button id="envoiModif" class="poster_un_avis_btn_publier" type="submit">Modifier →</button>
                    </div>
                </div>
        </div>
        </div>
        </form>
        <div id="customModal" class="custom-modal">
            <div class="custom-confirm-content">
                <p class="texte-boite-perso">Votre avis a été modifié avec succès !</p>
                <button id="confirmModif" class="confirm-btn-avis">Ok</button>
            </div>
        </div>

        <nav class="nav-bar">
            <a href="voir_offres.php"><img src="images/icones/House icon.png" alt="image de maison"></a>
            <a href="#"><img src="images/icones/Recent icon.png" alt="image d'horloge"></a>
            <a href="#"><img src="images/icones/Croix icon.png" alt="image de PLUS"></a>
            <a href="
                <?php
                if (isset($_SESSION["membre"]) || !empty($_SESSION["membre"])) {
                    echo "consulter_compte_membre.php";
                } else {
                    echo "connexion_membre.php";
                }
                ?>">
                <img src="images/icones/User icon.png" alt="image de Personne"></a>
        </nav>
    </main>

    <footer class="footer footer_membre">
        <div class="newsletter">
            <div class="newsletter-content">
                <h2>Inscrivez-vous à notre Newsletter</h2>
                <p>PACT</p>
                <p>découvrez la Bretagne !</p>
                <form class="newsletter-form" id="newsletterForm">
                    <input type="email" id="newsletterEmail" placeholder="Votre adresse mail" required>
                    <button type="submit">S'inscrire</button>
                </form>
            </div>
            <div class="newsletter-image">
                <img src="images/Boiteauxlettres.png" alt="Boîte aux lettres">
            </div>
        </div>

        <div id="newsletterConfirmBox" style="display: none;">
            <div class="popup-content">
                <p class="popup-message"></p>
                <button id="closeNewsletterPopup">Fermer</button>
            </div>
        </div>

        <div class="footer-links">
            <div class="logo">
                <img src="images/logoBlanc.png" alt="Logo PAVCT">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mentions_legales.php">Mentions Légales</a></li>
                    <li><a href="cgu.php">GGU</a></li>
                    <li><a href="cgv.php">CGV</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="voir_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <?php
                    if (isset($_SESSION["membre"]) && !empty($_SESSION["membre"])) {
                        ?>
                        <li>
                            <a href="consulter_compte_membre.php">Mon Compte</a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href="connexion_membre.php">Se connecter</a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="#">Nous Connaitre</a></li>
                    <li><a href="obtenir_aide.php">Obtenir de l'aide</a></li>
                    <li><a href="contacter_plateforme.php">Nous contacter</a></li>
                </ul>
            </div>
            <div class="link-group">
                <ul>
                    <!--<li><a href="#">Presse</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">Notre équipe</a></li>-->
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var note = <?php echo $note; ?>;
            // on récupère une étoile en fonction de la note
            const numEtoile = document.getElementById(`star${note}`);
            if (numEtoile) {
                numEtoile.toggleAttribute("checked");
            }

            var formModif = document.getElementById('avisForm');
            var modal = document.getElementById('customModal');
            var realPopup = modal.querySelector("div");
            var btnModif = document.getElementById('confirmModif');

            formModif.addEventListener('submit', function (e) {
                e.preventDefault();
                modal.style.display = 'flex';
                realPopup.style.display = "block";
            });

            confirmModif.addEventListener('click', () => {
                modal.style.display = 'none';
                realPopup.style.display = "none";
                avisForm.action = "modif_avis_membre.php";
                avisForm.submit();
            });

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                    realPopup.style.display = "none";
                }
            };
        });
    </script>
</body>

</html>