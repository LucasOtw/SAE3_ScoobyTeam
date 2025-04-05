<?php
ob_start(); 
session_start();

require __DIR__ . '/../vendor/autoload.php';

use OTPHP\TOTP;

require_once("recupInfosCompte.php");

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('location: connexion_pro.php');
    exit;
}

if (!isset($_SESSION['pro'])) {
    header('location: connexion_pro.php');
    exit;
}

// Vérification si le bouton de génération est cliqué
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_api_key'])) {
    $prefix = 'M'; // Préfixe pour générer la clé
    try {
        $stmt = $dbh->prepare('update tripenarvor._professionnel set api_key = tripenarvor.generate_api_key(:prefix) where code_compte = :code_compte');
        $stmt->bindParam(':prefix', $prefix, PDO::PARAM_STR);
        $stmt->bindParam(':code_compte', $_SESSION['pro']['code_compte']);
        $stmt->execute();
    } catch (PDOException $Exception) {
        throw new PDOException($Exception->getMessage(), (int) $Exception->getCode());
    }

    header('location: modif_mdp_pro.php');
    exit;
}

$modif_mdp = null;

function tempsEcouleDepuisNotif($avis)
{
    // date d'aujourd'hui
    $date_actuelle = new DateTime();
    // conversion de la date de publication en objet DateTime
    $date_ajout_avis = new DateTime($avis['date_notif']);
    $date_ajout_avis = new DateTime($date_ajout_avis->format('Y-m-d'));
    // calcul de la différence en jours
    $diff = $date_ajout_avis->diff($date_actuelle);
    // récupération des différentes unités de temps
    $jours = $diff->days; // total des jours de différence
    $mois = $diff->m + ($diff->y * 12); // mois totaux
    $annees = $diff->y;

    $retour = null;

    // calcul du nombre de jours dans le mois précédent
    $date_mois_precedent = clone $date_actuelle;
    $date_mois_precedent->modify('-1 month');
    $jours_dans_mois_precedent = (int) $date_mois_precedent->format('t'); // 't' donne le nombre de jours dans le mois

    if ($jours == 0) {
        $retour = "Aujourd'hui";
    } elseif ($jours == 1) {
        $retour = "Hier";
    } elseif ($jours > 1 && $jours < 7) {
        $retour = $jours . " jour(s)";
    } elseif ($jours >= 7 && $jours < $jours_dans_mois_precedent) {
        $semaines = floor($jours / 7);
        $retour = $semaines . " semaine(s)";
    } elseif ($mois < 12) {
        $retour = $mois . " mois";
    } else {
        $retour = $annees . " an(s)";
    }

    return $retour;
}

