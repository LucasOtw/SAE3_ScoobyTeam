#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <arpa/inet.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <unistd.h>
#include <time.h>
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
    printf("===> Binding du socket\n");
    *ret = bind(*sock, (struct sockaddr *)addr, sizeof(*addr));
    if (*ret == -1) {
        perror("-> Erreur lors du bind ");
        close(*sock); // Ferme le socket en cas d'erreur
        return -1;    // Retourne une erreur
    }

    // Mise en écoute
    printf("==> Mise en écoute du socket\n");
    *ret = listen(*sock, 1);
    if (*ret == -1) {
        perror("-> Erreur lors de listen ");
        close(*sock); // Ferme le socket en cas d'erreur
        return -1;    // Retourne une erreur
    }

    return 0; // Retourne succès
}



UserInfo* generate_and_return_token(const char *api_key, PGconn *conn) {
    UserInfo *userInfo = malloc(sizeof(UserInfo)); // Allouer dynamiquement la structure
    if (userInfo == NULL) {
        perror("Erreur d'allocation mémoire pour UserInfo");
        return NULL;
    }
    memset(userInfo, 0, sizeof(UserInfo)); // Initialiser toute la mémoire à 0

    char query[512];

    if (strcmp(api_key, API_ADMIN) == 0 || api_key[0] == 'M' || api_key[0] == 'P') {
        if (strcmp(api_key, API_ADMIN) != 0) {
            char *generated_token = generate_token(); // Génère un token
            if (!generated_token) {
                fprintf(stderr, "Erreur lors de la génération du token\n");
                free(userInfo);
                return NULL;
            }

            if (api_key[0] == 'M') {
                strcpy(userInfo->new_user, "MEMBRE");

                snprintf(query, sizeof(query), "select code_compte from tripenarvor._membre where api_key = '%s'", api_key);
                PGresult *res = PQexec(conn, query);
                if (PQresultStatus(res) != PGRES_TUPLES_OK || PQntuples(res) == 0) {
                    fprintf(stderr, "La clé API n'existe pas : %s\n", PQerrorMessage(conn));
                    PQclear(res);
                    free(userInfo);
                    free(generated_token);
                    return NULL;
                }
                userInfo->id_user=atoi(PQgetvalue(res, 0, 0));
                PQclear(res);
            } else if (api_key[0] == 'P') {
                strcpy(userInfo->new_user, "PRO");

                snprintf(query, sizeof(query), "select code_compte from tripenarvor._professionnel where api_key = '%s'", api_key);
                PGresult *res = PQexec(conn, query);
                if (PQresultStatus(res) != PGRES_TUPLES_OK || PQntuples(res) == 0) {
                    fprintf(stderr, "La clé API n'existe pas : %s\n", PQerrorMessage(conn));
                    PQclear(res);
                    free(userInfo);
                    free(generated_token);
                    return NULL;
                }
                userInfo->id_user=atoi(PQgetvalue(res, 0, 0));
                PQclear(res);
            }

            snprintf(query, sizeof(query),
                     "insert into tripenarvor._token (api_key, token, type_utilisateur, created_at, expires_at)"
                     "values ('%s', '%s', '%s', NOW(), NOW() + INTERVAL '1 day') returning token;", 
                     api_key, generated_token, userInfo->new_user);

            PGresult *res = PQexec(conn, query);
            free(generated_token);
            if (PQresultStatus(res) != PGRES_TUPLES_OK || PQntuples(res) == 0) {
                fprintf(stderr, "Échec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
                PQclear(res);
                free(userInfo);
                return NULL;
            }

            snprintf(userInfo->token, sizeof(userInfo->token), "%s", PQgetvalue(res, 0, 0));
            PQclear(res);
        } else {
            snprintf(userInfo->token, sizeof(userInfo->token), "a1");
            strcpy(userInfo->new_user, "ADMIN");
        }

        return userInfo; // Retourner l'adresse de la structure allouée
    }

    free(userInfo); // Nettoyage en cas d'échec
    return NULL;
}





void insert_logs(const char *api_key, const char *ip_address, const char *message) {
    if (LOG_LINKS == NULL) {
        fprintf(stderr, "Erreur : LOG_LINKS n'est pas défini dans config.h\n");
        return;
    }

    FILE *log_file = fopen(LOG_LINKS, "a"); // Ouverture en mode ajout
    if (log_file == NULL) {
        perror("Erreur lors de l'ouverture du fichier de log");
        return;
    }

    // Obtenir la date et l'heure actuelles
    time_t now = time(NULL);
    struct tm *time_info = localtime(&now);

    if (time_info == NULL) {
        perror("Erreur lors de la récupération de la date et de l'heure");
        fclose(log_file);
        return;
    }

    char timestamp[20];
    strftime(timestamp, sizeof(timestamp), "%Y-%m-%d %H:%M:%S", time_info);

    // Déterminer les valeurs par défaut pour l'IP et la clé API si elles sont absentes
    const char *final_api_key = (api_key != NULL && strlen(api_key) > 0) ? api_key : "UNKNOWN_API_KEY";
    const char *final_ip_address = (ip_address != NULL && strlen(ip_address) > 0) ? ip_address : "UNKNOWN_IP";

    // Écrire la ligne de log dans le fichier
    fprintf(log_file, "[%s] API_KEY: %s, IP: %s, Message: %s\n", timestamp, final_api_key, final_ip_address, message);

    fclose(log_file); // Fermer le fichier
}