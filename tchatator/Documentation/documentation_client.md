# SAE 3 - Livrable Tchatator

## Documentation Client - Livrable 1 | Scooby-Team

### Table des matières
1. [Explication du programme](#1--explication-du-programme)
2. [Prérequis](#2--prérequis)
3. [Mise en marche du programme](#3--mise-en-marche-du-programme)
4. [Utilisation des commandes pour un membre](#4--utilisation-des-commandes-pour-un-membre)
5. [Utilisation des commandes pour un professionnel](#5--utilisation-des-commandes-pour-un-professionnel)
6. [Utilisation des commandes pour un administrateur](#6--utilisation-des-commandes-pour-un-administrateur)

### 1 | Explication du programme

Le programme agit comme un client envoyant des commandes à un serveur. Il permet aux utilisateurs de :

- Se connecter avec une clé API
- Envoyer des messages à d'autres utilisateurs
- Consulter l'historique des messages
- Supprimer un message
- Régénérer une clé API
- Afficher l'annuaire des utilisateurs
- Se déconnecter et fermer la connexion

---

### 2 | Prérequis

- Un serveur Tchatator en cours d'exécution
- Une clé API valide
- Un système Linux avec gcc installé pour la compilation

---

### 3 | Mise en marche du programme

#### Installation de la bibliothèque PostgreSQL

##### Sur Debian, Ubuntu et dérivés (ex : MX Linux)
```bash
sudo apt update
sudo apt install libpq-dev
```

##### Sur macOS (via Homebrew)
```bash
brew install postgresql
```

#### Compilation du programme
```bash
gcc client.c bdd.c fonct.c config.c -o client -I/usr/include/postgresql -lpq
```

#### Exécution
```bash
./client
```

**Note importante** : Lors de l'exécution, le programme demandera de renseigner votre clé API.  
⚠️ Après **3 tentatives échouées**, un délai d'attente sera imposé avant de pouvoir réessayer.

---

### 4 | Utilisation des commandes pour un membre

#### Menu principal
```
=== Menu Membre ===
1. Afficher l'annuaire des professionnels
2. Envoyer un message
3. Modifier un message
4. Supprimer un message
5. Historique des messages
6. Régénérer clé API
7. Déconnexion
Votre choix :
```

#### Description des fonctionnalités

##### 1. Afficher l'annuaire des professionnels
- Format d'affichage : `ID n° | Raison Sociale`

##### 2. Envoyer un message
- Informations requises :
  - ID du destinataire
  - Contenu du message

##### 3. Modifier un message
- Informations requises :
  - Identifiant du message à modifier
  - Nouveau contenu du message

##### 4. Supprimer un message
- Information requise :
  - Identifiant du message à supprimer

##### 5. Historique des messages
Menu d'historique :
```
1. Voir les messages lus
2. Voir les messages non lus
3. Voir les messages reçus (lus et non lus)
4. Voir les messages envoyés
5. Retour au menu principal
```

##### 6. Régénérer clé API
- Génère une nouvelle clé API

##### 7. Déconnexion
- Ferme proprement la session et le programme

---

### 5 | Utilisation des commandes pour un professionnel

#### Menu principal
```
=== Menu Pro ===
1. Afficher l'annuaire des membres
2. Envoyer un message
3. Modifier un message
4. Supprimer un message
5. Historique des messages
6. Régénérer clé API
7. Bloquer un membre
8. Débloquer un membre
9. Déconnexion
Votre choix :
```

#### Description des fonctionnalités

##### 1. Afficher l'annuaire des membres
- Format d'affichage : `ID n° | Nom Prénom`

##### 2. Envoyer un message
- Informations requises :
  - ID du destinataire
  - Contenu du message

##### 3. Modifier un message
- Informations requises :
  - Identifiant du message à modifier
  - Nouveau contenu du message

##### 4. Supprimer un message
- Information requise :
  - Identifiant du message à supprimer

##### 5. Historique des messages
Menu d'historique identique au menu membre

##### 6. Régénérer clé API
- Génère une nouvelle clé API

##### 7. Bloquer un membre
- Information requise :
  - ID du membre à bloquer
- Durée du blocage : 24H

##### 8. Débloquer un membre
- Information requise :
  - ID du membre à débloquer

##### 9. Déconnexion
- Ferme proprement la session et le programme

---

### 6 | Utilisation des commandes pour un administrateur

#### Menu principal
```
=== Menu Admin ===
1. Bloquer un utilisateur
2. Débloquer un utilisateur
3. Bannir un utilisateur
4. Débannir un utilisateur
5. Déconnexion
Votre choix :
```

#### Description des fonctionnalités

##### 1. Bloquer un utilisateur
- Information requise :
  - ID de l'utilisateur à bloquer

##### 2. Débloquer un utilisateur
- Information requise :
  - ID de l'utilisateur à débloquer

##### 3. Bannir un utilisateur
- Information requise :
  - ID de l'utilisateur à bannir
- Note : Le bannissement est définitif jusqu'à débannissement

##### 4. Débannir un utilisateur
- Information requise :
  - ID de l'utilisateur à débannir

##### 5. Déconnexion
- Ferme proprement la session et le programme