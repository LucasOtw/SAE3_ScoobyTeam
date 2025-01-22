#ifndef FONCT_H
#define FONCT_H

#include <libpq-fe.h> // Bibliothèque PostgreSQL



int disconnect_user(UserInfo *user_info, PGconn *conn, int cnx);

int handle_token_update(UserInfo *user_info, PGconn *conn, int cnx);

#endif