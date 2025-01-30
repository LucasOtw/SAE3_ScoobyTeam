# SAE 3 - Livrable Tchatator

## Livrable 1 | Scooby-Team | Documentation Client

### 1 | Explication du programme

- Se connecter avec une clé API.
- Envoyer des messages à d'autres utilisateurs.
- Consulter l'historique des messages.
- Supprimer un message.
- Régénérer une clé API.
- Se déconnecter et fermer la connexion.

Le programme a pour but d'agir avec comme un client qui envoie des commandes à un serveur.

---

### 2 | Mise en marche du programme

Il faut compiler le programme en c avec cette commande : 

```c
gcc client.c -o client -Wall
```

Et ensuite l'éxecuter :

```c
./client
```

Ici le programme vous demandera de renseigner votre clé API. Notez bien que au bout de 3 tentatives ratées, vous devrez attendre un certain temps.

---

### 3 | Utilisation des commandes

Voici une liste des différentes commandes : 

- ``` MSG < votre message>```
- ```SUPPR <id>```
- ```HIST```
- ```REGEN```
- ```BYE BYE```

Concernant leurs rôles : 

-```MSG < votre message>``` : permet d'envoyer un message

-```SUPPR <id>``` : permet de supprimer un message en indiquant son identifiant

-```HIST``` : permet d'accéder à l'historique des messages envoyés

-```REGEN``` : Permet de générer une nouvelle clé API

-```BYE BYE``` : Se deconnecter et ferme proprement le programme.





