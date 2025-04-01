<?php
ob_start(); // bufferisation, ça devrait marcher ?
session_start();

include("recupInfosCompte.php");

if (!isset($_SESSION['pro'])) {
    header('location: connexion_pro.php');
    exit;
}

if (isset($_GET['deco'])) {
    if ($_GET['deco'] == true) {
        session_unset();
        session_destroy();
        header('location: voir_offres.php');
        exit;
    }
}

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
    // Récupérer les valeurs initiales (par exemple, depuis la base de données)
    $valeursInitiales = [
        'mail' => $compte['mail'],
        'telephone' => $compte['telephone'],
        'adresse_postal' => $_adresse['adresse_postal'],
        'complement_adresse' => $_adresse['complement_adresse'],
        'code_postal' => $_adresse['code_postal'],
        'ville' => $_adresse['ville'],
    ];

    // Champs modifiés
    $champsModifies = [];

    // Parcourir les données soumises
    foreach ($_POST as $champ => $valeur) {
        if (isset($valeursInitiales[$champ]) && $valeursInitiales[$champ] !== $valeur) {
            $champsModifies[$champ] = $valeur;
        }
    }

    // Mettre à jour seulement les champs modifiés
    if (!empty($champsModifies)) {
        foreach ($champsModifies as $champ => $valeur) {
            switch ($champ) {
                case 'mail':
                    $valeurSansEspaces = trim(preg_replace('/\s+/', '', trim($valeur)));
                    $query = $dbh->prepare("UPDATE tripenarvor._compte SET $champ = :valeur WHERE code_compte = :code_compte");
                    $query->execute(['valeur' => $valeurSansEspaces, 'code_compte' => $compte['code_compte']]);
                    if ($query) {
                        $_SESSION['pro']['mail'] = $valeurSansEspaces;
                    }
                    break;
                case 'telephone':
                    $query = $dbh->prepare("UPDATE tripenarvor._compte SET $champ = :valeur WHERE code_compte = :code_compte");
                    $query->execute(['valeur' => trim(preg_replace('/\s+/', '', trim($valeur))), 'code_compte' => $compte['code_compte']]);
                    if ($query) {
                        $_SESSION['pro']['telephone'] = trim(preg_replace('/\s+/', '', trim($valeur)));
                    }
                    break;

                case 'adresse_postal':
                case 'complement_adresse':
                case 'code_postal':
                case 'ville':
                    $query = $dbh->prepare("UPDATE tripenarvor._adresse SET $champ = :valeur WHERE code_adresse = :code_adresse");
                    $query->execute(['valeur' => trim($valeur), 'code_adresse' => $_adresse['code_adresse']]);
                    if ($query) {
                        $_SESSION['pro'][$champ] = $valeur;
                    }
                    break;

            }
        }
        // Ajoutez une variable de session pour indiquer que les modifications ont été effectuées
        $_SESSION['modif_success'] = true;
        include("recupInfosCompte.php");
    } else {
        // echo "Aucune modification détectée.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logoPin_orange.png" width="16px" height="32px">
    <title>Visualiser Profil</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    <style>
        .creation-success {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: 1000;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .creation-success img {
            width: 40px;
            height: 40px;
        }

        .creation-success h4 {
            margin: 0;
            color: #4CAF50;
        }
    </style>
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

    <main class="main-consulter-compte-pro">
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
                <li><a href="consulter_compte_pro.php" class="active">Informations personnelles</a></li>
                <li><a href="mes_offres.php">Mes offres</a></li>
                <?php if (isset($monComptePro['raison_sociale'])) { ?>
                    <li><a href="modif_bancaire.php">Compte bancaire</a></li>
                <?php } ?>
                <li><a href="modif_mdp_pro.php">Mot de passe et sécurité</a></li>
                <li><a href="consulter_mes_reponses_pro.php">Mes réponses</a></li>
            </ul>
        </section>
        <form action="consulter_compte_pro.php" method="POST">
            <div class="crea_pro_raison_sociale_num_siren">
                <fieldset disabled>
                    <legend>Raison Sociale</legend>
                    <input type="text" id="raison-sociale" name="raison-sociale*" placeholder="Raison Sociale*"
                        value="<?php echo $monComptePro['raison_sociale']; ?>" required>
                </fieldset>

                <?php
                if (isset($monComptePro['num_siren'])) {
                    ?>
                    <fieldset disabled>
                        <legend>N° de Siren</legend>
                        <input type="text" id="siren" name="siren" placeholder="N° de Siren"
                            value="<?php echo $monComptePro['num_siren']; ?>">
                    </fieldset>
                    <?php
                }
                ?>
            </div>
            <?php
            $api_key = '';

            try {

                // Récupération initiale de la clé API
                $stmt = $dbh->query('SELECT api_key FROM tripenarvor._professionnel LIMIT 1');
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $api_key = $result['api_key'] ?? '';

                // Vérification si le bouton de génération est cliqué
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_api_key'])) {
                    $prefix = 'P'; // Préfixe pour générer la clé
                    $stmt = $dbh->prepare('update tripenarvor._professionnel set api_key = tripenarvor.generate_api_key(:prefix) where code_compte = :code_compte');
                    $stmt->bindParam(':prefix', $prefix, PDO::PARAM_STR);
                    $stmt->bindParam(':code_compte', $_SESSION['pro']['code_compte']);
                    $stmt->execute();

                    // Récupérer la nouvelle clé générée
                }
            } catch (PDOException $e) {
                echo "Erreur de connexion ou de requête : " . $e->getMessage();
            }
            ?>
            <div class="crea_pro_raison_sociale_num_siren">
                <fieldset>
                    <legend>Email *</legend>
                    <input type="email" id="email" name="mail" placeholder="Email *"
                        value="<?php echo $compte['mail'] ?>" required>
                </fieldset>

                <fieldset>
                    <legend>Téléphone *</legend>
                    <input type="tel" id="telephone" name="telephone" placeholder="Téléphone *"
                        value=" <?php echo $compte['telephone']; ?> " required>
                </fieldset>
            </div>

            <fieldset>
                <legend>Adresse Postale *</legend>
                <input type="text" id="adresse" name="adresse_postal" placeholder="Adresse postale *"
                    value="<?php echo trim(preg_replace('/(\d{2})/', '$1 ', $_adresse['adresse_postal'])) ?>" required>
            </fieldset>

            <fieldset>
                <legend>Complément d'adresse</legend>
                <input type="text" id="complement_adresse" name="complement_adresse" placeholder="Complément d'adresse"
                    value="<?php echo $_adresse['complement_adresse']; ?>">
            </fieldset>

            <div class="crea_pro_raison_sociale_num_siren">
                <fieldset>
                    <legend>Code Postal *</legend>
                    <input type="text" id="code_postal" name="code_postal" placeholder="Code Postal*"
                        value="<?php echo $_adresse['code_postal']; ?>" required>
                </fieldset>

                <fieldset>
                    <legend>Ville *</legend>
                    <input type="text" id="ville" name="ville" placeholder="Ville *"
                        value="<?php echo $_adresse['ville']; ?>" required>
                </fieldset>
            </div>

            <div class="checkbox">
                <input type="checkbox" id="cgu" name="cgu" required>
                <label for="cgu">J’accepte les <a href="cgu.php">Conditions générales d’utilisation (CGU)</a></label>
            </div>
            <div class="compte_pro_save_delete_remove">
                <button type="button" name="suppr-compte" class="btn-suppr-compte" id="btn-suppr-compte">Supprimer le
                    compte</button>

                <div class="compte_pro_save_delete">
                    <a href="?deco=true" class="submit-btn1">Déconnexion</a>
                    <button type="submit" name="modif_infos" class="submit-btn3">Enregistrer</button>
                </div>
            </div>
        </form>
        <div class="creation-success" id="creation-success" style="display: none;">
            <img src="images/verifier.png" alt="Succès">
            <h4>Les informations ont été mises à jour avec succès !</h4>
        </div>
    </main>
    <footer class="footer footer_pro">
        <div class="footer-links">
            <div class="logo">
                <img src="images/logo_blanc_pro.png" alt="Logo PACT">
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

        ////////////////////////////////////////////////////////////////////////////////////
        ///                            Supprimer compte                                  ///
        ////////////////////////////////////////////////////////////////////////////////////

        document.getElementById('btn-suppr-compte').addEventListener('click', function () {
            const confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce compte ?");
            if (confirmation) {
                const compteId = <?php echo json_encode($compte['code_compte']); ?>;

                fetch('/supprimer_compte.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: compteId })
                })
                    .then(response => response.json()) // Parse la réponse JSON
                    .then(data => {
                        if (data.success) {
                            alert('Compte supprimé avec succès.');
                            window.location.href = '/consulter_compte_pro.php?deco=true'; // Redirection côté client
                        } else {
                            alert(data.message || 'Erreur lors de la suppression du compte.');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur réseau ou serveur :', error);
                        alert('Impossible de supprimer le compte.');
                    });
            }
        });

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
    <script>
        // Lorsque le message est envoyé avec succès, afficher le message de succès
        function afficherMessageSucces() {
            // Trouver l'élément qui contient le message de succès
            const successMessage = document.getElementById('creation-success');
            
            <?php if(isset($_SESSION['modif_success']) && $_SESSION['modif_success'] === true): ?>
            // Afficher le message
            successMessage.style.display = 'block';
            
            // Le message disparaît après 5 secondes
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 5000);
            <?php 
            // Supprimer la variable de session pour ne pas réafficher le message après rafraîchissement
            unset($_SESSION['modif_success']);
            endif; ?>
        }

        // Appel de la fonction au chargement de la page
        document.addEventListener('DOMContentLoaded', afficherMessageSucces);
    </script>

</body>

</html>