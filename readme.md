# Comment bien utiliser Git

















##Tableau des différentes commandes principales pour GIT

| **Commande Git**         | **Fonctionnalité**                                           | **Options courantes**                                |
|--------------------------|-------------------------------------------------------------|------------------------------------------------------|
| `git init`               | Initialise un nouveau dépôt Git.                            |                                                      |
| `git clone <url>`        | Clone un dépôt distant.                                      |                                                      |
| `git add <fichiers>`     | Ajoute des fichiers au staging.                              | `-A` : ajoute tous les fichiers, `.` : ajoute tout dans le répertoire courant. |
| `git commit`             | Enregistre les changements dans l'historique.                | `-m "<message>"` : message de commit, `-a` : ajoute automatiquement tous les fichiers suivis. |
| `git status`             | Affiche l'état des fichiers dans le dépôt.                   |                                                      |
| `git log`                | Affiche l'historique des commits.                            | `--oneline` : affiche en une seule ligne par commit, `--graph` : affiche un graphe des commits. |
| `git branch`             | Liste les branches.                                          | `-d <branche>` : supprime une branche, `-m <ancienne> <nouvelle>` : renomme une branche. |
| `git checkout <branche>` | Change de branche.                                           | `-b <nouvelle>` : crée et change de branche, `--` : annule des changements. |
| `git merge <branche>`    | Fusionne une branche dans la branche courante.               |                                                      |
| `git rebase <branche>`   | Applique les commits de la branche courante sur la branche spécifiée. |                                                  |
| `git pull`               | Récupère et fusionne les changements d'un dépôt distant.     | `--rebase` : effectue un rebase au lieu d'une fusion. |
| `git push`               | Envoie des commits locaux vers un dépôt distant.             | `-u` : lie la branche locale à la branche distante.   |
| `git fetch`              | Récupère les changements d'un dépôt distant sans fusionner.  |                                                      |
| `git remote`             | Gère les dépôts distants.                                    | `-v` : affiche les URL des dépôts.                   |
| `git stash`              | Met de côté les changements non validés.                     | `pop` : récupère les changements, `list` : liste les stashes. |
| `git reset`              | Réinitialise l'état du dépôt.                                | `--hard` : supprime les modifications, `--soft` : garde les changements dans le staging. |
| `git revert <commit>`    | Crée un nouveau commit qui annule les modifications d'un commit précédent. |                                                  |
