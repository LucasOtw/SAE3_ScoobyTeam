<?php

ob_start();
session_start();

include("recupInfosCompte.php");

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <title>Coordonnées bancaires</title>
    <link rel="stylesheet" href="styles.css">

</head>

<body>
    <header class="header_pro">
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
    <main class="main-modif-bancaire">
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
                <li><a href="#" class="active">Compte bancaire</a></li>
                <li><a href="modif_mdp_pro.php">Mot de passe et sécurité</a></li>
                <li><a href="consulter_mes_reponses_pro.php">Mes réponses</a></li>
            </ul>
        </section>
        </div>

        <?php
        // Détails de la connexion à la base de données
        require_once __DIR__ . ("/../.security/config.php");

        try {
            // Créer une instance PDO
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
        // Variables pour stocker les informations bancaires
        $iban = '';
        $bic = '';
        $nom = '';
        $message = '';

        if ($monComptePro["code_compte_bancaire"] != null) {
            // Préparer la requête pour récupérer les informations bancaires
            $query = "SELECT nom_compte, iban, bic FROM tripenarvor._compte_bancaire where code_compte_bancaire = :code_compte_bancaire";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':code_compte_bancaire', $monComptePro["code_compte_bancaire"]);
            $stmt->execute();

            // Vérifier s'il y a des résultats
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $nom = $row['nom_compte'];
                $iban = $row['iban'];
                $bic = $row['bic'];
            }
        }

        // Si le formulaire est soumis
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $iban = htmlspecialchars($_POST['IBAN']);
            $bic = htmlspecialchars($_POST['BIC']);
            $nom = htmlspecialchars($_POST['nom']);
            $cgu = isset($_POST['cgu']) ? true : false;

            // Vérifier que tous les champs sont remplis
            if (!empty($iban) && !empty($bic) && !empty($nom) && $cgu) {
                // Mettre à jour les informations bancaires dans la base de données
                $update_query = "UPDATE tripenarvor._compte_bancaire SET nom_compte = :nom, iban = :iban, bic = :bic WHERE code_compte_bancaire = :code_compte_bancaire";
                $stmt = $pdo->prepare($update_query);
                $stmt->bindParam(':code_compte_bancaire', $monComptePro["code_compte_bancaire"]);
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':iban', $iban);
                $stmt->bindParam(':bic', $bic);

                if ($stmt->execute()) {
                    $message = "Les informations bancaires ont été modifiées avec succès.";
                } else {
                    $message = "Impossible de modifier les informations. Veuillez réessayer.";
                }
            } else {
                $message = "Veuillez remplir tous les champs.";
            }
        }

        // Fermer la connexion
        $pdo = null;
        ?>

        <?php if ($message): ?>
            <div class="alert" id="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="#" method="POST">
            <h3>Modifiez vos coordonnées bancaires</h3>
            <div class="form-image-container">
                <div class="form-section">
                    <div class="IBAN">
                        <fieldset>
                            <legend>IBAN *</legend>
                            <input type="text" id="IBAN" name="IBAN" value="<?php echo $iban; ?>" placeholder="IBAN *"
                                required>
                        </fieldset>
                    </div>
                    <div class="BIC">
                        <fieldset>
                            <legend>BIC *</legend>
                            <input type="text" id="BIC" name="BIC" value="<?php echo $bic; ?>" placeholder="BIC *"
                                required>
                        </fieldset>
                    </div>
                    <div class="nom-du-proprietaire">
                        <fieldset>
                            <legend>Nom *</legend>
                            <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>" placeholder="Nom *"
                                required>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="checkbox">
                <input type="checkbox" id="cgu" name="cgu" required>
                <label for="cgu">J’accepte les <a href="cgu.html">Conditions générales d’utilisation (CGU)</a></label>
            </div>
            <div class="compte_membre_save_delete">
                <button type="submit" class="submit-btn2">Modifiez vos coordonnées</button>
            </div>
        </form>
    </main>
    <footer class="footer footer_pro">
        <div class="footer-links">
            <div class="logo">
                <img src="images/logo_blanc_pro.png" alt="Logo PAVCT">
            </div>
            <div class="link-group">
                <ul>
                    <li><a href="mentions_legales.html">Mentions Légales</a></li>
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
        window.onload = function () {
            var alertBox = document.getElementById('alert');
            if (alertBox) {
                alertBox.style.display = 'block';
                setTimeout(function () {
                    alertBox.style.display = 'none';
                }, 3000);
            }
        };
    </script>
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
</body>


</html>