# SAE 3 - Livrable Tchatator

## Documentation Serveur - Livrable 1 | Scooby-Team

### Table des matières
1. [Explication du programme](#1--explication-du-programme)
2. [Mise en marche du programme](#2--mise-en-marche-du-programme)
3. [Option de lancement](#3--option-de-lancement)

### 1 | Explication du programme

Le serveur **Tchatator** est un **serveur de messagerie en ligne** permettant aux utilisateurs d'échanger des messages en utilisant un **système de tokens et de commandes textuelles**.  

## **Fonctionnalités principales :**  
✅ Connexion avec une **clé API** et génération d’un **token unique**.  
✅ **Envoi et réception de messages** entre utilisateurs (membre et pro).  
✅ **Bannissement** d’un membre par un admin.  
✅ **Blocage/Déblocage** d’un membre par un professionnel ou un admin.  
✅ **Regénération des clés API** (`REGEN`).  
✅ **Historique des messages** envoyés, reçus, nonlus et lus.  
✅ **Expiration et gestion des tokens**.  
✅ **Gestion des logs** pour suivre les activités des utilisateurs.  

---

### 2 | Mise en marche du programme

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
gcc -o serveur serveur.c bdd.c config.c -I/usr/include/postgresql -lpq
```

#### Exécution
```bash
./serveur
```

---

### 3 | Option de lancement

Le programme prend en charge des **options de ligne de commande** pour modifier son comportement.  

| Option | Description |
|--------|------------|
| `-h`, `--help` | Affiche l'aide et la syntaxe des commandes. |
| `-v`, `--verbose` | Active le mode **verbeux**, affichant plus de détails dans le terminal. |

### **Exemples d'utilisation**
- **Afficher l'aide :**  
  ```bash
  ./serveur --help
  ```
- **Lancer en mode verbeux :**  
  ```bash
  ./serveur --verbose
  ```