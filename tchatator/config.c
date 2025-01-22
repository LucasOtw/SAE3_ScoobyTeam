#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <arpa/inet.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <unistd.h>
#include <libpq-fe.h> // Bibliothèque PostgreSQL

#include "config.h"
#include "bdd.h"



char SERVER_IP[16];
int SERVER_PORT;
int NB_BACKLOG;
int BAN_DUR;
int NB_MESS;
int LEN_MESS;
int BUFFER_SIZE;
int MAX_MIN;
int MAX_HOUR;
char LOG_LINKS[256];
char API_ADMIN[36];
int LEN_API;



// Fonction pour extraire les valeurs depuis le fichier param.txt
int load_config(const char *filename) {
    FILE *file = fopen(filename, "r");
    if (file == NULL) {
        perror("Erreur d'ouverture du fichier de configuration");
        return -1;
    }

    char line[256];
    while (fgets(line, sizeof(line), file)) {
        // Ignorer les lignes commentées
        if (line[0] == '#' || line[0] == '\n') {
            continue;
        }

        // Extraction des variables à partir des lignes du fichier
        if (strstr(line, "SERVER_IP") != NULL) {
            sscanf(line, "SERVER_IP=%s", SERVER_IP);
        } else if (strstr(line, "SERVER_PORT") != NULL) {
            sscanf(line, "SERVER_PORT=%d", &SERVER_PORT);
        } else if (strstr(line, "NB_BACKLOG") != NULL) {
            sscanf(line, "NB_BACKLOG=%d", &NB_BACKLOG);
        } else if (strstr(line, "BAN_DUR") != NULL) {
            sscanf(line, "BAN_DUR=%d", &BAN_DUR);
        } else if (strstr(line, "NB_MESS") != NULL) {
            sscanf(line, "NB_MESS=%d", &NB_MESS);
        } else if (strstr(line, "LEN_MESS") != NULL) {
            sscanf(line, "LEN_MESS=%d", &LEN_MESS);
        } else if (strstr(line, "BUFFER_SIZE") != NULL) {
            sscanf(line, "BUFFER_SIZE=%d", &BUFFER_SIZE);
        } else if (strstr(line, "MAX_MIN") != NULL) {
            sscanf(line, "MAX_MIN=%d", &MAX_MIN);
        } else if (strstr(line, "MAX_HOUR") != NULL) {
            sscanf(line, "MAX_HOUR=%d", &MAX_HOUR);
        } else if (strstr(line, "LOG_LINKS") != NULL) {
            sscanf(line, "LOG_LINKS=%s", LOG_LINKS);
        } else if (strstr(line, "API_ADMIN") != NULL) {
            sscanf(line, "API_ADMIN=%s", API_ADMIN);
        } else if (strstr(line, "LEN_API") != NULL) {
            sscanf(line, "LEN_API=%d", &LEN_API);
        }
    }

    fclose(file);
    return 0;
}



// Fonction pour préparer et configurer le socket
int prepare_socket(int *ret, int *sock, struct sockaddr_in *addr) {
    // Création du socket
    printf("====> Préparation du socket\n");
    *sock = socket(AF_INET, SOCK_STREAM, 0);
    if (*sock == -1) {
        perror("-> Erreur sur le socket ");
        return -1; // Retourne une erreur
    }

    // Configuration de l'adresse
    addr->sin_addr.s_addr = inet_addr("127.0.0.1");
    addr->sin_family = AF_INET;
    addr->sin_port = htons(SERVER_PORT);

    // Liaison du socket
    printf("====> Binding du socket\n");
    *ret = bind(*sock, (struct sockaddr *)addr, sizeof(*addr));
    if (*ret == -1) {
        perror("-> Erreur lors du bind ");
        close(*sock); // Ferme le socket en cas d'erreur
        return -1;    // Retourne une erreur
    }

    // Mise en écoute
    printf("====> Mise en écoute du socket\n");
    *ret = listen(*sock, 1);
    if (*ret == -1) {
        perror("-> Erreur lors de listen ");
        close(*sock); // Ferme le socket en cas d'erreur
        return -1;    // Retourne une erreur
    }

    return 0; // Retourne succès
}


const char* generate_and_return_token(const char *buffer, PGconn *conn) {
    static char token[50]; // Taille suffisante pour contenir le token
    char query[512];  // Déclare la variable query avec une taille suffisante
    char new_user[10];

    if (strcmp(buffer, API_ADMIN) == 0 || buffer[0] == 'M' || buffer[0] == 'P') {
        if (strcmp(buffer, API_ADMIN) != 0) {
            char *generated_token = generate_token();  // Génère un token

            if (buffer[0] == 'M') {
                strcpy(new_user, "MEMBRE");

                snprintf(query, sizeof(query), "select * from tripenarvor._membre where api_key = '%s'", buffer);
                PGresult *res = PQexec(conn, query);
                if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                    perror("La clé API n'existe pas ");
                    PQclear(res);
                    return NULL;
                }
                PQclear(res);
            } else if (buffer[0] == 'P') {
                strcpy(new_user, "PRO");

                snprintf(query, sizeof(query), "select * from tripenarvor._professionnel where api_key = '%s'", buffer);
                PGresult *res = PQexec(conn, query);
                if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                    perror("La clé API n'existe pas ");
                    PQclear(res);
                    return NULL;
                }
                PQclear(res);
            }

            // Insertion du token dans la base de données
            snprintf(query, sizeof(query),
                     "insert into tripenarvor._token (api_key, token, type_utilisateur, created_at, expires_at)"
                     "values ('%s', '%s', '%s', NOW(), NOW() + INTERVAL '1 day') returning token;", buffer, generated_token, new_user);

            free(generated_token);
            PGresult *res = PQexec(conn, query);
            if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                fprintf(stderr, "Échec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
                PQclear(res);
                return NULL;
            }

            // Récupérer le token de la requête SQL
            snprintf(token, sizeof(token), "%s", PQgetvalue(res, 0, 0));
            PQclear(res);
        } else {
            snprintf(token, sizeof(token), "a1");
        }

        return token;
    }
    
    return NULL;  // Si la condition initiale n'est pas remplie, retourner NULL
}