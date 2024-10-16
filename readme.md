# Comment bien utiliser Git


## Le fonctionnement pour enregistrer notre travail

Branche master : Cette branche est la version stable de notre application. Rien ne devrait être poussé directement dans main sans avoir été testé.

Branche (nom des pages de chacuns) : Dans ces differentes branches vous enregistrez la progression de vos travaux REGULIEREMENT, pas en juste en fin de journée sinon il y aura pleins de conflits.

Branches de fonctionnalité (feature branches) : Chaque fois que quelqu'un travaille sur une fonctionnalité, il crée une nouvelle branche à partir de develop. Exemples de branches :

## Les commandes pour versionner son code correctement

```git checkout develop``` (Pour aller sur votre branche, nom de votre page)  
```git add .``` (Pour selectionner tout les fichiers que vous voulez importer)  
```git commit -m "votre commentaire"``` (Pour ajouter un commentaire à votre commit, mettez quelque chose de precis et qui a du sens pas juste "Enregistrement modif")  
```git push origin <nom_de_la_branche``` (Pour push vos modifs locales sur la branche de votre page)  

## Les bonnes pratiques

Faites des ```pull``` et des ```push``` REGULIER pour reduire au maximum les conflits et ne pas abimer le travail des autres
Résolvez les conflits localement avant de faire des ```push```.


## Enfin de travail quand on devra merge notre travail sur la branche master

Les Pull Requests permettent aux autres membres de l'équipe de relire et vérifier le code avant qu'il ne soit fusionné. Voici comment le faire :

- Créez une Pull Request (PR) depuis la branche feature vers develop.
- Demandez à un ou deux coéquipiers de relire la PR.
- Une fois validée, fusionnez-la. (En toute fin de projet)




## Tableau des différentes commandes principales pour GIT

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
