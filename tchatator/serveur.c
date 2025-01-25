#include <arpa/inet.h>
#include <libpq-fe.h>  // Bibliothèque PostgreSQL
#include <netinet/in.h>
#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/socket.h>
#include <sys/types.h>
#include <time.h>  // Bibliothèque PostgreSQL
#include <unistd.h>

#include "bdd.h"
#include "config.h"
#include "fonct.h"

int main() {
    int sock, cnx, ret, size, len;
    struct sockaddr_in addr, conn_addr;

    char buffer[BUFFER_SIZE], query[2000], txt_log[256];  // Buffer pour lire les données

    PGconn *conn = connect_to_db();
    if (conn == NULL) {
        return EXIT_FAILURE;  // Échec de la connexion
    }

    if (load_config("param.txt") != 0) {
        printf("\033[31m-> Erreur lors du chargement du fichier de configuration.\033[0m\n");
        snprintf(txt_log, sizeof(txt_log),
                "-> Erreur lors du chargement du fichier de configuration.");
        insert_logs(NULL, NULL, txt_log);
        PQfinish(conn);
        return -1;
    }

    if (prepare_socket(&ret, &sock, &addr) != 0) {
        printf("\033[31m-> Erreur : Impossible de configurer le socket.\033[0m\n");
        snprintf(txt_log, sizeof(txt_log),
                "-> Erreur : Impossible de configurer le socket.");
        insert_logs(NULL, NULL, txt_log);
        exit(EXIT_FAILURE);
    }

    size = sizeof(conn_addr);

    while (1)  // while(1) pour garder le serveur allume malgre que le client quitte
    {
        printf("=> En attente d'une connexion...\n");
        cnx = accept(sock, (struct sockaddr *)&conn_addr, (socklen_t *)&size);
        if (cnx == -1) {
            perror("\033[31m-> Erreur lors de accept\033[0m");
            snprintf(txt_log, sizeof(txt_log),
                "-> Erreur lors de accept");
            insert_logs(NULL, NULL, txt_log);
            PQfinish(conn);
            exit(-1);
        }

        // Récupération de l'adresse IP du client
        char client_ip[INET_ADDRSTRLEN];  // Buffer pour stocker l'adresse IP
        inet_ntop(AF_INET, &(conn_addr.sin_addr), client_ip, INET_ADDRSTRLEN);

        printf("\033[32mConnexion acceptée de l'adresse IP : %s\033[0m\n", client_ip);
        snprintf(txt_log, sizeof(txt_log),
                "Connexion acceptée de l'adresse IP : %s\n", client_ip);
        insert_logs(NULL, client_ip, txt_log);
        
        memset(buffer, 0, sizeof(buffer));

        char api_key[LEN_API + 1];  // Variable pour stocker la clé API reçue

        printf("En attente de la cle API...\n");

        // Réception de la clé API
        len = recv(cnx, api_key, sizeof(api_key) - 1, 0);
        if (len <= 0) {
            perror("\033[31m-> Erreur lors de la réception des données\033[0m");
            snprintf(txt_log, sizeof(txt_log),
                "-> Erreur lors de la réception des données");
            insert_logs(NULL, client_ip, txt_log);
            close(cnx);
            continue;
        }

        // Terminer la chaîne reçue avec un caractère nul
        api_key[len] = '\0';

        // Nettoyer la clé API en supprimant les retours à la ligne et les retours chariot
        api_key[strcspn(api_key, "\r\n")] = '\0';

        // Vérification de la validité de la clé API (facultatif)
        if (strlen(api_key) == 0) {
            printf("\033[31m-> Erreur : clé API vide après nettoyage.\033[0m\n");
            snprintf(txt_log, sizeof(txt_log),
                "-> Erreur : clé API vide après nettoyage.");
            insert_logs(api_key, client_ip, txt_log);
            close(cnx);
            continue;
        }

        // Passer la clé API nettoyée à la fonction de génération de token
        printf("Clé API finale envoyée à la fonction : '%s'\n", api_key);
        UserInfo *user_info = generate_and_return_token(api_key, conn);

        if (user_info == NULL) {
            printf("\033[31m-> Erreur lors de la génération ou de la récupération du token.\033[0m\n");
            snprintf(txt_log, sizeof(txt_log),
                "-> Erreur lors de la génération ou de la récupération du token.");
            insert_logs(api_key, client_ip, txt_log);
            close(cnx);
            continue;
        }

        // Afficher le token généré pour confirmation (facultatif)
        printf("Token généré pour la clé API '%s' : %s\n", api_key, user_info->token);
        snprintf(txt_log, sizeof(txt_log),
                "User connecté token généré pour la clé API '%s' : %s\n", api_key, user_info->token);
        insert_logs(api_key, client_ip, txt_log);

        if (user_info == NULL) {
            perror("\033[31m-> Erreur lors de la génération du token.\033[0m");
            snprintf(txt_log, sizeof(txt_log),
                "-> Erreur lors de la génération du token.");
            insert_logs(api_key, client_ip, txt_log);
            close(cnx);
            continue;
        }
        if (user_info->token[0] != '\0') {
            char txt_env[80];
            snprintf(txt_env, sizeof(txt_env), "\033[32mVous êtes connecté, voici votre token : %s\033[0m\n", user_info->token);

            int tk = send(cnx, txt_env, strlen(txt_env), 0);
            if (tk == -1) {
                perror("-> Erreur lors de send ");
                snprintf(txt_log, sizeof(txt_log),
                    "-> Erreur lors de la génération du token.");
                insert_logs(api_key, client_ip, txt_log);
                PQfinish(conn);
                exit(-1);
            }

            char type_user_bdd[15];
            if (strcmp(user_info->new_user, "MEMBRE") == 0) {
                strcpy(type_user_bdd, "membre");
            } else if (strcmp(user_info->new_user, "PRO") == 0) {
                strcpy(type_user_bdd, "professionnel");
            }

            while (1) {
                send(cnx, "Nouvelle commande :\n", sizeof("Nouvelle commande :\n"), 0);
                memset(buffer, 0, sizeof(buffer));               // Réinitialiser le buffer
                ret = recv(cnx, buffer, sizeof(buffer) - 1, 0);  // Lire les données du client

                if (ret > 0) {
                    buffer[ret] = '\0';  // Terminaison de chaîne
                    printf("Commande reçue : %s\n", buffer);

                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// BYE BYE
                    //////////////////////////////////////////////////////////////////////////////////////////
                    if (strncmp(buffer, "BYE BYE", 7) == 0) {
                        send(cnx, "\033[33mConnexion terminée\033[0m\n", 20, 0);

                        snprintf(query, sizeof(query), "update tripenarvor._token set is_active = FALSE where token = '%s'", user_info->token);
                        // printf("Requête SQL exécutée : %s\n", query);

                        if (!execute_query(conn, query, api_key, client_ip)) {
                            send(cnx, "\033[31m-> Erreur interne lors de la déconnexion.\033[0m\n", strlen("Erreur interne lors de la déconnexion.\n"), 0);
                            break;
                        }

                        snprintf(txt_log, sizeof(txt_log), "BYE BYE %s", user_info->token);
                        insert_logs(api_key, client_ip, txt_log);
                        memset(query, 0, sizeof(query));

                        break;
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// REGEN
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "REGEN", 5) == 0) {
                        snprintf(query, sizeof(query), "select id_token, is_active from tripenarvor._token where token = '%s'", user_info->token);
                        // printf("Requête SQL exécutée : %s\n", query);

                        PGresult *res = PQexec(conn, query);
                        if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                            fprintf(stderr, "Échec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
                            PQclear(res);
                            PQfinish(conn);
                            return EXIT_FAILURE;
                        }
                        memset(query, 0, sizeof(query));

                        bool active_token;       // État actif/inactif du token
                        char type_user_bdd[15];  // Nom de la table cible (membre ou professionnel)

                        // Extraction des colonnes spécifiques
                        active_token = PQgetvalue(res, 0, 1);  // is_active

                        // printf("type_user récupéré : %s\n", type_user);

                        if (strcmp(user_info->new_user, "MEMBRE") == 0) {
                            strcpy(type_user_bdd, "membre");
                        } else if (strcmp(user_info->new_user, "PRO") == 0) {
                            strcpy(type_user_bdd, "professionnel");
                        }

                        // printf("type_user : %s, active_token : %d, my_api_key : %s, type_user_bdd : %s\n", user_info->new_user, active_token, my_api_key, type_user_bdd);

                        if (active_token) {
                            char first_char[2] = {user_info->new_user[0], '\0'};
                            // printf("Premier caractère de type_user : %s\n", first_char);

                            send(cnx, "Votre clé API a été changée\n", sizeof("Votre clé API a été changée\n"), 0);
                            printf("Nouvelle clé API générée\n");
                            snprintf(txt_log, sizeof(txt_log), "REGEN %s", user_info->token);
                            insert_logs(api_key, client_ip, txt_log);

                            snprintf(query, sizeof(query),
                                     "update tripenarvor._%s set api_key = tripenarvor.generate_api_key('%s') where api_key = '%s'",
                                     type_user_bdd, first_char, api_key);
                            // printf("Requête SQL exécutée : %s\n", query);

                            PGresult *res_update = PQexec(conn, query);
                            if (PQresultStatus(res_update) != PGRES_COMMAND_OK) {
                                fprintf(stderr, "Échec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
                                PQclear(res_update);
                                return EXIT_FAILURE;
                            }
                            PQclear(res_update);
                            memset(query, 0, sizeof(query));
                        } else {
                            send(cnx, "Votre token n'est plus valide\n", 31, 0);
                        }

                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// MSG
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "MSG", 3) == 0) {
                        int id_dest;
                        char txt[1000];
                        int offset = 0;

                        if (sscanf(buffer, "MSG %d %n", &id_dest, &offset) == 1) {
                            printf("ID destinataire : %d\n", id_dest);  // Affiche : 123
                            printf("Offset : %d\n", offset);            // Affiche : 7

                            // Pointeur vers la partie restante du message
                            char *message = buffer + offset;
                            printf("Message : %s\n", message);

                            snprintf(txt, sizeof(txt), "%s", buffer + offset);

                            // Retirer les espaces et les apostrophes en trop si nécessaire
                            char *start = txt;
                            while (*start == ' ') start++;  // Supprimer les espaces en début
                            char *end = start + strlen(start) - 1;
                            while (end > start && (*end == ' ' || *end == '\'')) {
                                *end = '\0';
                                end--;
                            }

                            // ENVOI DU MSG
                            snprintf(query, sizeof(query),
                                     "insert into tripenarvor._message (code_emetteur, code_destinataire, contenu) values (%d, %d, '%s')",
                                     user_info->id_user, id_dest, start);

                            PGresult *res_update = PQexec(conn, query);
                            if (PQresultStatus(res_update) != PGRES_COMMAND_OK) {
                                // Log en cas d'échec
                                snprintf(txt_log, sizeof(txt_log),
                                         "Échec de l'envoi du message au compte %d : %s", id_dest, PQerrorMessage(conn));
                                insert_logs(api_key, client_ip, txt_log);

                                fprintf(stderr, "Échec de l'enregistrement du message : %s\n", PQerrorMessage(conn));
                                PQclear(res_update);
                                return EXIT_FAILURE;
                            }

                            // Log en cas de succès
                            char success_message[256];
                            snprintf(success_message, sizeof(success_message),
                                     "Envoi du message au compte %d réussi", id_dest);
                            insert_logs(api_key, client_ip, success_message);

                            PQclear(res_update);

                        } else {
                            send(cnx, "Erreur de format : MSG <id_destinataire> '<contenu du message>'\n", 65, 0);
                        }
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// MODIF
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "MDF", 3) == 0) {
                        int id_msg;
                        char txt[1000], type_user_bdd[15];
                        int offset = 0;

                        if (strcmp(user_info->new_user, "MEMBRE") == 0) {
                            strcpy(type_user_bdd, "membre");
                        } else if (strcmp(user_info->new_user, "PRO") == 0) {
                            strcpy(type_user_bdd, "professionnel");
                        }

                        if (sscanf(buffer, "MDF %d %n", &id_msg, &offset) == 1) {
                            printf("ID Message : %d\n", id_msg);  // Affiche : 123
                            printf("Offset : %d\n", offset);      // Affiche : 7

                            // Pointeur vers la partie restante du message
                            char *message = buffer + offset;
                            printf("Message : %s\n", message);

                            snprintf(txt, sizeof(txt), "%s", buffer + offset);

                            // Retirer les espaces et les apostrophes en trop si nécessaire
                            char *start = txt;
                            while (*start == ' ') start++;  // Supprimer les espaces en début
                            char *end = start + strlen(start) - 1;
                            while (end > start && (*end == ' ' || *end == '\'')) {
                                *end = '\0';
                                end--;
                            }

                            // Récupération de la date et de l'heure actuelles
                            time_t now = time(NULL);
                            struct tm *time_info = localtime(&now);
                            if (time_info == NULL) {
                                perror("Erreur lors de la récupération de la date et de l'heure");
                                return EXIT_FAILURE;
                            }

                            char timestamp[20];
                            strftime(timestamp, sizeof(timestamp), "%Y-%m-%d %H:%M:%S", time_info);

                            // Construction de la requête SQL
                            snprintf(query, sizeof(query),
                                     "UPDATE tripenarvor._message SET contenu = '%s', horodatage_modifie = '%s', lus = 'false' WHERE id_message = %d",
                                     start, timestamp, id_msg);

                            // Exécution de la requête
                            PGresult *res_update = PQexec(conn, query);
                            if (PQresultStatus(res_update) != PGRES_COMMAND_OK) {
                                // Log en cas d'échec
                                snprintf(txt_log, sizeof(txt_log),
                                         "Échec de la mise à jour du message ID %d : %s", id_msg, PQerrorMessage(conn));
                                insert_logs(api_key, client_ip, txt_log);

                                send(cnx, "Le message n'a pas été modifié\n", sizeof("Le message n'a pas été modifié\n"), 0);

                                fprintf(stderr, "Échec de l'enregistrement du message : %s\n", PQerrorMessage(conn));
                                PQclear(res_update);
                                return EXIT_FAILURE;
                            } else {
                                 // Log en cas de succès
                                char success_message[256];
                                snprintf(success_message, sizeof(success_message),
                                        "Mise à jour réussie pour le message ID %d", id_msg);
                                insert_logs(api_key, client_ip, success_message);

                                PQclear(res_update);
                            }

                        } else {
                            send(cnx, "Erreur de format : MDF <id_msg> '<contenu du message>'\n", 56, 0);
                        }
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// HISTORIQUE
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "HIST", 4) == 0) {
                        // Si la commande HIST est suivie de rien ou d'un espace
                        if (strlen(buffer) == 4 || buffer[4] == ' ') {
                            send(cnx, "Erreur de format : HIST <Lus|nonLus|Envoyes|Reçus>\n", 50, 0);
                        } else {// Messages envoyés (messages où l'utilisateur est l'émetteur)
                            if (strncmp(buffer, "HIST ENVOYES", 12) == 0) {
                                snprintf(query, sizeof(query),
                                        "SELECT id_message, contenu, horodatage, horodatage_modifie, code_destinataire, membre.nom, membre.prenom, pro.raison_sociale "
                                        "FROM tripenarvor._message as msg "
                                        "LEFT JOIN tripenarvor._membre as membre ON membre.code_compte = msg.code_destinataire "
                                        "LEFT JOIN tripenarvor._professionnel as pro ON pro.code_compte = msg.code_destinataire "
                                        "WHERE code_emetteur = %d ORDER BY horodatage DESC",
                                        user_info->id_user);
                            }
                            // Messages reçus (messages où l'utilisateur est le destinataire)
                            else if (strncmp(buffer, "HIST RECUS", 10) == 0) {
                                snprintf(query, sizeof(query),
                                        "BEGIN; "
                                        // SELECT DES MESSAGES NON LUS
                                        "SELECT id_message, contenu, horodatage, horodatage_modifie, code_emetteur, membre.nom, membre.prenom, pro.raison_sociale "
                                        "FROM tripenarvor._message as msg "
                                        "LEFT JOIN tripenarvor._membre as membre ON membre.code_compte = msg.code_emetteur "
                                        "LEFT JOIN tripenarvor._professionnel as pro ON pro.code_compte = msg.code_emetteur "
                                        "WHERE code_destinataire = %d ORDER BY horodatage DESC; "

                                        // PASSAGES DES MESSAGES NON LUS EN LUS
                                        "UPDATE tripenarvor._message "
                                        "SET lus = true "
                                        "WHERE code_destinataire = %d AND lus = false; "

                                        "COMMIT;",
                                        user_info->id_user, user_info->id_user);
                            }
                            // Messages lus (messages où l'utilisateur est destinataire et lus)
                            else if (strncmp(buffer, "HIST LUS", 8) == 0) {
                                snprintf(query, sizeof(query),
                                        "SELECT id_message, contenu, horodatage, horodatage_modifie, code_emetteur, membre.nom, membre.prenom, pro.raison_sociale "
                                        "FROM tripenarvor._message as msg "
                                        "LEFT JOIN tripenarvor._membre as membre ON membre.code_compte = msg.code_emetteur "
                                        "LEFT JOIN tripenarvor._professionnel as pro ON pro.code_compte = msg.code_emetteur "
                                        "WHERE code_destinataire = %d AND lus = true ORDER BY horodatage DESC",
                                        user_info->id_user);
                            }
                            // Messages non lus (messages où l'utilisateur est destinataire et non lus)
                            else if (strncmp(buffer, "HIST NONLUS", 11) == 0) {
                                snprintf(query, sizeof(query),
                                        "BEGIN; "
                                        // SELECT DES MESSAGES NON LUS
                                        "SELECT id_message, contenu, horodatage, horodatage_modifie, code_emetteur, membre.nom, membre.prenom, pro.raison_sociale "
                                        "FROM tripenarvor._message as msg "
                                        "LEFT JOIN tripenarvor._membre as membre ON membre.code_compte = msg.code_emetteur "
                                        "LEFT JOIN tripenarvor._professionnel as pro ON pro.code_compte = msg.code_emetteur "
                                        "WHERE code_destinataire = %d AND lus = true ORDER BY horodatage DESC; "

                                        // PASSAGES DES MESSAGES NON LUS EN LUS
                                        "UPDATE tripenarvor._message "
                                        "SET lus = true "
                                        "WHERE code_destinataire = %d AND lus = false; "

                                        "COMMIT;",
                                        user_info->id_user, user_info->id_user);
                            }

                            PGresult *res = PQexec(conn, query);
                            if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                                fprintf(stderr, "Échec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
                                PQclear(res);
                                PQfinish(conn);
                                return EXIT_FAILURE;
                            }

                            int num_rows = PQntuples(res);
                            if (num_rows == 0) {
                                send(cnx, "Aucun message trouvé.\n", 23, 0);
                            } else {
                                char message_buffer[2048];  // Augmenter la taille du buffer pour un affichage plus large
                                for (int i = 0; i < num_rows; i++) {
                                    const char *nom_emetteur = PQgetvalue(res, i, 5);
                                    const char *prenom_emetteur = PQgetvalue(res, i, 6);
                                    const char *raison_sociale_emetteur = PQgetvalue(res, i, 7);

                                    char *emetteur = NULL;  // Utiliser un pointeur vers char (pas const)
                                    if (strlen(raison_sociale_emetteur) > 0) {
                                        emetteur = (char *)raison_sociale_emetteur;  // Utiliser la raison sociale si elle existe
                                    } else if (strlen(nom_emetteur) > 0 && strlen(prenom_emetteur) > 0) {
                                        emetteur = (char *)malloc(strlen(nom_emetteur) + strlen(prenom_emetteur) + 2);  // +2 pour l'espace et le caractère nul
                                        if (emetteur != NULL) {
                                            snprintf(emetteur, strlen(nom_emetteur) + strlen(prenom_emetteur) + 2, "%s %s", prenom_emetteur, nom_emetteur);  // Concaténer prénom et nom
                                        }
                                    } else {
                                        emetteur = "Inconnu";  // Si aucun nom ni prénom, afficher "Inconnu"
                                    }

                                    snprintf(message_buffer, sizeof(message_buffer),
                                            "\n----------------------------------------\n"
                                            "ID du message : %s\n"
                                            "Envoyé par : \033[1m%s\033[0m\n"
                                            "Contenu : \033[1m%s\033[0m"
                                            "Date de création : %s\n"
                                            "Dernière modification : %s\n"
                                            "----------------------------------------\n",
                                            PQgetvalue(res, i, 0), emetteur, PQgetvalue(res, i, 1), PQgetvalue(res, i, 2), PQgetvalue(res, i, 3));
                                    send(cnx, message_buffer, strlen(message_buffer), 0);

                                    // Libérer la mémoire uniquement si emetteur a été alloué dynamiquement
                                    if (emetteur != NULL && emetteur != "Inconnu" && emetteur != raison_sociale_emetteur) {
                                        free(emetteur);
                                    }
                                }
                            }
                            PQclear(res);
                        }
                    } else {
                        send(cnx, "Commande inconnue\n", 19, 0);
                    }
                } else {
                    perror("Erreur lors de la lecture\n");
                    break;
                }
            }
        } else {
            send(cnx, "Erreur lors de la génération du token\n", 39, 0);
        }

        printf("\033[33mDéconnexion du client en cours...\033[0m\n");
        send(cnx, "\033[33mVous avez été déconnecté.\033[0m\n", strlen("\033[33mVous avez été déconnecté.\033[0m\n"), 0);
        close(cnx);  // Fermer le socket client
        printf("\033[33mClient déconnecté.\033[0m\n");
    }

    PQfinish(conn);

    return 0;
}