if (isset($_POST['modif_infos'])) {

    // Champs modifiés
    $champsModifies = [];

    // Parcourir les données soumises
    foreach ($_POST as $champ => $valeur) {
        if ($champ != 'modif_infos') {
            $champsModifies[$champ] = trim($valeur);
        }
    }

   // Mettre à jour seulement les champs modifiés
   if (!empty($champsModifies))
   {
      if (password_verify($champsModifies['mdp_actuel'],$compte['mdp']))
      {
         if (trim($champsModifies['mdp_nv1']) === trim($champsModifies['mdp_nv2']))
         {
            $mdp_modif = password_hash($champsModifies['mdp_nv1'], PASSWORD_DEFAULT);
            $query = $dbh->prepare("UPDATE tripenarvor._compte SET mdp = :valeur WHERE code_compte = :code_compte");
            $query->bindValue(":valeur",$mdp_modif);
            $query->bindValue(":code_compte",$compte['code_compte']);
            $query->execute();
                
            $rowsAffected = $query->rowCount();
            if ($rowsAffected > 0) {
               $modif_mdp = true;
               $_SESSION['pro']['mdp'] = $mdp_modif;
            } else {
               $modif_mdp = false;
            }

         } else {
            $modif_mdp = false;
         }
      } else {
         echo "Test";
         $modif_mdp = false;
      }
       // echo "Les informations ont été mises à jour.";
       include("recupInfosCompte.php");
   } else {
       echo "Aucune modification détectée.";
   }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <title>Modifier mot de passe</title>
    <link rel="stylesheet" href="styles.css?">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
</head>

<body>

    <header class="header-pc header_pro">
        <div class="logo">
            <a href="mes_offres.php">
                <img src="images/logo_blanc_pro.png" alt="PACT Logo">
            </a>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="mes_offres.php">Accueil</a></li>
                <li><a href="creation_offre.php">Publier</a></li>
                <li><a href="consulter_compte_pro.php" class="active">Mon Compte</a></li>
                <li>
                    <a href="#" class="notification-icon" id="notification-btn">
                        <img src="images/notif.png" alt="cloche notification" class="nouvelle-image"
                            style="margin-top: -5px;">
                        <span id="notification-badge" style="display:none"></span>
                    </a>
                    <div id="notification-popup">
                        <ul>
                            <?php
                            ///////////////////////////////////////////////////////////////////////////////
                            ///                            Contenu notif                                ///
                            ///////////////////////////////////////////////////////////////////////////////
                            
                            require_once __DIR__ . ("/../.security/config.php");

                            // Créer une instance PDO
                            $dbh = new PDO($dsn, $username, $password);

                            $toutes_les_notifs = $dbh->prepare('select * from tripenarvor._notification 
                                                                natural join tripenarvor._avis 
                                                                natural join tripenarvor._offre 
                                                                where professionnel = :code_compte 
                                                                order by date_notif desc;');
                            $toutes_les_notifs->bindValue(":code_compte", $monComptePro["code_compte"]);
                            $toutes_les_notifs->execute();
                            $notifs = $toutes_les_notifs->fetchAll(PDO::FETCH_ASSOC);

                            $nb_notif = 0;

                            // echo "<pre>";
                            // var_dump($notifs);
                            // echo "</pre>";
                            
                            if (empty($notifs)) {
                                echo "<p> Aucun avis n'a été posté sur vos offres </p>";
                            } else {
                                foreach ($notifs as $index => $notif) {
                                    if (!$notif["consulter_notif"]) {
                                        echo '<script language="javascript">
                                            const notificationBadge = document.getElementById("notification-badge");
                                            notificationBadge.style.removeProperty("display");
                                          </script>';
                                    }

                                    $checkPP = $dbh->prepare("SELECT url_image FROM tripenarvor._sa_pp WHERE code_compte = :code_compte");
                                    $checkPP->bindValue(":code_compte", $notif['code_compte']);
                                    $checkPP->execute();

                                    $photo_profil = $checkPP->fetch(PDO::FETCH_ASSOC);
                                    if ($photo_profil) {
                                        $compte_pp = $photo_profil['url_image'];
                                    } else {
                                        $compte_pp = "images/icones/icone_compte.png";
                                    }

                                    $infoCompte = $dbh->prepare('select * from tripenarvor._compte natural join tripenarvor._membre where code_compte= :code_compte;');
                                    $infoCompte->bindValue(':code_compte', $notif['code_compte']);
                                    $infoCompte->execute();
                                    $compte_m = $infoCompte->fetch(PDO::FETCH_ASSOC);

                                    if (!empty($compte_m['prenom']) && !empty($compte_m['nom'])) {
                                        // Si c'est un membre classique
                                        $prenom = $compte_m['prenom'];
                                        $nom = $compte_m['nom'];
                                    } else {
                                        // Si l'utilisateur est supprimé
                                        $prenom = "Utilisateur";
                                        $nom = "supprimé";
                                    }

                                    if ($nb_notif < 5) {
                                        ?>

                                        <li class="notif" data-consult="<?php echo $notif["consulter_notif"]; ?>"
                                            data-avis="<?php echo $notif["code_avis"]; ?>"
                                            onclick="this.querySelector('.offer-form-notif').submit();" style="cursor: pointer;">

                                            <img src="<?php echo $compte_pp; ?>" alt="photo de profil" class="profile-img">
                                            <div class="notification-content">
                                                <strong><?php echo $prenom . ' ' . $nom; ?></strong>
                                                <span class="notification-location"> | <?php echo $notif["titre_offre"]; ?></span>
                                                <p><?php echo $notif["txt_avis"]; ?></p>
                                                <span class="notification-time"><?php echo tempsEcouleDepuisNotif($notif); ?></span>
                                                <span class="new-notif-dot" style="display:none"></span>
                                            </div>
                                            <?php
                                            foreach ($mesOffres as $offre) {
                                                if ($offre['code_offre'] == $notif["code_offre"]) {
                                                    $monOffre = $offre;
                                                    break;
                                                }
                                            }
                                            ?>
                                            <form action="detail_offre_pro.php" method="POST" class="offer-form-notif">
                                                <input type="hidden" id="valueOffre" name="uneOffre"
                                                    value="<?php echo htmlspecialchars(serialize($offre)); ?>">
                                                <input type="hidden" name="vueDetails" value="1">
                                            </form>
                                        </li>

                                        <?php
                                    }
                                    $nb_notif++;
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </li>
            </ul>
            </div>
            </li>
    </header>
    <main class="main_modif_mdp_pro">
        <section class="profile">
            <div class="banner">
                <img src="images/Fond.png" alt="Bannière de profil">
            </div>
            <div class="profile-info">
                <img class="profile-picture" src="images/hotel.jpg" alt="Profil utilisateur">
                <h1><?php echo $monComptePro['raison_sociale']; ?></h1>
                <p><?php echo $compte['mail'] . " | " . $compte['telephone']; ?></p>
            </div>
        </section>

        <section class="tabs">
            <ul>
                <li><a href="consulter_compte_pro.php">Informations personnelles</a></li>
                <li><a href="mes_offres.php">Mes offres</a></li>
                <?php if (isset($monComptePro['raison_sociale'])) { ?>
                    <li><a href="modif_bancaire.php">Compte bancaire</a></li>
                <?php } ?>
                <li><a href="#" class="active">Mot de passe et sécurité</a></li>
                <li><a href="consulter_mes_reponses_pro.php">Mes réponses</a></li>
            </ul>
        </section>
        <!-- Ajouter ceci juste avant votre popup -->
        <div class="popup-backdrop" id="popup-backdrop"></div>

        <div class="popup-2fa-validation">
            <p class="texte-boite-perso">Voulez-vous vraiment activer l'authentification à 2 facteurs ?</p>
            <p>Cette action est irréversible !</p>
            <span>
                <button id="confirm">Ok</button>
                <button id="cancel">Annuler</button>
            </span>
        </div>
        <form action="modif_mdp_pro.php" method="POST">
            <h3>Modifiez votre mot de passe</h3>

            <fieldset>
                <legend>Entrez votre mot de passe actuel *</legend>
                <input type="password" id="mdp_actuel" name="mdp_actuel"
                    placeholder="Entrez votre mot de passe actuel *" required>
            </fieldset>

            <fieldset>
                <legend>Définissez votre nouveau mot de passe *</legend>
                <input type="password" id="mdp_nv1" name="mdp_nv1" placeholder="Definissez votre nouveau mot de passe *"
                    required>
            </fieldset>

            <fieldset>
                <legend>Confirmez votre nouveau mot de passe *</legend>
                <input type="password" id="mdp_nv2" name="mdp_nv2" placeholder="Confirmez votre nouveau mot de passe *"
                    required>
            </fieldset>

            <div class="compte_membre_save_delete">
                <button type="submit" name="modif_infos" class="submit-btn2">Enregistrer</button>
            </div>
        </form>
        <form id="form2FA" action="#" method="POST">
            <h3>Authentification à deux facteurs</h3>

            <div class="two-fa-container">
                <!-- Colonne gauche -->
                <div class="left-2fa">
                    <div class="connexion_membre_2fa">
                        <button type="submit" id="enable2FABtn" class="btn-2fa_pro"
                            <?php echo (isset($isActivated2FA) && $isActivated2FA) ? "disabled" : "" ?>>
                            Authentification à deux facteurs
                        </button>

                        <input type="hidden" name="code_compte" value="<?php echo $compte['code_compte']; ?>">
                        <input type="hidden" name="active2FA" value="1">

                        <div class="info-icon-container">
                            <span class="info-icon2">?</span>
                            <div class="tooltip-content">
                                L'authentification à deux facteurs ajoute une couche de sécurité supplémentaire en exigeant une vérification via un code envoyé sur votre téléphone.
                            </div>
                        </div>

                        <p id="phrase" class="info_2fa" style="display: none;">
                            ⚠️ Une fois activée, cette option est <strong>irréversible</strong>.
                        </p>
                    </div>

                    <p id="etat_2fa" class="etat_2fa">
                        <?php if (isset($isActivated2FA) && $isActivated2FA): ?>
                            L'authentification à deux facteurs est <span class="statut-non_pro">activée</span>.
                        <?php else: ?>
                            Pour le moment, l'authentification à deux facteurs est <span class="statut-non_pro">désactivée</span>.
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Colonne droite : QR Code si activé -->
                <?php if (isset($isActivated2FA) && $isActivated2FA): ?>
                    <?php
                        $otp = TOTP::create($isActivated2FA['code_secret']);
                        $otp->setLabel("Scooby-Team");
                        $otp_uri = $otp->getProvisioningUri();
                    ?>
                    <div class="right-2fa">
                        <h3>Votre QR Code</h3>
                        <p>Scannez ce QR Code avec <a class="g_auth_link" href="https://apps.apple.com/fr/app/google-authenticator/id388497605">Google Authenticator</a> ou une autre app compatible.</p>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo urlencode($otp_uri) ?>&size=200x200" alt="QR Code OTP">
                    </div>
                <?php endif; ?>
            </div>
        </form>
        <?php
            if(isset($isActivated2FA) && $isActivated2FA){
                // On vérifie si le code OTP est actif ou non
                $checkIsActiveOTP = $dbh->prepare("SELECT is_active FROM tripenarvor._compte_otp
                WHERE code_compte = :code_compte");
                $checkIsActiveOTP->bindValue(":code_compte",$_SESSION['pro']['code_compte']);
                $checkIsActiveOTP->execute();

                $isActiveOTP = $checkIsActiveOTP->fetchColumn();

                if(!$isActiveOTP){
                    ?>
                    <form id="form-verif-otp" action="#" method="POST">
                        <label for="otpCode">Code OTP</label>
                        <input type="text" name="code_otp" id="otpCode" placeholder="Code à 6 chiffres" maxlength="6">
                        <input id="submit-otp" type="submit" value="Vérifier le code">
                        <p id="errorMsg" style="color: red; display: none;margin-top: 16px;">Le code doit contenir exactement 6 chiffres.</p>
                    </form>
                    <?php
                }
            }
        ?>
        <form action="#" method="POST">
            <fieldset id="api">
                <p>Clé API</p>
                <input disabled type="text" id="cle_api_pro" name="cle_api"
                    value="<?php echo htmlspecialchars($monComptePro['api_key']); ?>" readonly>
                <input type="submit" id="btn-api-pro" name="generate_api_key" value="" alt="Regénérer la clé API"
                    formnovalidate>
            </fieldset>

        </form>

        <?php

        if ($modif_mdp !== null) {
            if ($modif_mdp == true) {
                $img_success = "images/verifier.png";
                $msg_modif = "Mot de passe modifié avec succès&nbsp!";
            } else {
                $img_success = "images/erreur.png";
                $msg_modif = "Erreur lors du changement du mot de passe&nbsp!";
            }
            ?>
            <div class="modif-mdp-success-pro" id="modif_mdp_pro">
                <img src="<?php echo $img_success ?>" alt="Succès">
                <h2><?php echo $msg_modif; ?></h2>
            </div>
            <?php
        }
        ?>


    </main>


    <footer class="footer footer_pro">
        <div class="footer-links">
            <div class="logo">
                <img src="images/logo_blanc_pro.png" alt="Logo PAVCT">
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
                    <li><a href="mes_offres.php">Accueil</a></li>
                    <li><a href="connexion_pro.php">Publier</a></li>
                    <?php
                    if (isset($_SESSION["pro"]) && !empty($_SESSION["pro"])) {
                        ?>
                        <li>
                            <a href="consulter_compte_pro.php">Mon Compte</a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href="connexion_pro.php">Se connecter</a>
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
        document.addEventListener('DOMContentLoaded', () => {
            const notificationBtn = document.getElementById('notification-btn');
            const notificationPopup = document.getElementById('notification-popup');
            const notificationBadge = document.getElementById('notification-badge');

            // Ajouter la classe hidden pour masquer le pop-up au démarrage
            notificationPopup.classList.add('hidden');

            // S'assurer que notifItems contient des éléments
            const gererNotifications = () => {
                const notifItems = document.querySelectorAll(".notif");

                if (notifItems.length > 0) {
                    console.log("Notification trouvée");
                    notifItems.forEach(notif => {
                        const consulter = notif.getAttribute('data-consult') ? 1 : 0;
                        const newNotifDot = notif.querySelector('.new-notif-dot');
                        const avis = notif.getAttribute('data-avis');

                        console.log("///Consultée : ", consulter);
                        console.log("///Avis : ", avis);

                        if (consulter == 0) {
                            newNotifDot.style.removeProperty('display');

                            // Utilisation de fetch pour envoyer les données
                            fetch("https://scooby-team.ventsdouest.dev/update_notif_status.php", {
                                method: "POST",  // Méthode POST
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded",  // Spécifie le type de contenu
                                },
                                body: new URLSearchParams({
                                    code_avis: avis
                                })
                            })

                                .then(response => {
                                    if (response.ok) {
                                        console.log("L'état de la notif a été mis à jour avec succès.");
                                        notif.setAttribute('data-consult', "1");
                                    } else {
                                        console.error("Erreur lors de la mise à jour de l'état de la notif : " + response.status);
                                    }
                                })
                                .catch(error => {
                                    console.error("Erreur de réseau ou autre :", error);
                                });

                        }
                        else {
                            newNotifDot.style.display = 'none';
                        }
                    });
                } else {
                    console.log("Aucune notification trouvée.");
                }
            };

            notificationBtn.addEventListener('click', (e) => {
                e.preventDefault(); // Empêche le comportement par défaut de l'ancre
                notificationPopup.classList.toggle('hidden');

                if (!notificationPopup.classList.contains('hidden')) {
                    // Appeler la fonction pour traiter les notifications lorsque le popup est visible
                    gererNotifications();
                }

                notificationBadge.style.display = "none";
            });

            document.addEventListener('click', (e) => {
                if (!notificationPopup.contains(e.target) && !notificationBtn.contains(e.target)) {
                    notificationPopup.classList.add('hidden');
                }
            });

        });

    </script>
    <script>
        /* CE SCRIPT SERT A LA GÉNÉRATION DU QR CODE
        ET À L'ACTIVATION DE L'AUTHENTIFICATION À DEUX FACTEURS */
        document.addEventListener('DOMContentLoaded', function(){

            const formActiveOTP = document.getElementById('form-verif-otp');
            const btnOTP = document.getElementById('submit-otp');
            const codeCompte = <?php echo json_encode($_SESSION['pro']['code_compte']); ?>;
            var errorMsg = document.getElementById('errorMsg');

            let formActive2FA = document.getElementById('form2FA');
            let dialogue2FA = document.getElementsByClassName('popup-2fa-validation')[0];
            let backdrop = document.getElementById('popup-backdrop');

            // Masquer la popup et le backdrop au chargement
            dialogue2FA.style.display = "none";
            backdrop.style.display = "none";

            console.log(dialogue2FA);
            console.log(formActive2FA);

            formActive2FA.addEventListener('submit', function(e){
                e.preventDefault();
                dialogue2FA.style.display = "block";
                backdrop.style.display = "block"; // Afficher le backdrop avec effet de flou
            });

            let confirmBtn = dialogue2FA.querySelector('#confirm');
            let cancelBtn = dialogue2FA.querySelector('#cancel');

            confirmBtn.addEventListener('click', function(){
                form2FA.action = "generation_codeOTP.php";
                form2FA.submit();
            });

            cancelBtn.addEventListener('click', function(){
                dialogue2FA.style.display = "none";
                backdrop.style.display = "none"; // Masquer le backdrop
            });

            formActiveOTP.addEventListener('submit', (e) => {
                e.preventDefault();

                fetch('verification_codeOTP.php',{
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        codeOTP: document.getElementById('otpCode').value,
                        code_compte: codeCompte
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success){
                        errorMsg.style.display = "inline";
                        errorMsg.innerHTML = "";
                        errorMsg.style.color = "green";
                        errorMsg.textContent = data.message;
                        window.location.href = "modif_mdp_pro.php";
                    } else {
                        errorMsg.style.color = "red";
                        errorMsg.innerHTML = data.message;
                        errorMsg.style.display = "inline";
                    }
                })
                .catch(error => {
                    console.error("Erreur dans le fetch :", error);
                    errorMsg.innerHTML = "Erreur de communication avec le serveur.";
                });
            })

        });
    </script>
    <script>
        $(document).ready(function() {
        // Affichage du message lorsque le bouton est cliqué
        $(".submit-btn2").click(function(e) {
            e.preventDefault();  // Empêche la soumission du formulaire si nécessaire

            // Vérifiez si le div est déjà visible, sinon l'afficher
            if ($("#modif_mdp_pro").is(":hidden")) {
                $("#modif_mdp_pro").fadeIn();
            }
        });
    });
</script>
</body>
</html>
