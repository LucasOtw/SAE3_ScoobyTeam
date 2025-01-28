#include <arpa/inet.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>

#include "bdd.h"
#include "config.h"
#include "fonct.h"

#define SERVER_IP "127.0.0.1"
#define SERVER_PORT 8080
#define BUFFER_SIZE 1024

// Prototypes de fonctions
void print_menu();
int create_client_socket();
void configure_server_address(struct sockaddr_in *server_addr);
void handle_server_response(int socket);
void remove_ansi_codes(char *str);
void login(int socket, int *connected);
void send_message(int socket);
void close_connection(int socket);
void get_history(int socket);
void print_history_menu();
void handle_server_response_history(int socket);
void logout(int socket, int *connected);
void delete_message(int socket);
void regenerate_api_key(int socket);
void deconnexion(int socket, int *connected);

int main() {
    int client_socket;
    struct sockaddr_in server_addr;
    int choice;
    int connected = 0;
    // Création du socket client
    client_socket = create_client_socket();

    // Configuration de l'adresse du serveur
    configure_server_address(&server_addr);

    // Connexion au serveur
    if (connect(client_socket, (struct sockaddr *)&server_addr, sizeof(server_addr)) < 0) {
        perror("Échec de la connexion au serveur");
        close(client_socket);
        exit(EXIT_FAILURE);
    }

    printf("Connecté au serveur à %s:%d\n", SERVER_IP, SERVER_PORT);

    // Boucle principale pour afficher le menu et gérer les choix
    while (1) {
        if (connected == 1) {
            print_menu();
            scanf("%d", &choice);
            getchar();  // Consommer le caractère de nouvelle ligne

            switch (choice) {
                case 1:
                    login(client_socket, &connected);
                    break;

                case 2:
                    send_message(client_socket);
                    break;
                case 3:
                    delete_message(client_socket);  // Supprimer un message
                    break;
                case 4:
                    get_history(client_socket);
                    break;

                case 5:
                    regenerate_api_key(client_socket);
                    break;

                case 6:
                    logout(client_socket, &connected);  // Déconnexion
                    break;

                case 7:
                    close_connection(client_socket);
                    break;

                default:
                    printf("Choix invalide. Veuillez réessayer.\n");
            }
        } else {
            login(client_socket, &connected);  // L'utilisateur doit se connecter d'abord
        }
    }

    return 0;
}

// Fonction pour afficher le menu
void print_menu() {
    printf("\n=== Menu Client ===\n");
    printf("1. Connexion\n");
    printf("2. Envoyer un message\n");
    printf("3. Supprimer un message\n");
    printf("4. Historique des messages\n");
    printf("5. Regénérer clé API\n");
    printf("6. Déconnexion\n");
    printf("7. Quitter le client\n");
    printf("Votre choix : ");
}

// Fonction pour créer le socket client
int create_client_socket() {
    int client_socket = socket(AF_INET, SOCK_STREAM, 0);
    if (client_socket < 0) {
        perror("Échec de la création du socket");
        exit(EXIT_FAILURE);
    }
    return client_socket;
}

// Fonction pour configurer l'adresse du serveur
void configure_server_address(struct sockaddr_in *server_addr) {
    memset(server_addr, 0, sizeof(*server_addr));
    server_addr->sin_family = AF_INET;
    server_addr->sin_port = htons(SERVER_PORT);

    if (inet_pton(AF_INET, SERVER_IP, &server_addr->sin_addr) <= 0) {
        perror("Adresse invalide");
        exit(EXIT_FAILURE);
    }
}

// Fonction pour gérer la réponse du serveur
void handle_server_response(int socket) {
    char buffer[BUFFER_SIZE];
    memset(buffer, 0, BUFFER_SIZE);

    if (recv(socket, buffer, BUFFER_SIZE - 1, 0) < 0) {
        perror("Erreur de réception du serveur");
    } else {
    }
}

void remove_ansi_codes(char *str) {
    char *ptr = str;
    while (*ptr) {
        // Si on rencontre une séquence ANSI (commence par '\033[')
        if (*ptr == '\033' && *(ptr + 1) == '[') {
            // Sauter les caractères de la séquence
            while (*ptr && *ptr != 'm') {
                ptr++;
            }
            if (*ptr) {
                ptr++;  // Passer le 'm'
            }
        } else {
            *str++ = *ptr++;  // Conserver le caractère
        }
    }
    *str = '\0';  // Terminer la chaîne proprement
}

