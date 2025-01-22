#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <stdbool.h>
#include <libpq-fe.h> // Bibliothèque PostgreSQL

#include "config.h"
#include "bdd.h"




int main()
{
    int sock, cnx, ret, size;
    struct sockaddr_in addr, conn_addr;

    char buffer[BUFFER_SIZE], query[1024];; // Buffer pour lire les données
    int len;
    int ping_count = 0;

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

    while(1) // while(1) pour garder le serveur allume malgre que le client quitte
    {
    
        printf("=>En attente d'une connexion...\n");
        size = sizeof(conn_addr);
        cnx = accept(sock, (struct sockaddr *)&conn_addr, (socklen_t *)&size);
        if (cnx == -1)
        {
            perror("->Erreur lors de accept ");
            PQfinish(conn);
            exit(-1);
        }

        printf("Connexion acceptée\n");
        memset(buffer, 0, sizeof(buffer));


        printf("En attente de la cle API...\n");
        len = recv(cnx, buffer, sizeof(buffer) - 1, 0);
        if (len == -1)
        {
            perror("Erreur lors de recv ");
            PQfinish(conn);
            exit(-1);
        }

        buffer[LEN_API] = '\0'; 
        printf("Cle API reçu du client : %s\n", buffer);


        UserInfo* user_info = generate_and_return_token(buffer, conn);
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

                        snprintf(query, sizeof(query), "update tripenarvor._token set is_active = FALSE where token = '%s'", user_info->token);
                        // printf("Requête SQL exécutée : %s\n", query);

                        PGresult *res = PQexec(conn, query);
                        if (PQresultStatus(res) != PGRES_COMMAND_OK) {
                            fprintf(stderr, "Échec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
                            PQclear(res); // Libérer la mémoire allouée pour le résultat
                            PQfinish(conn); // Libérer la mémoire allouée pour la connexion
                            return EXIT_FAILURE;
                        }

                        break;
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// REGEN.................................................................................
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "REGEN", 5) == 0) {

                        snprintf(query, sizeof(query), "select id_token, is_active, api_key from tripenarvor._token where token = '%s'", user_info->token);
                        // printf("Requête SQL exécutée : %s\n", query);

                        PGresult *res = PQexec(conn, query);
                        if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                            fprintf(stderr, "Échec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
                            PQclear(res);
                            return EXIT_FAILURE;
                        }

                        bool active_token;                  // État actif/inactif du token
                        char my_api_key[36];                // Clé API associée au token
                        char type_user_bdd[15];             // Nom de la table cible (membre ou professionnel)

                        // Extraction des colonnes spécifiques
                        active_token = PQgetvalue(res, 0, 1); // is_active
                        strcpy(my_api_key, PQgetvalue(res, 0, 2)); // api_key

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
                            snprintf(query, sizeof(query),
                                    "update tripenarvor._%s set api_key = tripenarvor.generate_api_key('%s') where api_key = '%s'",
                                    type_user_bdd, first_char, my_api_key);

                            // printf("Requête SQL exécutée : %s\n", query);

                            PGresult *res_update = PQexec(conn, query);
                            if (PQresultStatus(res_update) != PGRES_COMMAND_OK) {
                                fprintf(stderr, "Échec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
                                PQclear(res_update);
                                return EXIT_FAILURE;
                            }
                            PQclear(res_update);
                        } else {
                            send(cnx, "Votre token n'est plus valide\n", 31, 0);
                        }

                    }
                    //////////////////////////////////////////////////////////////////////////////////////////
                    /// MSG...................................................................................
                    //////////////////////////////////////////////////////////////////////////////////////////
                    else if (strncmp(buffer, "MSG", 3) == 0) {
                        // SELECT CLÉ API
                        snprintf(query, sizeof(query), "select api_key from tripenarvor._token where token = '%s'", user_info->token);
                        // printf("Requête SQL exécutée : %s\n", query);

                        PGresult *res = PQexec(conn, query);
                        if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                            fprintf(stderr, "Échec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
                            PQclear(res);
                            return EXIT_FAILURE;
                        }

                        char my_api_key[36]; // Clé API associée au token

                        // Extraction des colonnes spécifiques
                        strcpy(my_api_key, PQgetvalue(res, 0, 2)); // api_key

                        PQclear(res);

                        int id_dest;
                        char txt[1000];

                        snprintf(query, sizeof(query),
                                    "select code_compte from tripenarvor._membre Natural JOIN tripenarvor._token where api_key = '%s'", my_api_key);
                        PGresult *res_update = PQexec(conn, query);

                        int id_emetteur = atoi(PQgetvalue(res_update, 0, 0));

                        PQclear(res_update);

                        if (sscanf(buffer, "MSG %d %s", &id_dest, txt) == 1) {
                            // ENVOI DU MSG
                            snprintf(query, sizeof(query),
                                    "insert into tripenarvor._message (code_emetteur, code_destinataire, contenu) values (%d, %d, %s)",
                                    id_emetteur, id_dest, txt);


                            PGresult *res_update = PQexec(conn, query);
                            if (PQresultStatus(res_update) != PGRES_COMMAND_OK) {
                                fprintf(stderr, "Échec de l'enregistrement du message : %s\n", PQerrorMessage(conn));
                                PQclear(res_update);
                                return EXIT_FAILURE;
                            }
                            PQclear(res_update);



                        } else {
                            send(cnx, "Erreur de format : MSG <id_destinataire> '<contenu du message>'\n", 17, 0);
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
            send(cnx, "Erreur lors de la génération du token\n", 36, 0);
        }

        printf("Déconnexion du client en cours...\n");
        send(cnx, "Vous avez été déconnecté.\n", strlen("Vous avez été déconnecté.\n"), 0);
        close(cnx); // Fermer le socket client
        printf("Client déconnecté.\n");
    }


    PQfinish(conn);

    return 0;
}