/*
   \033[1m   --> Mettre le texte en gras (ou "intensité élevée")
   \033[0m   --> Réinitialise le style au format par défaut (désactive le gras, italique, etc.)

   \033[4m   --> Mettre le texte en souligné
   \033[24m  --> Désactive le souligné

   \033[3m   --> Mettre le texte en italique (pas supporté partout)
   \033[23m  --> Désactive l'italique

   \033[7m   --> Inverser les couleurs (texte en fond et fond en texte)
   \033[27m  --> Désactive l'inversion des couleurs

   \033[30m  --> Changer la couleur du texte en noir
   \033[31m  --> Changer la couleur du texte en rouge
   \033[32m  --> Changer la couleur du texte en vert
   \033[33m  --> Changer la couleur du texte en jaune
   \033[34m  --> Changer la couleur du texte en bleu
   \033[35m  --> Changer la couleur du texte en magenta
   \033[36m  --> Changer la couleur du texte en cyan
   \033[37m  --> Changer la couleur du texte en blanc
   \033[39m  --> Réinitialise la couleur du texte à la couleur par défaut

   \033[40m  --> Changer la couleur de fond en noir
   \033[41m  --> Changer la couleur de fond en rouge
   \033[42m  --> Changer la couleur de fond en vert
   \033[43m  --> Changer la couleur de fond en jaune
   \033[44m  --> Changer la couleur de fond en bleu
   \033[45m  --> Changer la couleur de fond en magenta
   \033[46m  --> Changer la couleur de fond en cyan
   \033[47m  --> Changer la couleur de fond en blanc
   \033[49m  --> Réinitialise la couleur de fond à la couleur par défaut

   \033[8m   --> Rendre le texte invisible
   \033[28m  --> Réafficher le texte invisible

   \033[9m   --> Barrer le texte
   \033[29m  --> Désactive le barré du texte

   \033[5m   --> Faire clignoter le texte (pas supporté partout)
   \033[25m  --> Désactive le clignotement

   \033[48;5;<num>m --> Couleur personnalisée du fond (ex: 48;5;82)
   \033[38;5;<num>m --> Couleur personnalisée du texte (ex: 38;5;82)
   */
