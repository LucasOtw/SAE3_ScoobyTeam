#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/socket.h>
#include <stdbool.h>
#include <unistd.h>
#include <libpq-fe.h>

#include "config.h"
#include "bdd.h"
#include "fonct.h"




int disconnect_user(UserInfo *user_info, PGconn *conn, int cnx) {
    // Envoi du message de déconnexion
    const char *message = "Connexion terminée\n";
    if (send(cnx, message, strlen(message), 0) == -1) {
        perror("Erreur lors de l'envoi du message de connexion terminée");
        return -1; // Erreur d'envoi
    }

    // Mise à jour de la base de données
    char query[512];
    snprintf(query, sizeof(query), "UPDATE tripenarvor._token SET is_active = FALSE WHERE token = '%s'", user_info->token);
    PGresult *res = PQexec(conn, query);
    if (PQresultStatus(res) != PGRES_COMMAND_OK) {
        fprintf(stderr, "Échec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
        PQclear(res); // Libérer la mémoire PostgreSQL
        return -1; // Échec de la requête
    }
    PQclear(res);

    // Libération sécurisée de la mémoire de l'utilisateur
    // if (user_info != NULL) {
    //     free(user_info);
    //     // user_info = NULL;
    // }

    // Fermeture sécurisée du socket client
    if (cnx >= 0) {
        close(cnx);
        cnx = -1;
    }

    return 0; // Succès
}



int handle_token_update(UserInfo *user_info, PGconn *conn, int cnx) {
    char query[512];

    // Préparer la requête pour récupérer les informations sur le token
    snprintf(query, sizeof(query), "SELECT id_token, is_active, api_key FROM tripenarvor._token WHERE token = '%s'", user_info->token);
    PGresult *res = PQexec(conn, query);

    // Vérifier l'exécution de la requête
    if (PQresultStatus(res) != PGRES_TUPLES_OK) {
        fprintf(stderr, "\nÉchec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
        PQclear(res);
        return -1;
    }

    // Variables pour stocker les données récupérées
    bool active_token;
    char my_api_key[36];
    char type_user_bdd[15];

    // Extraire les données des colonnes
    active_token = strcmp(PQgetvalue(res, 0, 1), "t") == 0; // 't' pour TRUE en PostgreSQL
    strcpy(my_api_key, PQgetvalue(res, 0, 2)); // Clé API

    // Déterminer la table utilisateur en fonction du type de l'utilisateur
    if (strcmp(user_info->new_user, "MEMBRE") == 0) {
        strcpy(type_user_bdd, "membre");
    } else if (strcmp(user_info->new_user, "PRO") == 0) {
        strcpy(type_user_bdd, "professionnel");
    } else {
        fprintf(stderr, "Type utilisateur inconnu : %s\n", user_info->new_user);
        PQclear(res);
        return -1;
    }

    if (active_token) {
        // Générer la première lettre pour l'API
        char first_char[2] = {user_info->new_user[0], '\0'};

        send(cnx, "Votre clé API a été changée\n", strlen("Votre clé API a été changée\n"), 0);
        printf("Nouvelle clé API générée\n");

        // Préparer la requête pour mettre à jour la clé API
        snprintf(query, sizeof(query),
                 "UPDATE tripenarvor._%s SET api_key = tripenarvor.generate_api_key('%s') WHERE api_key = '%s'",
                 type_user_bdd, first_char, my_api_key);

        PGresult *res_update = PQexec(conn, query);

        // Vérifier l'exécution de la requête de mise à jour
        if (PQresultStatus(res_update) != PGRES_COMMAND_OK) {
            fprintf(stderr, "\nÉchec de l'exécution de la requête : %s\n", PQerrorMessage(conn));
            PQclear(res_update);
            PQclear(res);
            return -1;
        }

        PQclear(res_update);
    } else {
        send(cnx, "Votre token n'est plus valide\n", strlen("Votre token n'est plus valide\n"), 0);
    }

    PQclear(res); // Nettoyer le résultat
    return 0; // Succès
}
