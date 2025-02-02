# SAE 3 - Livrable Tchatator

## Documentation des Cas d'Utilisation - Livrable 1 | Scooby-Team

### Introduction
Tchatator est un service de messagerie asynchrone développé en C utilisant des sockets. Il permet aux professionnels et aux clients d'échanger des messages sur la plateforme TripEnArvor. L'API fonctionne avec trois niveaux d'accès : client, professionnel et administrateur.

---

### Acteurs

1. **Client**  
   - Peut envoyer, modifier et supprimer ses propres messages.
   - Peut accéder à son historique de messages lus, non lus et envoyés.
   - Ne peut pas bloquer ni bannir d’autres utilisateurs.
   - Dispose d’une clé API liée à son compte utilisateur.
   - Peut regénérer sa clé API

2. **Professionnel**  
   - Peut envoyer, modifier et supprimer ses propres messages.
   - Peut bloquer un client pour une durée limitée (24h).
   - Ne peut pas bannir définitivement un client.
   - Dispose d’une clé API liée à son compte professionnel.
   - Peut regénérer sa clé API

3. **Administrateur**  
   - Possède des droits étendus pour la gestion des utilisateurs.
   - Peut bannir définitivement un client ou un professionnel du service de messagerie.
   - Peut lever un blocage mis en place par un professionnel.
   - Dispose d’une clé API stockée dans un fichier de paramétrage.

---

### Cas d'utilisation

#### 1. Authentification
##### Pré-requis
- L'utilisateur doit posséder une clé API valide.

##### Scénario principal
1. L'utilisateur entre sa clé API.
2. Le serveur vérifie la clé API.
3. Si la clé est valide :
   - Affiche le menu correspondant à son rôle (Membre, Pro, Admin).
4. Si la clé est invalide :
   - Renvoie un message d'erreur et demande de réessayer.

##### Erreurs possibles
- Clé API invalide : `Erreur : La clé API n'existe pas.`
- Trop de tentatives : `Nombre d'essais dépassé. Veuillez réessayer dans 10 secondes.`
- Erreur lors de la lecture de l'entrée : `Erreur lors de la lecture de l'entrée`
- Erreur d'envoi : `Erreur lors de l'envoi de la clé API`

---

#### 2. Envoi d'un message
##### Pré-requis
- L'utilisateur doit être connecté.

##### Scénario principal
1. L'utilisateur entre l'ID du destinataire et le message.
2. Le message est envoyé au serveur.
3. Le serveur enregistre le message en base de données.
4. Une confirmation est renvoyée à l'utilisateur.

##### Erreurs possibles
- Format incorrect du message : `Erreur lors de l'envoi du message`
- Destinataire inexistant : `ID du destinataire incorrect`
- Limite de messages atteinte : `Trop de messages envoyés`

---

#### 3. Modification d'un message
##### Pré-requis
- L'utilisateur doit être connecté.
- L'utilisateur peut modifier uniquement ses propres messages.

##### Scénario principal
1. L'utilisateur entre l'ID du message et le nouveau contenu.
2. Le serveur vérifie si l'utilisateur est bien l'auteur du message.
3. Si oui, la modification est enregistrée.
4. Une confirmation est renvoyée.

##### Erreurs possibles
- ID du message incorrect : `Erreur lors de l'envoi du message`
- L'utilisateur n'est pas l'auteur du message : `Erreur : Vous ne pouvez modifier que vos propres messages.`
- Message déjà supprimé.

---

#### 4. Suppression d'un message
##### Pré-requis
- L'utilisateur doit être connecté.
- L'utilisateur peut supprimer uniquement ses propres messages.

##### Scénario principal
1. L'utilisateur entre l'ID du message.
2. Le serveur vérifie si l'utilisateur est bien l'auteur du message.
3. Si oui, le message est marqué comme supprimé.
4. Une confirmation est renvoyée.

##### Erreurs possibles
- ID du message incorrect : `Erreur lors de l'envoi de la commande SUPPR`
- L'utilisateur n'est pas l'auteur du message.

---

#### 5. Consultation de l'historique
##### Pré-requis
- L'utilisateur doit être connecté.

##### Scénario principal
1. L'utilisateur choisit le type d'historique :
   - Messages lus
   - Messages non lus
   - Messages envoyés
   - Messages reçus
2. Le serveur renvoie la liste des messages correspondants.
3. L'utilisateur peut naviguer dans l'historique.

##### Erreurs possibles
- Aucun message correspondant : `Erreur lors de la réception de l'historique`

---

#### 6. Blocage d'un membre (Pro/Admin)
##### Pré-requis
- L'utilisateur doit être un professionnel ou un administrateur.

##### Scénario principal
1. L'utilisateur entre l'ID du client à bloquer.
2. Le serveur enregistre le blocage.
3. Le client ne peut plus envoyer de messages au professionnel.

##### Erreurs possibles
- ID incorrect.
- L'utilisateur n'a pas les permissions nécessaires.

---

#### 7. Déblocage d'un membre (Pro/Admin)
##### Pré-requis
- L'utilisateur doit être un professionnel ou un administrateur.

##### Scénario principal
1. L'utilisateur entre l'ID du client à débloquer.
2. Le serveur supprime le blocage.
3. Le client peut à nouveau envoyer des messages.

##### Erreurs possibles
- ID incorrect.
- L'utilisateur n'a pas les permissions nécessaires.

---

#### 8. Bannissement d'un membre (Admin)
##### Pré-requis
- L'utilisateur doit être un administrateur.

##### Scénario principal
1. L'utilisateur entre l'ID du membre à bannir.
2. Le serveur enregistre le bannissement.
3. Le membre ne peut plus envoyer de messages sur la plateforme.

##### Erreurs possibles
- ID incorrect.
- L'utilisateur n'a pas les permissions nécessaires.

---

#### 9. Débannissement d'un membre (Admin)
##### Pré-requis
- L'utilisateur doit être un administrateur.

##### Scénario principal
1. L'utilisateur entre l'ID du membre à débannir.
2. Le serveur supprime le bannissement.
3. Le membre retrouve son accès à la messagerie.

##### Erreurs possibles
- ID incorrect.
- L'utilisateur n'a pas les permissions nécessaires.

---

#### 10. Déconnexion
##### Pré-requis
- L'utilisateur doit être connecté.

##### Scénario principal
1. L'utilisateur sélectionne l'option de déconnexion.
2. Le serveur ferme la session.
3. L'utilisateur retourne à l'écran de connexion.

##### Erreurs possibles
- Aucun.

---

### Conclusion
Cette documentation couvre les principaux cas d'utilisation du service Tchatator. Chaque fonctionnalité est conçue pour assurer une expérience sécurisée et intuitive pour les différents types d'utilisateurs.