void login(int socket, int *connected) {
    char buffer[1024];
    int essais = 3;

    // Demander la clé API jusqu'à ce que l'utilisateur soit connecté ou que le nombre d'essais soit dépassé
    while (essais > 0) {
        printf("Entrez la clé API : ");
        if (fgets(buffer, sizeof(buffer), stdin) == NULL) {
            perror("Erreur lors de la lecture de l'entrée");
            break;
        }

        // Supprimer le retour à la ligne s'il est présent
        buffer[strcspn(buffer, "\n")] = '\0';

        // Vérifier si la clé API est vide
        if (strlen(buffer) == 0) {
            printf("Erreur : clé API vide\n");
            essais--;
            printf("Nombre d'essai(s) restant : %d\n", essais);
            continue;
        }

        // Envoi de la clé API au serveur
        if (send(socket, buffer, strlen(buffer), 0) < 0) {
            perror("Erreur lors de l'envoi de la clé API");
            essais--;
            printf("Nombre d'essai(s) restant : %d\n", essais);
            continue;
        }

        // Attente de la réponse du serveur
        int len = recv(socket, buffer, sizeof(buffer) - 1, 0);
        if (len < 0) {
            perror("Erreur lors de la réception de la réponse");
            essais--;
            printf("Nombre d'essai(s) restant : %d\n", essais);
            continue;
        } else if (len == 0) {
            printf("Le serveur a fermé la connexion de manière inattendue\n");
            essais--;
            printf("Nombre d'essai(s) restant : %d\n", essais);
            continue;
        }

        buffer[len] = '\0';         // Terminer la chaîne proprement
        remove_ansi_codes(buffer);  // Enlever les séquences de couleur si nécessaire

        // Vérifier si la réponse contient une erreur de clé API
        if (strstr(buffer, "Erreur : La clé API n'existe pas\n") != NULL) {
            *connected = 0;          // L'utilisateur reste non connecté
            printf("%s\n", buffer);  // Affiche le message d'erreur
            essais--;
            if (essais == 0) {
                printf("Nombre d'essais dépassé. Veuillez réessayer dans 10 secondes.\n");
                for (int i = 10; i > 0; i--) {
                    printf("%d seconde(s)...\n", i);
                    fflush(stdout);
                    sleep(1);  // Attente d'une seconde avant de réessayer
                }
                // Message après le délai
                printf("Vous pouvez réessayer maintenant.\n");
            } else {
                printf("Nombre d'essai(s) restant : %d\n", essais);
            }
            continue;  // Arrêter ici, car la clé API est invalide
        }

        // Si la clé est valide
        *connected = 1;          // L'utilisateur est maintenant connecté
        printf("%s\n", buffer);  // Affiche le message du serveur si clé API valide
        break;                   // Sortir de la boucle si la connexion est réussie
    }
}

// Fonction pour envoyer un message
void send_message(int socket) {
    char buffer[BUFFER_SIZE];
    int id_dest;
    char message[BUFFER_SIZE];

    // Demande l'ID du destinataire
    printf("ID du destinataire : ");
    scanf("%d", &id_dest);
    getchar();  // Consomme le retour à la ligne restant

    // Demande le contenu du message
    printf("Contenu du message : ");
    fgets(message, BUFFER_SIZE, stdin);
    message[strcspn(message, "\n")] = 0;  // Supprime le caractère \n
    // Formate et envoie le message au serveur
    snprintf(buffer, sizeof(buffer), "MSG %d %s\n", id_dest, message);
    if (send(socket, buffer, strlen(buffer), 0) == -1) {
        perror("Erreur lors de l'envoi du message\n");
        return;
    }

    // Reçoit et affiche la réponse du serveur
    int len = recv(socket, buffer, sizeof(buffer) - 1, 0);
    if (len <= 0) {
        perror("Erreur lors de la réception de la réponse\n");
        return;
    }
    buffer[len] = '\0';
}

// Fonction pour fermer la connexion
void close_connection(int socket) {
    printf("Fermeture du programme...\n");
    close(socket);
    exit(EXIT_SUCCESS);
}

void get_history(int socket) {
    char buffer[BUFFER_SIZE];
    int choice;

    // Menu d'historique
    print_history_menu();
    scanf("%d", &choice);
    getchar();  // Consommer le retour à la ligne

    char choix[20];
    switch (choice) {
        case 1:
            strcpy(choix, "LUS");
            break;
        case 2:
            strcpy(choix, "NONLUS");
            break;
        case 3:
            strcpy(choix, "RECUS");
            break;
        case 4:
            strcpy(choix, "ENVOYES");
            break;
        case 5:
            return;  // Retour au menu principal
        default:
            printf("Choix invalide. Retour au menu principal.\n");
            return;
    }

    // Envoie la commande HIST avec le choix au serveur
    snprintf(buffer, sizeof(buffer), "HIST %s\n", choix);
    if (send(socket, buffer, strlen(buffer), 0) == -1) {
        perror("Erreur lors de l'envoi de la commande HIST");
        return;
    }

    // Reçoit et affiche la réponse du serveur
    int len = recv(socket, buffer, sizeof(buffer) - 1, 0);
    if (len <= 0) {
        perror("Erreur lors de la réception de l'historique");
        return;
    }
    buffer[len] = '\0';

    handle_server_response_history(socket);
}

