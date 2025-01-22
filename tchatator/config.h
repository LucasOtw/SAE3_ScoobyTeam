#ifndef CONFIG_H
#define CONFIG_H

#include <netinet/in.h>
#include <libpq-fe.h> // Bibliothèque PostgreSQL

// Déclaration des variables globales
extern char SERVER_IP[16];       // Adresse IP du serveur
extern int SERVER_PORT;          // Port du serveur
extern int NB_BACKLOG;           // Nombre de connexions en attente
extern int BAN_DUR;              // Durée du bannissement en heures
extern int NB_MESS;              // Nombre de messages à afficher
extern int LEN_MESS;             // Longueur maximale d'un message
extern int BUFFER_SIZE;          // Taille du tampon de données
extern int MAX_MIN;              // Limite en minutes de messages envoyés
extern int MAX_HOUR;             // Limite en heures de messages envoyés
extern char LOG_LINKS[256];      // Chemin du fichier de logs
extern char API_ADMIN[36];       // Identifiant unique pour l'API de l'admin
extern int LEN_API;              // Taille clés API

typedef struct {
    char token[10];
    char new_user[15];
} UserInfo;


// Fonction pour charger les paramètres à partir du fichier "param.txt"
int load_config(const char *filename);

// Fonction pour préparer et configurer le socket
int prepare_socket(int *ret, int *sock, struct sockaddr_in *addr);

// Fonction qui connecte le user et qui renvoie le token
UserInfo* generate_and_return_token(const char *buffer, PGconn *conn);

#endif