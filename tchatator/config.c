#include "config.h"

#include <arpa/inet.h>
#include <libpq-fe.h>  // Bibliothèque PostgreSQL
#include <netinet/in.h>
#include <stdbool.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/socket.h>
#include <time.h>
#include <unistd.h>

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
    char txt_log[256];

    // Création du socket
    printf("====> Préparation du socket\n");
    *sock = socket(AF_INET, SOCK_STREAM, 0);
    if (*sock == -1) {
        perror("\033[31m-> Erreur sur le socket \033[0m");

        snprintf(txt_log, sizeof(txt_log), "-> Erreur sur le socket");
        insert_logs(NULL, NULL, txt_log);

        return -1;  // Retourne une erreur
    }

    // Configuration de l'adresse
    addr->sin_addr.s_addr = inet_addr("127.0.0.1");
    addr->sin_family = AF_INET;
    addr->sin_port = htons(SERVER_PORT);

    // Liaison du socket
    printf("===> Binding du socket\n");
    *ret = bind(*sock, (struct sockaddr *)addr, sizeof(*addr));
    if (*ret == -1) {
        perror("\033[31m-> Erreur lors du bind \033[0m");

        snprintf(txt_log, sizeof(txt_log), "-> Erreur lors du bind");
        insert_logs(NULL, NULL, txt_log);

        close(*sock);  // Ferme le socket en cas d'erreur
        return -1;     // Retourne une erreur
    }

    // Mise en écoute
    printf("==> Mise en écoute du socket\n");
    *ret = listen(*sock, 1);
    if (*ret == -1) {
        perror("\033[31m-> Erreur lors de listen \033[0m");

        snprintf(txt_log, sizeof(txt_log), "-> Erreur lors de listen");
        insert_logs(NULL, NULL, txt_log);

        close(*sock);  // Ferme le socket en cas d'erreur
        return -1;     // Retourne une erreur
    }

    return 0;  // Retourne succès
}

UserInfo *generate_and_return_token(const char *api_key, PGconn *conn) {
    UserInfo *userInfo = malloc(sizeof(UserInfo));  // Allouer dynamiquement la structure
    if (userInfo == NULL) {
        perror("\033[31m-> Erreur d'allocation mémoire pour UserInfo\033[0m");
        return NULL;
    }
    memset(userInfo, 0, sizeof(UserInfo));  // Initialiser toute la mémoire à 0

    char query[512], txt_log[256];

    if (strcmp(api_key, API_ADMIN) == 0 || api_key[0] == 'M' || api_key[0] == 'P') {
        if (strcmp(api_key, API_ADMIN) != 0) {
            char *generated_token = generate_token();  // Génère un token
            if (!generated_token) {
                fprintf(stderr, "\033[31m-> Erreur lors de la génération du token\033[0m\n");

                snprintf(txt_log, sizeof(txt_log), "-> Erreur lors de la génération du token");
                insert_logs(NULL, NULL, txt_log);

                free(userInfo);
                return NULL;
            }

            if (api_key[0] == 'M') {
                strcpy(userInfo->new_user, "MEMBRE");

                snprintf(query, sizeof(query), "select code_compte from tripenarvor._membre where api_key = '%s'", api_key);
                PGresult *res = PQexec(conn, query);
                if (PQresultStatus(res) != PGRES_TUPLES_OK || PQntuples(res) == 0) {
                    fprintf(stderr, "\033[31m-> Erreur : La clé API n'existe pas\033[0m\n");

                    snprintf(txt_log, sizeof(txt_log), "-> Erreur : La clé API n'existe pas");
                    insert_logs(NULL, NULL, txt_log);

                    PQclear(res);
                    free(userInfo);
                    free(generated_token);
                    return NULL;
                }
                userInfo->id_user = atoi(PQgetvalue(res, 0, 0));
                PQclear(res);
            } else if (api_key[0] == 'P') {
                strcpy(userInfo->new_user, "PRO");

                snprintf(query, sizeof(query), "select code_compte from tripenarvor._professionnel where api_key = '%s'", api_key);
                PGresult *res = PQexec(conn, query);
                if (PQresultStatus(res) != PGRES_TUPLES_OK || PQntuples(res) == 0) {
                    fprintf(stderr, "\033[31m-> Erreur : La clé API n'existe pas\033[0m\n");

                    snprintf(txt_log, sizeof(txt_log), "-> Erreur : La clé API n'existe pas");
                    insert_logs(NULL, NULL, txt_log);

                    PQclear(res);
                    free(userInfo);
                    free(generated_token);
                    return NULL;
                }
                userInfo->id_user = atoi(PQgetvalue(res, 0, 0));
                PQclear(res);
            }

            snprintf(query, sizeof(query),
                     "insert into tripenarvor._token (api_key, token, type_utilisateur, created_at, expires_at)"
                     "values ('%s', '%s', '%s', NOW(), NOW() + INTERVAL '1 hour') returning token;",
                     api_key, generated_token, userInfo->new_user);

            PGresult *res = PQexec(conn, query);
            if (PQresultStatus(res) != PGRES_TUPLES_OK || PQntuples(res) == 0) {
                fprintf(stderr, "\033[31m-> Échec de l'exécution de la requête : %s\033[0m\n", PQerrorMessage(conn));

                snprintf(txt_log, sizeof(txt_log), "-> Échec de l'exécution de la requête : %s\033[0m\n", PQerrorMessage(conn));
                insert_logs(NULL, NULL, txt_log);

                PQclear(res);
                free(userInfo);
                return NULL;
            }

            snprintf(userInfo->token, sizeof(userInfo->token), "%s", PQgetvalue(res, 0, 0));
            free(generated_token);
            PQclear(res);
        } else {
            snprintf(userInfo->token, sizeof(userInfo->token), "a1");
            strcpy(userInfo->new_user, "ADMIN");
        }

        return userInfo;  // Retourner l'adresse de la structure allouée
    }

    free(userInfo);  // Nettoyage en cas d'échec
    return NULL;
}