void print_history_menu() {
    printf("\n=== Menu Historique ===\n");
    printf("1. Voir les messages lus\n");
    printf("2. Voir les messages non lus\n");
    printf("3. Voir les messages reçus\n");
    printf("4. Voir les messages envoyés\n");
    printf("5. Retour au menu principal\n");
    printf("Votre choix : ");
}

// Fonction pour gérer la réponse du serveur
void handle_server_response_history(int socket) {
    char buffer[BUFFER_SIZE];
    memset(buffer, 0, BUFFER_SIZE);

    int len;
    while (1) {
        len = recv(socket, buffer, BUFFER_SIZE - 1, 0);

        if (len < 0) {
            perror("Erreur lors de la réception de l'historique");
            break;
        }

        if (len == 0) {
            // Si la taille de la réponse est 0, cela signifie que le serveur a terminé l'envoi
            break;
        }

        buffer[len] = '\0';  // Assurez-vous que la chaîne est bien terminée

        // Vérifie si la réponse indique "nouvelle commande", on arrête alors la boucle
        if (strstr(buffer, "Nouvelle commande") != NULL) {
            // Ignore l'affichage de ce message et sort de la boucle
            break;
        }

        // Affiche les autres messages
        printf("%s\n", buffer);

        // Réinitialisation du buffer pour la prochaine réponse
        memset(buffer, 0, sizeof(buffer));
    }
}

// Fonction de déconnexion
// Fonction de déconnexion
void logout(int socket, int *connected) {
    char buffer[BUFFER_SIZE];

    // Envoi de la commande LOGOUT au serveur
    snprintf(buffer, sizeof(buffer), "BYE BYE\n\0");
    if (send(socket, buffer, strlen(buffer), 0) == -1) {
        perror("Erreur lors de l'envoi de la commande BYE BYE\n");
        return;
    }

    // Reçoit la réponse du serveur
    int len = recv(socket, buffer, sizeof(buffer) - 1, 0);
    if (len <= 0) {
        perror("Erreur lors de la réception de la réponse\n");
        return;
    }
    buffer[len] = '\0';

    printf("%s\n", buffer);
    *connected = 0;  // L'utilisateur est maintenant déconnecté

    // Réinitialisation de la clé API ou de tout autre état nécessaire
    // Vous pouvez aussi réinitialiser d'autres variables ici si nécessaire
}

// Fonction pour supprimer un message
void delete_message(int socket) {
    char buffer[BUFFER_SIZE];
    int id_msg;

    // Demande à l'utilisateur l'ID du message à supprimer
    printf("Entrez l'ID du message à supprimer : ");
    scanf("%d", &id_msg);
    getchar();  // Consomme le retour à la ligne restant

    // Envoie la commande SUPPR avec l'ID du message
    snprintf(buffer, sizeof(buffer), "SUPPR %d\n", id_msg);
    if (send(socket, buffer, strlen(buffer), 0) == -1) {
        perror("Erreur lors de l'envoi de la commande SUPPR");
        return;
    }

    // Reçoit et affiche la réponse du serveur
    int len = recv(socket, buffer, sizeof(buffer) - 1, 0);
    if (len <= 0) {
        perror("Erreur lors de la réception de la réponse");
        return;
    }
    buffer[len] = '\0';

    printf("%s\n", buffer);  // Affiche la réponse du serveur (par exemple, succès ou échec)
}

// Fonction pour demander la régénération de la clé API
void regenerate_api_key(int socket) {
    char buffer[BUFFER_SIZE];

    // Demande la régénération de la clé API
    snprintf(buffer, sizeof(buffer), "REGEN\n");
    if (send(socket, buffer, strlen(buffer), 0) == -1) {
        perror("Erreur lors de l'envoi de la commande REGEN");
        return;
    }

    // Reçoit et affiche la réponse du serveur
    int len = recv(socket, buffer, sizeof(buffer) - 1, 0);
    if (len <= 0) {
        perror("Erreur lors de la réception de la réponse");
        return;
    }
    buffer[len] = '\0';      // Assurez-vous que la chaîne est terminée par un caractère nul
    printf("%s\n", buffer);  // Affiche la réponse
}
