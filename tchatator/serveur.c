#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <stdbool.h>
#include <time.h> // Bibliothèque PostgreSQL
#include <libpq-fe.h> // Bibliothèque PostgreSQL


#include "config.h"
#include "bdd.h"
#include "fonct.h"




int main()
{
    int sock, cnx, ret, size, len;
    struct sockaddr_in addr, conn_addr;

    char buffer[BUFFER_SIZE], query[2000], txt_log[100]; // Buffer pour lire les données

    PGconn *conn = connect_to_db();
    if (conn == NULL) {
        return EXIT_FAILURE; // Échec de la connexion
    }

    if (load_config("param.txt") != 0) {
        printf("Erreur lors du chargement du fichier de configuration.\n");
        PQfinish(conn);
        return -1;
    }

    if (prepare_socket(&ret, &sock, &addr) != 0) {
        printf("Erreur : Impossible de configurer le socket.\n");
        exit(EXIT_FAILURE);
    }

    size = sizeof(conn_addr);

    while(1) // while(1) pour garder le serveur allume malgre que le client quitte
    {
    
        printf("=>En attente d'une connexion...\n");
        cnx = accept(sock, (struct sockaddr *)&conn_addr, (socklen_t *)&size);
        if (cnx == -1)
        {
            perror("->Erreur lors de accept ");
            PQfinish(conn);
            exit(-1);
        }

        // Récupération de l'adresse IP du client
        char client_ip[INET_ADDRSTRLEN]; // Buffer pour stocker l'adresse IP
        inet_ntop(AF_INET, &(conn_addr.sin_addr), client_ip, INET_ADDRSTRLEN);

        printf("Connexion acceptée de l'adresse IP : %s\n", client_ip);
        memset(buffer, 0, sizeof(buffer));




        char api_key[LEN_API + 1]; // Variable pour stocker la clé API reçue

        printf("En attente de la cle API...\n");

        // Réception de la clé API
        len = recv(cnx, api_key, sizeof(api_key) - 1, 0);
        if (len <= 0) {
            perror("Erreur lors de la réception des données");
            close(cnx);
            continue;
        }

        // Terminer la chaîne reçue avec un caractère nul
        api_key[len] = '\0';

        // Nettoyer la clé API en supprimant les retours à la ligne et les retours chariot
        api_key[strcspn(api_key, "\r\n")] = '\0';

        // Vérification de la validité de la clé API (facultatif)
        if (strlen(api_key) == 0) {
            printf("Erreur : clé API vide après nettoyage.\n");
            close(cnx);
            continue;
        }

        // Passer la clé API nettoyée à la fonction de génération de token
        printf("Clé API finale envoyée à la fonction : '%s'\n", api_key);
        UserInfo* user_info = generate_and_return_token(api_key, conn);

        if (user_info == NULL) {
            printf("Erreur lors de la génération ou de la récupération du token.\n");
            close(cnx);
            continue;
        }

        // Afficher le token généré pour confirmation (facultatif)
        printf("Token généré pour la clé API '%s' : %s\n", api_key, user_info->token);

        if (user_info == NULL) {
            perror("Erreur lors de la génération du token.");
            close(cnx);
            continue;
        }
        if (user_info->token[0] != '\0') {
            char txt_env[60];
            snprintf(txt_env, sizeof(txt_env), "Vous êtes connecté, voici votre token : %s\n", user_info->token);
            
            int tk = send(cnx, txt_env, strlen(txt_env), 0);
            if (tk == -1) {
                perror("Erreur lors de send ");
                PQfinish(conn);
                exit(-1);
            }

            while (1)
            {
                send(cnx, "Nouvelle commande :\n", sizeof("Nouvelle commande :\n"), 0);
                memset(buffer, 0, sizeof(buffer)); // Réinitialiser le buffer
                ret = recv(cnx, buffer, sizeof(buffer) - 1, 0); // Lire les données du client

                if (ret > 0)
                {
                    buffer[ret] = '\0'; // Terminaison de chaîne
                    printf("Données reçues : %s\n", buffer);

                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// BYE BYE...............................................................................
                    //////////////////////////////////////////////////////////////////////////////////////////
                    if (strncmp(buffer, "BYE BYE", 7) == 0) {

                        send(cnx, "Connexion terminée\n", 20, 0);
                        snprintf(txt_log, sizeof(txt_log), "BYE BYE %s",user_info->token);
                        insert_logs(api_key, client_ip, txt_log);

                        snprintf(query, sizeof(query), "update tripenarvor._token set is_active = FALSE where token = '%s'", user_info->token);
                        // printf("Requête SQL exécutée : %s\n", query);

                        PGresult *res = PQexec(conn, query);
                        if (PQresultStatus(res) != PGRES_COMMAND_OK) {
                            fprintf(stderr, "Échec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
                            PQclear(res); // Libérer la mémoire allouée pour le résultat
                            PQfinish(conn); // Libérer la mémoire allouée pour la connexion
                            return EXIT_FAILURE;
                        }
                        memset(query, 0, sizeof(query));

                        break;
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// REGEN.................................................................................
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

                        bool active_token;                  // État actif/inactif du token
                        char type_user_bdd[15];             // Nom de la table cible (membre ou professionnel)

                        // Extraction des colonnes spécifiques
                        active_token = PQgetvalue(res, 0, 1); // is_active

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
                            snprintf(txt_log, sizeof(txt_log), "REGEN %s",user_info->token);
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
                    /// MSG...................................................................................
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "MSG", 3) == 0) {
                        
                        int id_dest;
                        char txt[1000], type_user_bdd[15];
                        int offset = 0;

                        if (strcmp(user_info->new_user, "MEMBRE") == 0) {
                            strcpy(type_user_bdd, "membre");
                        } else if (strcmp(user_info->new_user, "PRO") == 0) {
                            strcpy(type_user_bdd, "professionnel");
                        }

                        snprintf(query, sizeof(query),
                                "select code_compte from tripenarvor._%s Natural JOIN tripenarvor._token where api_key = '%s'", type_user_bdd, api_key);
                        printf("Requête SQL exécutée : %s\n", query);

                        PGresult *res = PQexec(conn, query);
                        if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                            fprintf(stderr, "Échec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
                            PQclear(res);
                            PQfinish(conn);
                            return EXIT_FAILURE;
                        }
                        memset(query, 0, sizeof(query));

                        int id_emetteur = atoi(PQgetvalue(res, 0, 0));

                        PQclear(res);

                        if (sscanf(buffer, "MSG %d %n", &id_dest, &offset) == 1) {

                            printf("ID destinataire : %d\n", id_dest);   // Affiche : 123
                            printf("Offset : %d\n", offset);             // Affiche : 7

                            // Pointeur vers la partie restante du message
                            char *message = buffer + offset;
                            printf("Message : %s\n", message);

                            snprintf(txt, sizeof(txt), "%s", buffer + offset);

                            // Retirer les espaces et les apostrophes en trop si nécessaire
                            char *start = txt;
                            while (*start == ' ') start++; // Supprimer les espaces en début
                            char *end = start + strlen(start) - 1;
                            while (end > start && (*end == ' ' || *end == '\'')) {
                                *end = '\0';
                                end--;
                            }

                            // ENVOI DU MSG
                            snprintf(query, sizeof(query),
                                    "insert into tripenarvor._message (code_emetteur, code_destinataire, contenu) values (%d, %d, '%s')",
                                    id_emetteur, id_dest, start);


                            PGresult *res_update = PQexec(conn, query);
                            if (PQresultStatus(res_update) != PGRES_COMMAND_OK) {
                                fprintf(stderr, "Échec de l'enregistrement du message : %s\n", PQerrorMessage(conn));
                                PQclear(res_update);
                                return EXIT_FAILURE;
                            }
                            PQclear(res_update);



                        } else {
                            send(cnx, "Erreur de format : MSG <id_destinataire> '<contenu du message>'\n", 65, 0);
                        }
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// MODIF ................................................................................
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

                            printf("ID Message : %d\n", id_msg);   // Affiche : 123
                            printf("Offset : %d\n", offset);             // Affiche : 7

                            // Pointeur vers la partie restante du message
                            char *message = buffer + offset;
                            printf("Message : %s\n", message);

                            snprintf(txt, sizeof(txt), "%s", buffer + offset);

                            // Retirer les espaces et les apostrophes en trop si nécessaire
                            char *start = txt;
                            while (*start == ' ') start++; // Supprimer les espaces en début
                            char *end = start + strlen(start) - 1;
                            while (end > start && (*end == ' ' || *end == '\'')) {
                                *end = '\0';
                                end--;
                            }


                            // Obetnir Date et heure de la modif
                            // Obtenir la date et l'heure actuelles
                            time_t now = time(NULL);
                            struct tm *time_info = localtime(&now);

                            if (time_info == NULL) {
                                perror("Erreur lors de la récupération de la date et de l'heure");
                                return EXIT_FAILURE;
                            }

                            char timestamp[20];
                            strftime(timestamp, sizeof(timestamp), "%Y-%m-%d %H:%M:%S", time_info);

                            // ENVOI DU MSG
                            snprintf(query, sizeof(query),
                                    "UPDATE tripenarvor._message SET contenu = '%s', horodatage_modifie = '%s', lus = 'false' WHERE id_message = %d",
                                    start, timestamp, id_msg);


                            PGresult *res_update = PQexec(conn, query);
                            if (PQresultStatus(res_update) != PGRES_COMMAND_OK) {
                                fprintf(stderr, "Échec de l'enregistrement du message : %s\n", PQerrorMessage(conn));
                                PQclear(res_update);
                                return EXIT_FAILURE;
                            }
                            PQclear(res_update);



                        } else {
                            send(cnx, "Erreur de format : MDF <id_msg> '<contenu du message>'\n", 65, 0);
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

        printf("Déconnexion du client en cours...\n");
        send(cnx, "Vous avez été déconnecté.\n", strlen("Vous avez été déconnecté.\n"), 0);
        close(cnx); // Fermer le socket client
        printf("Client déconnecté.\n");
    }


    PQfinish(conn);

    return 0;
}