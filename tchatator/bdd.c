#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <stdbool.h>
#include <libpq-fe.h> // Bibliothèque PostgreSQL

#include "bdd.h"
#include "config.h"



PGconn* connect_to_db() {
    // Informations de connexion codées en dur
    const char *host = "scooby-team.ventsdouest.dev";
    int port = 5432;
    const char *dbname = "sae";
    const char *user = "sae";
    const char *password = "philly-Congo-bry4nt";

    // Construire la chaîne de connexion
    char conninfo[256], txt_log[256];
    snprintf(conninfo, sizeof(conninfo), "host=%s port=%d dbname=%s user=%s password=%s", 
             host, port, dbname, user, password);

    // Créer une connexion à la base de données
    PGconn *conn = PQconnectdb(conninfo);

    // Vérifier l'état de la connexion
    if (PQstatus(conn) != CONNECTION_OK) {
        fprintf(stderr, "\033[33m-> Échec de la connexion à la base de données : %s\033[0m\n", PQerrorMessage(conn));

        snprintf(txt_log, sizeof(txt_log), "-> Échec de la connexion à la base de données : %s", PQerrorMessage(conn));
        insert_logs(NULL, NULL, txt_log);

        PQfinish(conn); // Libérer la mémoire allouée pour la connexion
        return NULL;
    }

    return conn;
}



char* generate_token() {
    // Définir le jeu de caractères possibles pour le token
    const char charset[] = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    int charset_size = sizeof(charset) - 1; // -1 pour exclure le caractère de fin de chaîne '\0'

    // Allouer de la mémoire pour le token
    char *token = (char *)malloc(5 * sizeof(char));  // 4 caractères + '\0'

    // Initialiser le générateur de nombres aléatoires
    srand(time(NULL));

    // Générer un token de 4 caractères
    for (int i = 0; i < 4; i++) {
        token[i] = charset[rand() % charset_size]; // Choisir un caractère au hasard
    }
    token[4] = '\0'; // Ajouter un caractère nul à la fin pour en faire une chaîne de caractères valide

    return token;
}



// Fonction utilitaire pour exécuter une requête PostgreSQL et vérifier le résultat
bool execute_query(PGconn *conn, const char *query, char *api_key, char *client_ip) {
    char txt_log[256];
    PGresult *res = PQexec(conn, query);
    if (PQresultStatus(res) != PGRES_COMMAND_OK) {
        fprintf(stderr, "\033[31m-> Échec de la requête SQL : %s\n%s\033[0m\n", query, PQerrorMessage(conn));
        
        snprintf(txt_log, sizeof(txt_log), "-> Échec de la requête SQL : %s\n%s", query, PQerrorMessage(conn));
        insert_logs(api_key, client_ip, txt_log);

        PQclear(res);
        return false;
    }
    PQclear(res);
    return true;
}



bool is_member_blocked_all(PGconn *conn, int code_membre, char *log_message, size_t log_size) {
    if (conn == NULL) {
        snprintf(log_message, log_size, "-> Erreur : Connexion à la base de données invalide.");
        return false;
    }

    char query[256];
    snprintf(query, sizeof(query), 
             "SELECT expires_at FROM tripenarvor._blocked_all "
             "WHERE code_membre = %d", 
             code_membre);

    PGresult *res = PQexec(conn, query);
    if (PQresultStatus(res) != PGRES_TUPLES_OK) {
        snprintf(log_message, log_size, "-> Erreur exécution requête : %s", PQerrorMessage(conn));
        PQclear(res);
        return false;
    }

    if (PQntuples(res) == 0) {
        snprintf(log_message, log_size, "-> Aucun blocage trouvée pour le membre.");
        PQclear(res);
        return false; 
    }

    char *expires_at_str = PQgetvalue(res, 0, 0);
    struct tm expires_at;

    if (strptime(expires_at_str, "%Y-%m-%d %H:%M:%S", &expires_at) == NULL) {
        snprintf(log_message, log_size, "-> Erreur parsing expires_at : %s", expires_at_str);
        PQclear(res);
        return false;
    }

    PQclear(res);

    time_t expires_at_time = timegm(&expires_at);
    if (expires_at_time == -1) {
        snprintf(log_message, log_size, "-> Erreur conversion expires_at en time_t.");
        return false;
    }

    time_t now = time(NULL);
    struct tm now_tm;
    gmtime_r(&now, &now_tm);
    now = mktime(&now_tm);

    if (difftime(expires_at_time, now) > 0) {
        snprintf(log_message, log_size, "Le membre %d est encore bloqué globalement jusqu'à %s.", 
                 code_membre, expires_at_str);
        return true;  
    }

    snprintf(query, sizeof(query), 
             "DELETE FROM tripenarvor._blocked_all WHERE code_membre = %d", code_membre);

    res = PQexec(conn, query);
    if (PQresultStatus(res) != PGRES_COMMAND_OK) {
        snprintf(log_message, log_size, "-> Erreur suppression blocage : %s", PQerrorMessage(conn));
    } else {
        snprintf(log_message, log_size, "Blocage expiré supprimé pour le membre %d.", code_membre);
    }

    PQclear(res);
    return false;
}




