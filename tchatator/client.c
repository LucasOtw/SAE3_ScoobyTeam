#include <arpa/inet.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>

#include "bdd.h"
#include "config.h"
#include "fonct.h"

void afficher_menu();
void connexion(int sock);
void envoyer_message(int sock);
void regen_api_key(int sock);
void afficher_historique(int sock);

int main() {
    int sock, sock_ret;
    struct sockaddr_in server_addr;
    char buffer[BUFFER_SIZE];
    int choix;

    char txt_log[2050];

    if (load_config("param.txt") != 0) {
        printf("\033[31m-> Erreur lors du chargement du fichier de configuration.\033[0m\n");

        snprintf(txt_log, sizeof(txt_log), "-> Erreur lors du chargement du fichier de configuration.");
        insert_logs(NULL, NULL, txt_log);

        return -1;
    }

    // Création du socket client
    if ((sock = socket(AF_INET, SOCK_STREAM, 0)) < 0) {
        perror("Erreur lors de la création du socket");
        exit(EXIT_FAILURE);
    }

    // Connexion au serveur
    if ((sock_ret = connect(sock, (struct sockaddr *)&server_addr, sizeof(server_addr))) < 0) {
        perror("Cannot connect to the server socket");
        close(sock);
        exit(EXIT_FAILURE);
    } else {
        printf("Connected to server !\n");
    }

    printf("Connecté au serveur %s:%d\n", SERVER_IP, SERVER_PORT);

    while (1) {
        afficher_menu();  // Affiche le menu des options
        if (scanf("%d", &choix) != 1) {
            printf("Entrée invalide. Veuillez réessayer.\n");
            while (getchar() != '\n');  // Vide le buffer d'entrée
            continue;
        }

        switch (choix) {
            case 1:
                connexion(sock);
                break;
            case 2:
                envoyer_message(sock);  // Option pour envoyer un message
                break;
            case 3:
                regen_api_key(sock);  // Option pour régénérer une clé API
                break;
            case 4:
                afficher_historique(sock);  // Option pour afficher l'historique
                break;
            case 5:
                // Envoie la commande de déconnexion et ferme le socket
                snprintf(buffer, sizeof(buffer), "BYE BYE\n");
                if (send(sock, buffer, strlen(buffer), 0) == -1) {
                    perror("Erreur lors de l'envoi de la commande BYE BYE");
                }
                close(sock);
                printf("Déconnecté du serveur.\n");
                return 0;
            case 6:
                // Ferme le socket et quitte le programme
                close(sock);
                printf("Fermeture du client.\n");
                return 0;
            default:
                printf("Choix invalide.\n");
        }
    }

    return 0;
}

// Affiche le menu principal pour les actions disponibles
void afficher_menu() {
    printf("\n=== Menu Client ===\n");
    printf("1. Se connecter\n");
    printf("2. Envoyer un message (MSG)\n");
    printf("3. Regénérer clé API (REGEN)\n");
    printf("4. Historique des messages (HIST)\n");
    printf("5. Déconnexion (BYE BYE)\n");
    printf("6. Quitter\n");
    printf("Votre choix : ");
}

void connexion(int sock) {
    PGconn *conn = connect_to_db();

    char api_key[LEN_API + 1];  // Alloue de la mémoire pour la clé API

    // Demande la clé API
    printf("Votre clé API : ");
    scanf("%s", api_key);
    getchar();  // Consomme le retour à la ligne restant

    // Envoie la clé API au serveur
    if (send(sock, api_key, strlen(api_key), 0) == -1) {
        perror("Erreur lors de l'envoi de la clé API");
        return;
    }

    // Réception de la réponse du serveur (le token ou un message d'erreur)
    char buffer[BUFFER_SIZE];
    int len = recv(sock, buffer, sizeof(buffer) - 1, 0);
    if (len <= 0) {
        perror("Erreur lors de la réception de la réponse");
        return;
    }
    buffer[len] = '\0';                           // Terminer la chaîne de caractères reçue
    printf("Réponse du serveur : %s\n", buffer);  // Affiche la réponse (token ou message d'erreur)

    UserInfo *user_info = generate_and_return_token(api_key, conn);
}

void envoyer_message(int sock) {}
void regen_api_key(int sock) {}
void afficher_historique(int sock) {}