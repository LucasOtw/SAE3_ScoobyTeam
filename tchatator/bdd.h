#ifndef BDD_H
#define BDD_H

#include <libpq-fe.h> // Bibliothèque PostgreSQL
#include <stdbool.h>


// Fonction pour me connecter à la bdd
PGconn* connect_to_db();

// Fonction qui génère un token aléatoire de 4 caractères et le retourne
char* generate_token();

// Fonction utilitaire pour exécuter une requête PostgreSQL et vérifier le résultat
bool execute_query(PGconn *conn, const char *query, char *api_key, char *client_ip);

bool is_member_blocked_all(PGconn *conn, int code_membre, char *log_message, size_t log_size);

bool is_member_blocked_for(PGconn *conn, int code_membre, int code_professionnel, char *log_message, size_t log_size);

bool is_member_banned(PGconn *conn, int code_membre, char *log_message, size_t log_size);

#endif