bool is_member_blocked_for(PGconn *conn, int code_membre, int code_professionnel, char *log_message, size_t log_size) {
    if (conn == NULL) {
        snprintf(log_message, log_size, "-> Erreur : Connexion à la base de données invalide.");
        return false;
    }

    char query[256];
    snprintf(query, sizeof(query), 
             "SELECT expires_at FROM tripenarvor._blocked_for "
             "WHERE code_membre = %d AND code_professionnel = %d", 
             code_membre, code_professionnel);

    PGresult *res = PQexec(conn, query);
    if (PQresultStatus(res) != PGRES_TUPLES_OK) {
        snprintf(log_message, log_size, "-> Erreur exécution requête : %s", PQerrorMessage(conn));
        PQclear(res);
        return false;
    }

    if (PQntuples(res) == 0) {
        snprintf(log_message, log_size, "Aucun blocage trouvée pour le membre.");
        PQclear(res);
        return false; 
    }

    char *expires_at_str = PQgetvalue(res, 0, 0);
    struct tm expires_at;

    if (strptime(expires_at_str, "%Y-%m-%d %H:%M:%S", &expires_at) == NULL) {
        snprintf(log_message, log_size, "-> Erreur parsing expires_at : %s", expires_at_str);
        PQclear(res);
        return false;
    }
    
    PQclear(res);

    time_t expires_at_time = timegm(&expires_at);
    if (expires_at_time == -1) {
        snprintf(log_message, log_size, "-> Erreur conversion expires_at en time_t.");
        return false;
    }

    time_t now = time(NULL);
    struct tm now_tm;
    gmtime_r(&now, &now_tm);
    now = mktime(&now_tm);

    if (difftime(expires_at_time, now) > 0) {
        snprintf(log_message, log_size, "-> Erreur : Le membre %d est encore bloqué par le professionnel %d jusqu'à %s.", 
                 code_membre, code_professionnel, expires_at_str);
        return true;  
    }

    snprintf(query, sizeof(query), 
             "DELETE FROM tripenarvor._blocked_for "
             "WHERE code_membre = %d AND code_professionnel = %d", 
             code_membre, code_professionnel);

    res = PQexec(conn, query);
    if (PQresultStatus(res) != PGRES_COMMAND_OK) {
        snprintf(log_message, log_size, "-> Erreur suppression blocage : %s", PQerrorMessage(conn));
    } else {
        snprintf(log_message, log_size, "Blocage expiré supprimé pour le membre %d par le professionnel %d.", 
                 code_membre, code_professionnel);
    }

    PQclear(res);
    return false;
}




bool is_member_banned(PGconn *conn, int code_membre, char *log_message, size_t log_size) {
    if (conn == NULL) {
        snprintf(log_message, log_size, "-> Erreur : Connexion à la base de données invalide.");
        return false;
    }

    char query[256];
    snprintf(query, sizeof(query), 
             "SELECT * FROM tripenarvor._banned "
             "WHERE code_membre = %d", 
             code_membre);

    PGresult *res = PQexec(conn, query);
    if (PQresultStatus(res) != PGRES_TUPLES_OK) {
        snprintf(log_message, log_size, "-> Erreur exécution requête : %s", PQerrorMessage(conn));
        PQclear(res);
        return false;
    }

    if (PQntuples(res) == 0) {
        snprintf(log_message, log_size, "Aucun bannissement trouvée pour le membre.");
        PQclear(res);
        return false; 
    }

    snprintf(log_message, log_size, "-> Erreur : Le membre %d est banni.", code_membre);

    PQclear(res);
    return true;  
}
