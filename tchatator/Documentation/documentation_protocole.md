# SAE 3 - Livrable Tchatator

## Documentation Protocole - Livrable 1 | Scooby-Team

### Table des matières
1. [Configuration](#1--configuration)
2. [Utilisation des commandes](#2--utilisation-des-commandes)
3. [Hiérarchie des utilisateurs](#3--hiérarchie-des-utilisateurs)
4. [Authentification](#4--authentification)
5. [Sécurité](#5--sécurité)

### 1 | Configuration

#### Fichier de configuration
- Utilisation d'un fichier de configuration "param.txt"

#### Options de lancement
- `--help (-h)` : Affiche l'aide
- `--verbose (-v)` : Active le mode verbeux

---

### 2 | Utilisation des commandes

#### BYE BYE
- **Format** : `BYE BYE`
- **Description** : Déconnexion de l'utilisateur
- **Disponible pour** : Tous les utilisateurs

#### MSG (Message)
- **Format** : `MSG`
- **Description** : Envoi d'un message à un destinataire
- **Disponible pour** : Membres et Professionnels

#### MDF (Modification)
- **Format** : `MDF`
- **Description** : Modification d'un message existant
- **Disponible pour** : Membres et Professionnels

#### HIST (Historique)
- **Format** : `HIST`
- **Types disponibles** :
  - ENVOYES : Messages envoyés
  - RECUS : Messages reçus
  - LUS : Messages lus
  - NONLUS : Messages non lus
- **Disponible pour** : Membres et Professionnels

#### SUPPR (Suppression)
- **Format** : `SUPPR`
- **Description** : Suppression d'un message
- **Disponible pour** : Membres et Professionnels

#### BLOCK/UNBLOCK
- **Format** : `BLOCK UNBLOCK`
- **Description** : Blocage/Déblocage d'un membre
- **Disponible pour** : Administrateurs et Professionnels

#### BAN/UNBAN
- **Format** : `BAN UNBAN`
- **Description** : Bannissement/Débannissement d'un membre
- **Disponible pour** : Administrateurs uniquement

#### REGEN
- **Format** : `REGEN`
- **Description** : Régénération de la clé API
- **Disponible pour** : Membres et Professionnels

---

### 3 | Hiérarchie des utilisateurs

#### Administrateur (ADMIN)
- Accès complet au système
- Peut bannir/débannir les utilisateurs
- Ne peut pas envoyer de messages

#### Professionnel (PRO)
- Peut bloquer/débloquer des membres
- Accès aux fonctionnalités de messagerie

#### Membre (MEMBRE)
- Accès basique aux fonctionnalités de messagerie
- Peut être bloqué/banni

---

### 4 | Authentification

#### Processus d'authentification
1. Connexion TCP/IP
2. Envoi de la clé API
3. Génération du token
4. Vérification du statut (banni/non banni)

#### Sécurité des connexions
- Vérification de la validité du token pour chaque opération
- Gestion des sessions avec des tokens uniques
- Déconnexion automatique en cas d'inactivité

---

### 5 | Sécurité
- Vérification de la validité du token pour chaque opération
- Gestion des sessions avec des tokens uniques
- Déconnexion automatique en cas d'inactivité