void insert_logs(const char *api_key, const char *ip_address, const char *message) {
    if (LOG_LINKS == NULL) {
        fprintf(stderr, "\033[33m-> Erreur : LOG_LINKS n'est pas défini dans config.h\033[0m\n");
        return;
    }

    FILE *log_file = fopen(LOG_LINKS, "a");  // Ouverture en mode ajout
    if (log_file == NULL) {
        perror("\033[33m-> Erreur lors de l'ouverture du fichier de log\033[0m");
        return;
    }

    // Obtenir la date et l'heure actuelles
    time_t now = time(NULL);
    struct tm *time_info = localtime(&now);

    if (time_info == NULL) {
        perror("\033[33m-> Erreur lors de la récupération de la date et de l'heure\033[0m");
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

    fclose(log_file);  // Fermer le fichier
}

bool is_token_valid(PGconn *conn, const char *token, char *log_message, size_t log_size) {
    if (conn == NULL || token == NULL) {
        snprintf(log_message, log_size, "-> Erreur : Connexion à la base de données ou token invalide.");
        return false;
    }

    char query[256];
    snprintf(query, sizeof(query), "SELECT is_active, expires_at FROM tripenarvor._token WHERE token = '%s'", token);

    // Exécuter la requête SQL
    PGresult *res = PQexec(conn, query);
    if (PQresultStatus(res) != PGRES_TUPLES_OK) {
        snprintf(log_message, log_size, "-> Erreur lors de l'exécution de la requête : %s", PQerrorMessage(conn));
        PQclear(res);
        return false;
    }

    // Vérifier si le token existe dans la base de données
    if (PQntuples(res) == 0) {
        snprintf(log_message, log_size, "-> Erreur : Aucun token trouvé avec la valeur donnée.");
        PQclear(res);
        return false;
    }

    // Récupérer les valeurs depuis le résultat de la requête
    char *is_active_str = PQgetvalue(res, 0, 0);
    char *expires_at_str = PQgetvalue(res, 0, 1);

    bool is_active = (strcmp(is_active_str, "t") == 0);  // "t" représente TRUE dans PostgreSQL
    struct tm expires_at;

    // Convertir expires_at_str en struct tm
    if (strptime(expires_at_str, "%Y-%m-%d %H:%M:%S", &expires_at) == NULL) {
        snprintf(log_message, log_size, "-> Erreur : Impossible de parser la date expires_at : %s", expires_at_str);
        PQclear(res);
        return false;
    }

    // Convertir expires_at en time_t (UTC)
    time_t expires_at_time = timegm(&expires_at);  // Utilise timegm pour UTC
    if (expires_at_time == -1) {
        snprintf(log_message, log_size, "-> Erreur : Impossible de convertir expires_at en time_t.");
        PQclear(res);
        return false;
    }

    // Obtenir l'heure actuelle en UTC
    time_t now = time(NULL);  // Temps actuel en epoch
    if (now == -1) {
        snprintf(log_message, log_size, "-> Erreur lors de la récupération de l'heure actuelle.");
        PQclear(res);
        return false;
    }

    // Comparer les dates en UTC
    if (is_active && difftime(expires_at_time, now) > 0) {
        snprintf(log_message, log_size, "Le token est valide jusqu'à %s.", expires_at_str);
        PQclear(res);
        return true;
    } else {
        snprintf(log_message, log_size, "-> Erreur : Le token est expiré ou inactif.");

        snprintf(query, sizeof(query), "UPDATE tripenarvor._token SET is_active = false WHERE token = '%s'", token);
        PGresult *res = PQexec(conn, query);

        PQclear(res);
        return false;
    }
}