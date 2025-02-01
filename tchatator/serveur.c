#include <arpa/inet.h>
#include <libpq-fe.h>  // Bibliothèque PostgreSQL
#include <netinet/in.h>
#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/socket.h>
#include <sys/types.h>
#include <time.h>
#include <unistd.h>
#include <getopt.h>

#include "bdd.h"
#include "config.h"
#include "fonct.h"

#define _XOPEN_SOURCE 700  // Nécessaire pour strptime

int main(int argc, char *argv[]) {
    int sock, cnx, ret, size, len, opt;
    char buffer[BUFFER_SIZE], query[2000], txt_log[2050];
    bool verbose = false;

    struct sockaddr_in addr, conn_addr;
    struct option long_options[] = {
        {"help", no_argument, NULL, 'h'},
        {"verbose", no_argument, NULL, 'v'},
        {0, 0, 0, 0} // Fin de la liste
    };
    
    size = sizeof(conn_addr);

    while ((opt = getopt_long(argc, argv, "hvf:", long_options, NULL)) != -1) {
        switch (opt) {
            case 'h':
                printf("Usage: %s [--help] [--verbose] \n", argv[0]);
                return 0;
            case 'v':
                printf("\033[34m======= Verbose activé =======\033[0m\n");
                VERBOSE=true;
                break;
            case '?':
                printf("Option inconnue : %s\n", argv[optind - 1]);
                return 1;
        }
    }

    PGconn *conn = connect_to_db();
    if (conn == NULL) {
        return EXIT_FAILURE;  // Échec de la connexion
    }

    if (load_config("param.txt") != 0) {
        printf("\033[31m-> Erreur lors du chargement du fichier de configuration.\033[0m\n");

        snprintf(txt_log, sizeof(txt_log), "-> Erreur lors du chargement du fichier de configuration.");
        insert_logs(NULL, NULL, txt_log);

        PQfinish(conn);
        return -1;
    }

    if (prepare_socket(&ret, &sock, &addr) != 0) {
        printf("\033[31m-> Erreur : Impossible de configurer le socket.\033[0m\n");

        snprintf(txt_log, sizeof(txt_log), "-> Erreur : Impossible de configurer le socket.");
        insert_logs(NULL, NULL, txt_log);

        exit(EXIT_FAILURE);
    }

    while (1)  // while(1) pour garder le serveur allume malgre que le client quitte
    {
        UserInfo *user_info;
        char client_ip[INET_ADDRSTRLEN];  // Buffer pour stocker l'adresse IP
        char api_key[LEN_API + 1];        // Assurez-vous que la taille est suffisante

        do {
            // Initialiser la clé API avec une valeur par défaut
            strcpy(api_key, "DefaultApiKey");

            printf("\n=> En attente d'une connexion...\n");

            cnx = accept(sock, (struct sockaddr *)&conn_addr, (socklen_t *)&size);
            if (cnx == -1) {
                perror("\033[31m-> Erreur lors de accept\033[0m");

                snprintf(txt_log, sizeof(txt_log), "-> Erreur lors de accept");
                insert_logs(NULL, NULL, txt_log);

                PQfinish(conn);
                exit(-1);
            }

            // Récupération de l'adresse IP du client
            inet_ntop(AF_INET, &(conn_addr.sin_addr), client_ip, INET_ADDRSTRLEN);

            printf("\033[32mConnexion acceptée de l'adresse IP : %s\033[0m\n", client_ip);

            snprintf(txt_log, sizeof(txt_log), "Connexion acceptée de l'adresse IP : %s", client_ip);
            insert_logs(NULL, client_ip, txt_log);

            memset(buffer, 0, sizeof(buffer));

            do {
                // Réception de la clé API
                printf("> En attente de la cle API...\n");

                len = recv(cnx, api_key, sizeof(api_key) - 1, 0);
                if (len <= 0) {
                    if (len == 0) {
                        printf("Connexion fermée par le client.\n");
                    } else {
                        perror("\033[31m-> Erreur lors de la réception des données\033[0m");
                        snprintf(txt_log, sizeof(txt_log), "-> Erreur lors de la réception des données");
                        insert_logs(NULL, client_ip, txt_log);
                    }
                    break;  // Sortir si la connexion est fermée ou en cas d'erreur
                }

                // Traitement si la réception est réussie
                // printf("Réussi : len: %d\n", len);
                api_key[len] = '\0';  // S'assurer que la clé API est bien terminée
                api_key[strcspn(api_key, "\r\n")] = '\0';
                // printf("Clé API après nettoyage : '%s'\n", api_key);

                // Si la clé API est valide, continuer avec la génération du token
                // printf("Clé API envoyée à la fonction : '%s'\n", api_key);

                user_info = generate_and_return_token(api_key, conn);

                if (user_info == NULL) {
                    printf("\033[31m-> Erreur : la clé API est invalide ou le token n'a pas pu être généré.\033[0m\n");
                    snprintf(txt_log, sizeof(txt_log), "-> Erreur : la clé API est invalide ou le token n'a pas pu être généré.");
                    insert_logs(api_key, client_ip, txt_log);
                    send(cnx, "\033[31m-> Erreur : La clé API n'existe pas\033[0m\n\n", sizeof("\033[31m-> Erreur : La clé API n'existe pas\033[0m\n"), 0);
                    // Ne ferme pas la connexion ici, juste demander à nouveau la clé API
                    continue;  // Demander à nouveau la clé API
                }
            } while (user_info == NULL);

        } while (user_info == NULL);

        // Afficher le token généré pour confirmation (facultatif)
        printf("\033[32mToken généré pour la clé API '%s' : %s\n\033[0m", api_key, user_info->token);

        snprintf(txt_log, sizeof(txt_log), "User connecté token généré pour la clé API '%s' : %s", api_key, user_info->token);
        insert_logs(api_key, client_ip, txt_log);

        if (user_info->token[0] != '\0') {
            char txt_env[80];
            snprintf(txt_env, sizeof(txt_env), "\033[32m==> Vous êtes connecté, voici votre token : %s\033[0m\n", user_info->token);

            int tk = send(cnx, txt_env, strlen(txt_env), 0);
            if (tk == -1) {
                perror("-> Erreur lors de send ");

                snprintf(txt_log, sizeof(txt_log), "-> Erreur lors de la génération du token.");
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
                send(cnx, "=> Nouvelle commande : ", strlen("=> Nouvelle commande : "), 0);

                do {
                    memset(buffer, 0, sizeof(buffer));               
                    ret = recv(cnx, buffer, sizeof(buffer) - 1, 0);

                } while (ret > 0 && (buffer[0] == '\n' || buffer[0] == '\r'));

                if (ret > 0) {
                    buffer[ret] = '\0';  // Terminaison de chaîne
                    printf("Commande reçue : %s", buffer);

                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// BYE BYE
                    //////////////////////////////////////////////////////////////////////////////////////////
                    if (strncmp(buffer, "BYE BYE", 7) == 0) {
                        send(cnx, "\033[33mConnexion terminée\033[0m\n", sizeof("\033[33mConnexion terminée\033[0m\n"), 0);

                        snprintf(query, sizeof(query), "update tripenarvor._token set is_active = FALSE where token = '%s'", user_info->token);
                        // printf("Requête SQL exécutée : %s\n", query);

                        if (!execute_query(conn, query, api_key, client_ip)) {
                            send(cnx, "\033[31m-> Erreur interne lors de la déconnexion.\033[0m\n", strlen("\033[31m-> Erreur interne lors de la déconnexion.\033[0m\n"), 0);
                        } else {
                            snprintf(txt_log, sizeof(txt_log), "BYE BYE %s", user_info->token);
                            insert_logs(api_key, client_ip, txt_log);
                        }

                        memset(query, 0, sizeof(query));
                        break;
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// REGEN
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "REGEN", 5) == 0) {
                        if (strcmp(user_info->new_user, "ADMIN") != 0) {
                            if (is_token_valid(conn, user_info->token, txt_log, sizeof(txt_log))) {
                                char first_char[2] = {user_info->new_user[0], '\0'};

                                send(cnx, "Votre clé API a été changée\n", sizeof("Votre clé API a été changée\n"), 0);
                                printf("Nouvelle clé API générée\n");

                                snprintf(query, sizeof(query),
                                         "update tripenarvor._%s set api_key = tripenarvor.generate_api_key('%s') where api_key = '%s'",
                                         type_user_bdd, first_char, api_key);

                                if (!execute_query(conn, query, api_key, client_ip)) {
                                    send(cnx, "\033[31m-> Erreur interne lors de la regénération de la clé d'API.\033[0m\n", strlen("\033[31m-> Erreur interne lors de la regénération de la clé d'API.\033[0m\n\n"), 0);
                                } else {
                                    snprintf(txt_log, sizeof(txt_log), "REGEN %s", user_info->token);
                                    insert_logs(api_key, client_ip, txt_log);
                                }

                                memset(query, 0, sizeof(query));
                            } else {
                                send(cnx, "\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n", strlen("\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n"), 0);
                                fprintf(stderr, "\033[31m%s\033[0m\n", txt_log);

                                insert_logs(api_key, client_ip, txt_log);
                            }
                        } else {
                            send(cnx, "\033[31m-> Erreur : Vous ne pouvez pas regen votre clé API\033[39m\n", sizeof("\033[31m-> Erreur : Vous ne pouvez pas regen votre clé API\n\033[39m"), 0);

                            snprintf(txt_log, sizeof(txt_log), "-> Erreur : Un admin peut pas regen sa cle d'API");
                            insert_logs(api_key, client_ip, txt_log);

                            printf("\033[31m-> Erreur : Un admin peut pas regen sa cle d'API\033[0m\n");
                        }

                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// MSG
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "MSG", 3) == 0) {
                        if (strcmp(user_info->new_user, "ADMIN") != 0) {
                            int id_dest;
                            char txt[1000];
                            int offset = 0;

                            if (sscanf(buffer, "MSG %d %n", &id_dest, &offset) == 1) {
                                bool mess;
                                if (strcmp(user_info->new_user, "MEMBRE") == 0) {
                                    mess = is_member_blocked_all(conn, user_info->id_user, txt_log, 2050);
                                } else {
                                    mess = false;
                                }

                                if (!mess) {
                                    if (strcmp(user_info->new_user, "MEMBRE") == 0) {
                                        mess = is_member_blocked_for(conn, user_info->id_user, id_dest, txt_log, 2050);
                                    } else {
                                        mess = false;
                                    }

                                    if (!mess) {
                                        // Pointeur vers la partie restante du message
                                        char *message = buffer + offset;
                                        // printf("Message : %s\n", message);

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
                                        if (is_token_valid(conn, user_info->token, txt_log, sizeof(txt_log))) {
                                            if (user_info->id_user != id_dest) {
                                                snprintf(query, sizeof(query),
                                                         "insert into tripenarvor._message (code_emetteur, code_destinataire, contenu) values (%d, %d, '%s')",
                                                         user_info->id_user, id_dest, start);

                                                if (!execute_query(conn, query, api_key, client_ip)) {
                                                    send(cnx, "\033[31m-> Erreur interne lors de l'envoie du message.\033[0m\n", strlen("\033[31m-> Erreur interne lors de l'envoie du message.\033[0m\n"), 0);
                                                } else {
                                                    send(cnx, "Envoi du message réussi\n", sizeof("Envoi du message réussi\n"), 0);

                                                    snprintf(txt_log, sizeof(txt_log), "Envoi du message au compte %d réussi", id_dest);
                                                    insert_logs(api_key, client_ip, txt_log);

                                                    printf("Envoi du message au compte %d réussi\n", id_dest);
                                                }

                                                memset(query, 0, sizeof(query));
                                            } else {
                                                send(cnx, "\033[31m-> Erreur : id_dest = id_user\033[0m", sizeof("\033[31m-> Erreur : id_dest = id_user\033[0m"), 0);

                                                snprintf(txt_log, sizeof(txt_log), "-> Erreur : id_dest = id_user");
                                                insert_logs(api_key, client_ip, txt_log);

                                                printf("\033[31m-> Erreur : id_dest = id_user\033[0m");
                                            }

                                        } else {
                                            send(cnx, "\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n", strlen("\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n"), 0);
                                            fprintf(stderr, "\033[31m%s\033[0m\n", txt_log);

                                            insert_logs(api_key, client_ip, txt_log);
                                        }
                                    } else {
                                        send(cnx, "\033[31m-> Vous ne pouvez plus envoyer de message à ce professionnel.\33[0m\n", strlen("\033[31m-> Vous ne pouvez plus envoyer de message à ce professionnel.\33[0m\n"), 0);
                                        fprintf(stderr, "\033[31m%s\033[0m\n", txt_log);

                                        insert_logs(api_key, client_ip, txt_log);
                                    }
                                } else {
                                    send(cnx, "\033[31m-> Vous ne pouvez plus envoyer de message.\33[0m\n", strlen("\033[31m-> Vous ne pouvez plus envoyer de message.\33[0m\n"), 0);
                                    fprintf(stderr, "\033[31m%s\033[0m\n", txt_log);

                                    insert_logs(api_key, client_ip, txt_log);
                                }

                            } else {
                                send(cnx, "\033[31m-> Erreur de format : MSG <id_destinataire> '<contenu du message>'\n\033[0m", sizeof("\033[31m-> Erreur de format : MSG <id_destinataire> '<contenu du message>'\n\033[0m"), 0);

                                snprintf(txt_log, sizeof(txt_log), "-> Erreur de format : MSG <id_destinataire> '<contenu du message>'");
                                insert_logs(api_key, client_ip, txt_log);

                                printf("\033[31m-> Erreur Format\033[0m\n");
                            }
                        } else {
                            send(cnx, "\033[31m-> Erreur : Vous ne pouvez pas envoyer un message\033[0m\n", sizeof("\033[31m-> Erreur : Vous ne pouvez pas envoyer un message\n\033[39m"), 0);

                            snprintf(txt_log, sizeof(txt_log), "-> Erreur : Un admin peut pas envoyer un message");
                            insert_logs(api_key, client_ip, txt_log);

                            printf("\033[31m-> Erreur : Un admin peut pas envoyer un message\033[0m\n");
                        }

                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// MODIF
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "MDF", 3) == 0) {
                        if (strcmp(user_info->new_user, "ADMIN") != 0) {
                            int id_msg;
                            char txt[1000], type_user_bdd[15];
                            int offset = 0;

                            if (strcmp(user_info->new_user, "MEMBRE") == 0) {
                                strcpy(type_user_bdd, "membre");
                            } else if (strcmp(user_info->new_user, "PRO") == 0) {
                                strcpy(type_user_bdd, "professionnel");
                            }

                            if (sscanf(buffer, "MDF %d %n", &id_msg, &offset) == 1) {
                                // printf("ID Message : %d\n", id_msg);  // Affiche : 123
                                // printf("Offset : %d\n", offset);      // Affiche : 7

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
                                    perror("\033[31m-> Erreur lors de la récupération de la date et de l'heure\033[0m");

                                    snprintf(txt_log, sizeof(txt_log), "-> Erreur lors de la récupération de la date et de l'heure");
                                    insert_logs(api_key, client_ip, txt_log);
                                    return EXIT_FAILURE;
                                }

                                char timestamp[20];
                                strftime(timestamp, sizeof(timestamp), "%Y-%m-%d %H:%M:%S", time_info);

                                // MDF message
                                if (is_token_valid(conn, user_info->token, txt_log, sizeof(txt_log))) {
                                    snprintf(query, sizeof(query),
                                             "UPDATE tripenarvor._message SET contenu = '%s', horodatage_modifie = '%s', lus = 'false' WHERE id_message = %d",
                                             start, timestamp, id_msg);

                                    printf("Query : %s\n", query);

                                    // Exécution de la requête
                                    if (!execute_query(conn, query, api_key, client_ip)) {
                                        send(cnx, "\033[31m-> Erreur interne lors de la modification du message.\033[0m\n", strlen("\033[31m-> Erreur interne lors de la modification du message.\033[0m\n"), 0);
                                    } else {
                                        // Log en cas de succès
                                        send(cnx, "Modification du message réussi\n", sizeof("Modification du message réussi\n"), 0);

                                        snprintf(txt_log, sizeof(txt_log), "Modification du message %d réussi", id_msg);
                                        insert_logs(api_key, client_ip, txt_log);

                                        printf("Mise à jour réussie pour le message ID %d\n", id_msg);
                                    }
                                } else {
                                    send(cnx, "\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n", strlen("\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n"), 0);
                                    fprintf(stderr, "\033[31m%s\033[0m\n", txt_log);

                                    insert_logs(api_key, client_ip, txt_log);
                                }

                            } else {
                                send(cnx, "\033[31m-> Erreur de format : MDF <id_msg> '<contenu du message>'\n\033[39m", 72, 0);

                                snprintf(txt_log, sizeof(txt_log), "-> Erreur de format : MDF <id_msg> '<contenu du message>'");
                                insert_logs(api_key, client_ip, txt_log);

                                printf("\033[31m-> Erreur Format\033[0m");
                            }
                        } else {
                            send(cnx, "\033[31m-> Erreur : Vous ne pouvez pas modifier un message\033[39m\n", sizeof("\033[31m-> Erreur : Vous ne pouvez pas modifier un message\n\033[39m"), 0);

                            snprintf(txt_log, sizeof(txt_log), "-> Erreur : Un admin peut pas modifier un message");
                            insert_logs(api_key, client_ip, txt_log);

                            printf("\033[31m-> Erreur : Un admin peut pas modifier un message\033[0m\n");
                        }
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// HISTORIQUE
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "HIST", 4) == 0) {
                        if (strcmp(user_info->new_user, "ADMIN") != 0) {
                            if (is_token_valid(conn, user_info->token, txt_log, sizeof(txt_log))) {
                                bool executable = false;
                                bool a_marquer_comme_lus = false;
                                char envoyeParOuA[10];
                                // Déterminer le type de commande HIST (envoies, recus, lus, nonlus)
                                char command_type[20];
                                sscanf(buffer, "HIST %19s", command_type);  // Récupère la partie après "HIST"

                                // Switch sur le type de commande
                                switch (command_type[0]) {
                                    case 'E':  // "HIST ENVOYES"
                                        if (strncmp(command_type, "ENVOYES", 7) == 0) {
                                            snprintf(query, sizeof(query),
                                                     "SELECT id_message, contenu, horodatage, horodatage_modifie, code_destinataire, membre.nom, membre.prenom, pro.raison_sociale "
                                                     "FROM tripenarvor._message as msg "
                                                     "LEFT JOIN tripenarvor._membre as membre ON membre.code_compte = msg.code_destinataire "
                                                     "LEFT JOIN tripenarvor._professionnel as pro ON pro.code_compte = msg.code_destinataire "
                                                     "WHERE code_emetteur = %d AND supprime='false' ORDER BY horodatage ASC",
                                                     user_info->id_user);
                                        }
                                        executable = true;
                                        strcpy(envoyeParOuA, "à");
                                        break;

                                    case 'R':  // "HIST RECUS"
                                        if (strncmp(command_type, "RECUS", 5) == 0) {
                                            snprintf(query, sizeof(query),
                                                     "SELECT id_message, contenu, horodatage, horodatage_modifie, code_emetteur, membre.nom, membre.prenom, pro.raison_sociale "
                                                     "FROM tripenarvor._message as msg "
                                                     "LEFT JOIN tripenarvor._membre as membre ON membre.code_compte = msg.code_emetteur "
                                                     "LEFT JOIN tripenarvor._professionnel as pro ON pro.code_compte = msg.code_emetteur "
                                                     "WHERE code_destinataire = %d AND supprime='false' ORDER BY horodatage ASC;",
                                                     user_info->id_user);
                                        }
                                        executable = true;
                                        a_marquer_comme_lus = true;
                                        strcpy(envoyeParOuA, "par");

                                        break;

                                    case 'L':  // "HIST LUS"
                                        if (strncmp(command_type, "LUS", 3) == 0) {
                                            snprintf(query, sizeof(query),
                                                     "SELECT id_message, contenu, horodatage, horodatage_modifie, code_emetteur, membre.nom, membre.prenom, pro.raison_sociale "
                                                     "FROM tripenarvor._message as msg "
                                                     "LEFT JOIN tripenarvor._membre as membre ON membre.code_compte = msg.code_emetteur "
                                                     "LEFT JOIN tripenarvor._professionnel as pro ON pro.code_compte = msg.code_emetteur "
                                                     "WHERE code_destinataire = %d AND lus = true AND supprime='false' ORDER BY horodatage ASC",
                                                     user_info->id_user);
                                        }
                                        executable = true;
                                        strcpy(envoyeParOuA, "par");

                                        break;

                                    case 'N':  // "HIST NONLUS"
                                        if (strncmp(command_type, "NONLUS", 6) == 0) {
                                            snprintf(query, sizeof(query),
                                                     "SELECT id_message, contenu, horodatage, horodatage_modifie, code_emetteur, membre.nom, membre.prenom, pro.raison_sociale "
                                                     "FROM tripenarvor._message as msg "
                                                     "LEFT JOIN tripenarvor._membre as membre ON membre.code_compte = msg.code_emetteur "
                                                     "LEFT JOIN tripenarvor._professionnel as pro ON pro.code_compte = msg.code_emetteur "
                                                     "WHERE code_destinataire = %d AND lus = false AND supprime='false' ORDER BY horodatage ASC; ",
                                                     user_info->id_user);
                                        }
                                        executable = true;
                                        a_marquer_comme_lus = true;
                                        strcpy(envoyeParOuA, "par");

                                        break;

                                    default:
                                        send(cnx, "\033[31m-> Erreur de format : HIST <LUS | NONLUS | RECUS | ENVOYES> \n\033[0m", sizeof("\033[31m-> Erreur de format : HIST <LUS | NONLUS | RECUS | ENVOYES> \n\033[0m"), 0);

                                        snprintf(txt_log, sizeof(txt_log), "-> Erreur de format : HIST <LUS | NONLUS | RECUS | ENVOYES>");
                                        insert_logs(api_key, client_ip, txt_log);

                                        printf("\033[31m-> Erreur Format\033[0m");
                                        break;
                                }

                                if (executable == true) {
                                    // Exécution de la requête SQL
                                    PGresult *res = PQexec(conn, query);
                                    if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                                        fprintf(stderr, "\033[31m-> Échec de la requête SQL : %s\n%s\033[0m\n", query, PQerrorMessage(conn));

                                        snprintf(txt_log, sizeof(txt_log), "-> Échec de la requête SQL : %s\n%s", query, PQerrorMessage(conn));
                                        insert_logs(api_key, client_ip, txt_log);
                                    } else {
                                        int num_rows = PQntuples(res);
                                        if (num_rows == 0) {
                                            send(cnx, "Aucun message trouvé.\n", 23, 0);

                                            snprintf(txt_log, sizeof(txt_log), "Aucun message trouvé.");
                                            insert_logs(api_key, client_ip, txt_log);
                                        } else {
                                            char message_buffer[2048];
                                            for (int i = 0; i < num_rows; i++) {
                                                const char *nom_emetteur = PQgetvalue(res, i, 5);
                                                const char *prenom_emetteur = PQgetvalue(res, i, 6);
                                                const char *raison_sociale_emetteur = PQgetvalue(res, i, 7);

                                                char *emetteur = NULL;
                                                if (strlen(raison_sociale_emetteur) > 0) {
                                                    emetteur = (char *)raison_sociale_emetteur;
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
                                                         "Envoyé %s : \033[1m%s\033[0m\n"
                                                         "Contenu : \033[1m%s\033[0m"
                                                         "Date de création : %s\n"
                                                         "Dernière modification : %s\n"
                                                         "----------------------------------------\n",
                                                         PQgetvalue(res, i, 0), envoyeParOuA, emetteur, PQgetvalue(res, i, 1), PQgetvalue(res, i, 2), PQgetvalue(res, i, 3));
                                                send(cnx, message_buffer, strlen(message_buffer), 0);

                                                // Libérer la mémoire uniquement si emetteur a été alloué dynamiquement
                                                if (emetteur != NULL && strcmp(emetteur, "Inconnu") != 0 && strcmp(emetteur, raison_sociale_emetteur) != 0) {
                                                    free(emetteur);
                                                }
                                            }

                                            snprintf(txt_log, sizeof(txt_log), "HIST %s affiché", command_type);
                                            insert_logs(api_key, client_ip, txt_log);
                                        }
                                        PQclear(res);

                                        if (a_marquer_comme_lus) {
                                            // Marquer les messages comme lus après avoir traité la sélection
                                            char update_query[512];
                                            snprintf(update_query, sizeof(update_query),
                                                     "UPDATE tripenarvor._message SET lus = true WHERE code_destinataire = %d AND lus = false;",
                                                     user_info->id_user);

                                            // Appeler execute_query avec la requête formatée
                                            execute_query(conn, update_query, api_key, client_ip);
                                        }
                                    }
                                }

                            } else {
                                send(cnx, "\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n", strlen("\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n"), 0);
                                fprintf(stderr, "\033[31m%s\033[0m\n", txt_log);

                                insert_logs(api_key, client_ip, txt_log);
                            }
                        } else {
                            send(cnx, "\033[31m-> Erreur : Vous ne pouvez pas consulter votre historique\033[39m\n", sizeof("\033[31m-> Erreur : Vous ne pouvez pas consulter votre historique\n\033[39m"), 0);

                            snprintf(txt_log, sizeof(txt_log), "-> Erreur : Un admin peut pas consulter son historique");
                            insert_logs(api_key, client_ip, txt_log);

                            printf("\033[31m-> Erreur : Un admin peut pas consulter son historique\033[0m\n");
                        }
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// SUPPR
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "SUPPR", 5) == 0) {
                        if (strcmp(user_info->new_user, "ADMIN") != 0) {
                            int id_msg;

                            if (sscanf(buffer, "SUPPR %d", &id_msg) == 1) {
                                // SUPPR message
                                if (is_token_valid(conn, user_info->token, txt_log, sizeof(txt_log))) {
                                    snprintf(query, sizeof(query),
                                             "UPDATE tripenarvor._message SET supprime = 'true' WHERE id_message = %d AND code_emetteur = %d",
                                             id_msg, user_info->id_user);

                                    // Exécution de la requête
                                    if (!execute_query(conn, query, api_key, client_ip)) {
                                        send(cnx, "\033[31m-> Erreur interne lors de la suppression du message.\033[0m\n", strlen("\033[31m-> Erreur interne lors de la suppression du message.\033[0m\n"), 0);
                                    } else {
                                        // Log en cas de succès
                                        send(cnx, "Suppression du message réussi\n", sizeof("Suppression du message réussi\n"), 0);

                                        snprintf(txt_log, sizeof(txt_log), "Suppression du message %d réussi\n", id_msg);
                                        insert_logs(api_key, client_ip, txt_log);

                                        printf("Suppression réussie pour le message ID %d\n", id_msg);
                                    }
                                } else {
                                    send(cnx, "\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n", strlen("\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n"), 0);
                                    fprintf(stderr, "\033[31m%s\033[0m\n", txt_log);

                                    insert_logs(api_key, client_ip, txt_log);
                                }

                            } else {
                                send(cnx, "\033[31m-> Erreur de format : SUPPR <id_msg>\n\033[39m", 54, 0);

                                snprintf(txt_log, sizeof(txt_log), "-> Erreur de format : SUPPR <id_msg>");
                                insert_logs(api_key, client_ip, txt_log);

                                printf("\033[31m-> Erreur Format\033[0m");
                            }
                        } else {
                            send(cnx, "\033[31m-> Erreur : Vous ne pouvez pas supprimer un message\033[39m\n", sizeof("\033[31m-> Erreur : Vous ne pouvez pas supprimer un message\n\033[39m"), 0);

                            snprintf(txt_log, sizeof(txt_log), "-> Erreur : Un admin peut pas supprimer un message");
                            insert_logs(api_key, client_ip, txt_log);

                            printf("\033[31m-> Erreur : Un admin peut pas supprimer un message\033[0m\n");
                        }

                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// BLOQUER
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "BLOCK", 5) == 0) {
                        int id_membre;

                        if (sscanf(buffer, "BLOCK %d", &id_membre) == 1) {
                            if (strcmp(user_info->new_user, "ADMIN") == 0) {
                                // BLOCK admin -> membre
                                snprintf(query, sizeof(query),
                                         "insert into tripenarvor._blocked_all (code_membre, blocked_at, expires_at) values (%d, now(), now() + interval '%d hour');", id_membre, BAN_DUR);

                                // Exécution de la requête
                                if (!execute_query(conn, query, api_key, client_ip)) {
                                    send(cnx, "\033[31m-> Erreur interne lors du blocage du membre.\033[0m\n", strlen("\033[31m-> Erreur interne lors du blocage du membre.\033[0m\n"), 0);
                                } else {
                                    // Log en cas de succès
                                    send(cnx, "Blocage du membre réussi\n", sizeof("Blocage du membre réussi\n"), 0);

                                    snprintf(txt_log, sizeof(txt_log), "Blocage du membre %d réussi par un admin", id_membre);
                                    insert_logs(api_key, client_ip, txt_log);

                                    printf("Blocage réussie pour le membre ID %d par un admin\n", id_membre);
                                }
                            } else if (strcmp(user_info->new_user, "PRO") == 0) {
                                // BLOCK pro -> membre
                                if (is_token_valid(conn, user_info->token, txt_log, sizeof(txt_log))) {
                                    snprintf(query, sizeof(query),
                                             "insert into tripenarvor._blocked_for (code_membre, code_professionnel, blocked_at, expires_at) values (%d, %d, now(), now() + interval '%d hour');", id_membre, user_info->id_user, BAN_DUR);

                                    // Exécution de la requête
                                    if (!execute_query(conn, query, api_key, client_ip)) {
                                        send(cnx, "\033[31m-> Erreur interne lors du blocage du membre.\033[0m\n", strlen("\033[31m-> Erreur interne lors du blocage du membre.\033[0m\n"), 0);
                                    } else {
                                        // Log en cas de succès
                                        send(cnx, "Blocage du membre réussi\n", sizeof("Blocage du membre réussi\n"), 0);

                                        snprintf(txt_log, sizeof(txt_log), "Blocage du membre %d réussi par un professionnel", id_membre);
                                        insert_logs(api_key, client_ip, txt_log);

                                        printf("Blocage réussie pour le membre ID %d par un professionnel\n", id_membre);
                                    }
                                } else {
                                    send(cnx, "\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n", strlen("\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n"), 0);
                                    fprintf(stderr, "\033[31m%s\033[0m\n", txt_log);

                                    insert_logs(api_key, client_ip, txt_log);
                                }
                            } else {
                                send(cnx, "\033[31m-> Erreur : Vous ne pouvez pas bloquer un utilisateur\n\033[39m", 71, 0);

                                snprintf(txt_log, sizeof(txt_log), "-> Erreur : Un membre ne peut pas bloquer un utilisateur");
                                insert_logs(api_key, client_ip, txt_log);

                                printf("\033[31m-> Erreur : Un membre ne peut pas bloquer un utilisateur\033[0m");
                            }

                        } else {
                            send(cnx, "\033[31m-> Erreur de format : BLOCK <id_membre>\n\033[39m", 57, 0);

                            snprintf(txt_log, sizeof(txt_log), "-> Erreur de format : BLOCK <id_membre>");
                            insert_logs(api_key, client_ip, txt_log);

                            printf("\033[31m-> Erreur Format\033[0m");
                        }

                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// DEBLOQUER
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "UNBLOCK", 7) == 0) {
                        int id_membre;

                        if (sscanf(buffer, "UNBLOCK %d", &id_membre) == 1) {
                            if (strcmp(user_info->new_user, "ADMIN") == 0) {
                                // UNBLOCK admin -> membre
                                snprintf(query, sizeof(query),
                                         "delete from tripenarvor._blocked_all where code_membre=%d;", id_membre);

                                // Exécution de la requête
                                if (!execute_query(conn, query, api_key, client_ip)) {
                                    send(cnx, "\033[31m-> Erreur interne lors du déblocage du membre.\033[0m\n", strlen("\033[31m-> Erreur interne lors du déblocage du membre.\033[0m\n"), 0);
                                } else {
                                    // Log en cas de succès
                                    send(cnx, "Déblocage du membre réussi\n", sizeof("Déblocage du membre réussi\n"), 0);

                                    snprintf(txt_log, sizeof(txt_log), "Déblocage du membre %d réussi par un admin", id_membre);
                                    insert_logs(api_key, client_ip, txt_log);

                                    printf("Déblocage réussie pour le membre ID %d par un admin\n", id_membre);
                                }
                            } else if (strcmp(user_info->new_user, "PRO") == 0) {
                                // UNBLOCK pro -> membre
                                if (is_token_valid(conn, user_info->token, txt_log, sizeof(txt_log))) {
                                    snprintf(query, sizeof(query),
                                             "delete from tripenarvor._blocked_for where code_membre=%d;", id_membre);

                                    // Exécution de la requête
                                    if (!execute_query(conn, query, api_key, client_ip)) {
                                        send(cnx, "\033[31m-> Erreur interne lors du déblocage du membre.\033[0m\n", strlen("\033[31m-> Erreur interne lors du déblocage du membre.\033[0m\n"), 0);
                                    } else {
                                        // Log en cas de succès
                                        send(cnx, "Déblocage du membre réussi\n", sizeof("Déblocage du membre réussi\n"), 0);

                                        snprintf(txt_log, sizeof(txt_log), "Déblocage du membre %d réussi par un professionnel", id_membre);
                                        insert_logs(api_key, client_ip, txt_log);

                                        printf("Déblocage réussie pour le membre ID %d par un professionnel\n", id_membre);
                                    }
                                } else {
                                    send(cnx, "\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n", strlen("\033[31m-> Erreur : Votre token n'est plus actif.\33[0m\n"), 0);
                                    fprintf(stderr, "\033[31m%s\033[0m\n", txt_log);

                                    insert_logs(api_key, client_ip, txt_log);
                                }
                            } else {
                                send(cnx, "\033[31m-> Erreur : Vous ne pouvez pas débloquer un utilisateur\n\033[39m", 73, 0);

                                snprintf(txt_log, sizeof(txt_log), "-> Erreur : Un membre ne peut pas débloquer un utilisateur");
                                insert_logs(api_key, client_ip, txt_log);

                                printf("\033[31m-> Erreur : Un membre ne peut pas débloquer un utilisateur\033[0m");
                            }

                        } else {
                            send(cnx, "\033[31m-> Erreur de format : UNBLOCK <id_membre>\n\033[39m", 59, 0);

                            snprintf(txt_log, sizeof(txt_log), "-> Erreur de format : UNBLOCK <id_membre>");
                            insert_logs(api_key, client_ip, txt_log);

                            printf("\033[31m-> Erreur Format\033[0m");
                        }

                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// UNBAN
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "UNBAN", 5) == 0) {
                        int id_membre;

                        if (sscanf(buffer, "UNBAN %d", &id_membre) == 1) {
                            if (strcmp(user_info->new_user, "ADMIN") == 0) {
                                // UNBAN admin -> membre
                                snprintf(query, sizeof(query),
                                         "delete from tripenarvor._banned where code_membre=%d;", id_membre);

                                // Exécution de la requête
                                if (!execute_query(conn, query, api_key, client_ip)) {
                                    send(cnx, "\033[31m-> Erreur interne lors du débannissement du membre.\033[0m\n", strlen("\033[31m-> Erreur interne lors du débannissement du membre.\033[0m\n"), 0);
                                } else {
                                    // Log en cas de succès
                                    send(cnx, "Débannissement du membre réussi\n", sizeof("Débannissement du membre réussi\n"), 0);

                                    snprintf(txt_log, sizeof(txt_log), "Débannissement du membre %d réussi par un admin", id_membre);
                                    insert_logs(api_key, client_ip, txt_log);

                                    printf("Débannissement réussie pour le membre ID %d par un admin\n", id_membre);
                                }
                            } else if (strcmp(user_info->new_user, "PRO") == 0) {
                                send(cnx, "\033[31m-> Erreur : Vous ne pouvez pas débannir un utilisateur\n\033[39m", sizeof("\033[31m-> Erreur : Vous ne pouvez pas débannir un utilisateur\n\033[39m"), 0);

                                snprintf(txt_log, sizeof(txt_log), "-> Erreur : Un professionnel ne peut pas débannir un utilisateur");
                                insert_logs(api_key, client_ip, txt_log);

                                printf("\033[31m-> Erreur : Un professionnel ne peut pas débannir un utilisateur\033[0m\n");
                            } else {
                                send(cnx, "\033[31m-> Erreur : Vous ne pouvez pas débannir un utilisateur\n\033[39m", sizeof("\033[31m-> Erreur : Vous ne pouvez pas débannir un utilisateur\n\033[39m"), 0);

                                snprintf(txt_log, sizeof(txt_log), "-> Erreur : Un membre ne peut pas débannir un utilisateur");
                                insert_logs(api_key, client_ip, txt_log);

                                printf("\033[31m-> Erreur : Un membre ne peut pas débannir un utilisateur\033[0m\n");
                            }

                        } else {
                            send(cnx, "\033[31m-> Erreur de format : UNBAN <id_membre>\n\033[39m", 57, 0);

                            snprintf(txt_log, sizeof(txt_log), "-> Erreur de format : UNBAN <id_membre>");
                            insert_logs(api_key, client_ip, txt_log);

                            printf("\033[31m-> Erreur Format\033[0m");
                        }

                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// BAN
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "BAN", 3) == 0) {
                        int id_membre;

                        if (sscanf(buffer, "BAN %d", &id_membre) == 1) {
                            if (strcmp(user_info->new_user, "ADMIN") == 0) {
                                // BAN admin -> membre
                                snprintf(query, sizeof(query),
                                         "insert into tripenarvor._banned (code_membre) values (%d);", id_membre);

                                // Exécution de la requête
                                if (!execute_query(conn, query, api_key, client_ip)) {
                                    send(cnx, "\033[31m-> Erreur interne lors du bannissement du membre.\033[0m\n", strlen("\033[31m-> Erreur interne lors du bannissement du membre.\033[0m\n"), 0);
                                } else {
                                    // Log en cas de succès
                                    send(cnx, "Bannissement du membre réussi\n", sizeof("Bannissement du membre réussi\n"), 0);

                                    snprintf(txt_log, sizeof(txt_log), "Bannissement du membre %d réussi par un admin", id_membre);
                                    insert_logs(api_key, client_ip, txt_log);

                                    printf("Bannissement réussie pour le membre ID %d par un admin\n", id_membre);
                                }
                            } else if (strcmp(user_info->new_user, "PRO") == 0) {
                                send(cnx, "\033[31m-> Erreur : Vous ne pouvez pas bannir un utilisateur\033[39m\n", sizeof("\033[31m-> Erreur : Vous ne pouvez pas bannir un utilisateur\n\033[39m"), 0);

                                snprintf(txt_log, sizeof(txt_log), "-> Erreur : Un professionnel ne peut pas bannir un utilisateur");
                                insert_logs(api_key, client_ip, txt_log);

                                printf("\033[31m-> Erreur : Un professionnel ne peut pas bannir un utilisateur\033[0\n");
                            } else {
                                send(cnx, "\033[31m-> Erreur : Vous ne pouvez pas bannir un utilisateur\n\033[39m", sizeof("\033[31m-> Erreur : Vous ne pouvez pas bannir un utilisateur\n\033[39m"), 0);

                                snprintf(txt_log, sizeof(txt_log), "-> Erreur : Un membre ne peut pas bannir un utilisateur");
                                insert_logs(api_key, client_ip, txt_log);

                                printf("\033[31m-> Erreur : Un membre ne peut pas bannir un utilisateur\033[0m\n");
                            }

                        } else {
                            send(cnx, "\033[31m-> Erreur de format : BAN <id_membre>\n\033[39m", 55, 0);

                            snprintf(txt_log, sizeof(txt_log), "-> Erreur de format : BAN <id_membre>");
                            insert_logs(api_key, client_ip, txt_log);

                            printf("\033[31m-> Erreur Format\033[0m");
                        }
                    } else {
                        send(cnx, "Commande inconnue\n", 19, 0);

                        snprintf(txt_log, sizeof(txt_log), "-> Erreur : Commande inconnue => %s", buffer);
                        insert_logs(api_key, client_ip, txt_log);
                    }
                } else {
                    perror("\033[31m-> Erreur lors de la lecture\033[0m");

                    snprintf(txt_log, sizeof(txt_log), "-> Erreur lors de la lecture");
                    insert_logs(api_key, client_ip, txt_log);

                    break;
                }
            }

        } else {
            send(cnx, "\033[31m-> Erreur lors de la génération du token\033[0m\n", sizeof("\033[31m-> Erreur lors de la génération du token\033[0m\n"), 0);

            snprintf(txt_log, sizeof(txt_log), "-> Erreur lors de la génération du token");
            insert_logs(api_key, client_ip, txt_log);
        }

        printf("\033[33mDéconnexion du client en cours...\033[0m\n");
        send(cnx, "\033[33mVous avez été déconnecté.\033[0m\n", strlen("\033[33mVous avez été déconnecté.\033[0m\n"), 0);
        close(cnx);  // Fermer le socket client
        printf("\033[33mClient déconnecté.\033[0m\n");

        snprintf(txt_log, sizeof(txt_log), "Client déconnecté.");
        insert_logs(api_key, client_ip, txt_log);
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