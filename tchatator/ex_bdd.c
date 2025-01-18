#include <stdio.h>
#include <stdlib.h>
#include <libpq-fe.h>  // Bibliothèque PostgreSQL

int main() {
    const char *conninfo = "host=scooby-team.ventsdouest.dev port=5432 dbname=sae user=sae password=philly-Congo-bry4nt";
    PGconn *conn = NULL;
    PGresult *res = NULL;

    // Connexion à la base de données PostgreSQL
    conn = PQconnectdb(conninfo);
    printf("%d", PQstatus(conn) != CONNECTION_OK);
    // Vérification de la connexion
    if (PQstatus(conn) != CONNECTION_OK) {
        fprintf(stderr, "Échec de la connexion : %s", PQerrorMessage(conn));
        PQfinish(conn);
        exit(EXIT_FAILURE);
    }

    printf("Connexion réussie à PostgreSQL\n");

    // Exécution d'une requête
    res = PQexec(conn, "SELECT * FROM tripenarvor._compte");

    // Vérification de l'exécution de la requête
    if (PQresultStatus(res) != PGRES_TUPLES_OK) {
        fprintf(stderr, "Erreur lors de l'exécution de la requête : %s", PQerrorMessage(conn));
        PQclear(res);
        PQfinish(conn);
        exit(EXIT_FAILURE);
    }

    // Traitement des résultats
    int nFields = PQnfields(res);
    for (int i = 0; i < PQntuples(res); i++) {
        for (int j = 0; j < nFields; j++) {
            printf("%s\t", PQgetvalue(res, i, j));  // Affichage des valeurs
        }
        printf("\n");
    }

    // Libération des ressources
    PQclear(res);
    PQfinish(conn);

    return 0;
}
