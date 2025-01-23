#ifndef BDD_H
#define BDD_H

#include <libpq-fe.h> // Bibliothèque PostgreSQL
#include <stdbool.h>


// Fonction pour me connecter à la bdd
PGconn* connect_to_db();

// Fonction qui génère un token aléatoire de 4 caractères et le retourne
char* generate_token();

// Fonction utilitaire pour exécuter une requête PostgreSQL et vérifier le résultat
bool execute_query(PGconn *conn, const char *query);

#endif