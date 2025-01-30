# SAE 3 - Livrable Tchatator

## Livrable 1 | Scooby-Team | Documentation Client

### 1 | Explication du programme

Le programme agit comme un client envoyant des commandes à un serveur. Il permet aux utilisateurs de :

-   Se connecter avec une clé API.
-   Envoyer des messages à d'autres utilisateurs.
-   Consulter l'historique des messages.
-   Supprimer un message.
-   Régénérer une clé API.
-   Se déconnecter et fermer la connexion.

---

### 2 | Prérequis

Un serveur Tchatator en cours d'exécution.
Une clé API valide.
Un système Linux avec gcc installé pour la compilation.

### 3 | Mise en marche du programme

Pour commencer il faut installer une bibliothèque PostgreSQL

### **Installation selon ta distribution**

-   #### **Sur Debian, Ubuntu et dérivés (ex : MX Linux)**

    ```
    sudo apt update
    sudo apt install libpq-dev
    ```

-   #### **Sur macOS (via Homebrew)**

    ```
    brew install postgresql
    ```

Il faut compiler le programme en C avec la commande suivante :

```
gcc client.c bdd.c fonct.c config.c -o client -I/usr/include/postgresql -lpq
```

Puis l'exécuter :

```
./client
```

Lors de l'exécution, le programme demandera de renseigner votre clé API.  
**Attention** : après **3 tentatives échouées**, un délai d'attente sera imposé avant de pouvoir réessayer.

---

### 4 | Utilisation des commandes pour un membre

Une fois connecté, le menu suivant s'affiche :

```
=== Menu Membre ===
1. Envoyer un message
2. Modifier un message
3. Supprimer un message
4. Historique des messages
5. Régénérer clé API
6. Déconnexion
Votre choix :
```

#### Action selon le choix :

-   **Envoyer un message** : envoi d'un message en précisant :

    -   l'ID du destinataire
    -   le contenu du message

-   **Modifier un message** : modification du contenu d'un message envoyé en précisant :

    -   l'identifiant du message à modifier
    -   le nouveau contenu du message

-   **Supprimer un message** : suppression d'un message en précisant :

    -   l'identifiant du message à supprimer

-   **Historique des messages** : permet d'accéder à l'historique des messages via le menu suivant :

    ```
    1. Voir les messages lus
    2. Voir les messages non lus
    3. Voir les messages reçus (lus et non lus)
    4. Voir les messages envoyés
    5. Retour au menu principal
    ```

-   **Régénérer clé API** : génère une nouvelle clé API.

-   **Déconnexion** : ferme proprement la session et le programme.

### 5 | Utilisation des commandes pour un professionnel

Une fois connecté, le menu suivant s'affiche :

```
=== Menu Pro ===
1. Envoyer un message
2. Modifier un message
3. Supprimer un message
4. Historique des messages
5. Régénérer clé API
6. Bloquer un membre
7. Débloquer un membre
8. Déconnexion
Votre choix :
```

#### Action selon le choix :

-   **Envoyer un message** : envoi d'un message en précisant :

    -   l'ID du destinataire
    -   le contenu du message

-   **Modifier un message** : modification du contenu d'un message envoyé en précisant :

    -   l'identifiant du message à modifier
    -   le nouveau contenu du message

-   **Supprimer un message** : suppression d'un message envoyé en précisant :

    -   l'identifiant du message à supprimer

-   **Historique des messages** : permet d'accéder à l'historique des messages via le menu suivant :

    ```
    1. Voir les messages lus
    2. Voir les messages non lus
    3. Voir les messages reçus (lus et non lus)
    4. Voir les messages envoyés
    5. Retour au menu principal
    ```

-   **Régénérer clé API** : génère une nouvelle clé API.

-   **Bloquer un membre** : empêche un utilisateur d'envoyer des messages pendant 24H en précisant :

    -   l'ID du membre à bloquer

-   **Débloquer un membre** : rétablit l'accès aux messages pour un utilisateur bloqué en précisant :

    -   l'ID du membre à débloquer

-   **Déconnexion** : ferme proprement la session et le programme.

### 6 | Utilisation des commandes pour un administrateur

Une fois connecté, le menu suivant s'affiche :

```
=== Menu Admin ===

1. Bloquer un utilisateur
2. Débloquer un utilisateur
3. Bannir un utilisateur
4. Débannir un utilisateur
5. Déconnexion
   Votre choix :
```
#### Action selon le choix

-   **Bloquer un utilisateur :** : empêche un utilisateur d'envoyer des messages en précisant :
    -   l'ID de l'utisateur à bloquer
-   **Débloquer un utilisateur :** : rétablit l'accès aux messages pour un utilisateur bloqué en précisant :

    -   l'ID de l'utisateur à débloquer

-   **Bannir un utilisateur :** : interdit définitivement un utilisateur d'utiliser la plateforme en précisant :

    -   l'ID de l'utisateur à bannir

-   **Débannir un utilisateur :** : annule un bannissement d'un utilisateur en précisant :

    -   l'ID de l'utisateur à débannir

-   **Déconnexion :** ferme proprement la session et le programme.
