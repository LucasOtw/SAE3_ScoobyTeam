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
    char conninfo[256];
    snprintf(conninfo, sizeof(conninfo), "host=%s port=%d dbname=%s user=%s password=%s", 
             host, port, dbname, user, password);

    // Créer une connexion à la base de données
    PGconn *conn = PQconnectdb(conninfo);

    // Vérifier l'état de la connexion
    if (PQstatus(conn) != CONNECTION_OK) {
        fprintf(stderr, "Échec de la connexion à la base de données : %s\n", PQerrorMessage(conn));
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
        PQclear(res);
        snprintf(txt_log, sizeof(txt_log), "-> Échec de la requête SQL : %s\n%s\n", query, PQerrorMessage(conn));
        insert_logs(api_key, client_ip, txt_log);
        return false;
    }
    PQclear(res);
    return true;
}