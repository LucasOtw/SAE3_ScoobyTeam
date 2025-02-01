#ifndef FONCT_H
#define FONCT_H

#include <libpq-fe.h>  // Bibliothèque PostgreSQL

#include "bdd.h"
#include "config.h"

int disconnect_user(UserInfo *user_info, PGconn *conn, int cnx);

int handle_token_update(UserInfo *user_info, PGconn *conn, int cnx);

#endif