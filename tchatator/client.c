#include <arpa/inet.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <unistd.h>

#include "bdd.h"
#include "config.h"
#include "fonct.h"

#define SERVER_IP "127.0.0.1"
#define SERVER_PORT 8080
#define BUFFER_SIZE 1024

// Prototypes de fonctions
void print_tchatator();
void print_menu_membre();
void print_menu_pro();
void print_menu_admin();
int create_client_socket();
void configure_server_address(struct sockaddr_in *server_addr);
void handle_server_response(int socket);
void remove_ansi_codes(char *str);
char *login(int socket, int *connected);
void send_message(int socket);
void close_connection(int socket);
void get_history(int socket);
void print_history_menu();
void handle_server_response_history(int socket);
void logout(int socket, int *connected);
void delete_message(int socket);
void regenerate_api_key(int socket);
void deconnexion(int socket, int *connected);
void update_message(int socket);
void block_member(int socket);
void unblock_member(int socket);
void block(int socket);
void unblock(int socket);
void ban(int socket);
void unban(int socket);

int main() {
    int client_socket;
    struct sockaddr_in server_addr;
    int choice;
    int connected = 0;
    // Création du socket client
    client_socket = create_client_socket();
    // Configuration de l'adresse du serveur
    configure_server_address(&server_addr);
    char *api_key;
    // Connexion au serveur
    if (connect(client_socket, (struct sockaddr *)&server_addr, sizeof(server_addr)) < 0) {
        perror("Échec de la connexion au serveur");
        close(client_socket);
        exit(EXIT_FAILURE);
    }
    print_tchatator();

    printf("Connecté au serveur à %s:%d\n", SERVER_IP, SERVER_PORT);

    // Boucle principale pour afficher le menu et gérer les choix
    while (1) {
        if (connected == 1) {
            if (api_key[0] == 'M') {
                print_menu_membre();
                scanf("%d", &choice);
                getchar();
                switch (choice) {
                    case 1:
                        afficher_infos_pros();  // ENVOYER MSG
                        break;
                    case 2:
                        send_message(client_socket);  // ENVOYER MSG
                        break;
                    case 3:
                        update_message(client_socket);  // Modifier MSG
                        break;
                    case 4:
                        delete_message(client_socket);  // Supprimer un message
                        break;
                    case 5:
                        get_history(client_socket);  // Historique
                        break;
                    case 6:
                        regenerate_api_key(client_socket);  // Regen API Key
                        break;
                    case 7:
                        logout(client_socket, &connected);  // Déconnexion
                        return EXIT_SUCCESS;

                        break;

                    default:
                        printf("Choix invalide. Veuillez réessayer.\n");
                }
            } else if (api_key[0] == 'P') {
                print_menu_pro();
                scanf("%d", &choice);
                getchar();

                switch (choice) {
                    case 1:
                        afficher_infos_clients();  // ENVOYER MSG
                        break;
                    case 2:
                        send_message(client_socket);  // ENVOYER MSG
                        break;
                    case 3:
                        update_message(client_socket);  // Modifier MSG
                        break;
                    case 4:
                        delete_message(client_socket);  // Supprimer un message
                        break;
                    case 5:
                        get_history(client_socket);  // Historique
                        break;
                    case 6:
                        regenerate_api_key(client_socket);  // Regen API Key
                        break;
                    case 7:
                        block(client_socket);  // bloquer un membre
                        break;
                    case 8:
                        unblock(client_socket);  // débloquer un membre
                        break;
                    case 9:
                        // close_connection(client_socket);
                        logout(client_socket, &connected);  // Déconnexion
                        return EXIT_SUCCESS;

                        break;
                    default:
                        printf("Choix invalide. Veuillez réessayer.\n");
                }
            } else if (api_key[0] == 'A') {
                print_menu_admin();
                scanf("%d", &choice);
                getchar();

                switch (choice) {
                    case 1:
                        block(client_socket);  // bloquer un membre ou un pro
                        break;
                    case 2:
                        unblock(client_socket);  // débloquer un membre ou un pro
                        break;
                    case 3:
                        ban(client_socket);  // ban un membre ou un pro
                        break;
                    case 4:
                        unban(client_socket);  // unban un membre ou un pro
                        break;
                    case 5:
                        logout(client_socket, &connected);  // Déconnexion
                        return EXIT_SUCCESS;
                        break;
                    default:
                        printf("Choix invalide. Veuillez réessayer.\n");
                }
            }

        } else {
            api_key = login(client_socket, &connected);  // L'utilisateur doit se connecter d'abord
        }
    }

    return 0;
}
void print_tchatator() {
    printf(" _____    _           _        _   \n");
    printf("|_   _|__| |__   __ _| |_ __ _| |_ ___  _ __\n");
    printf("  | |/ __| '_ \\ / _` | __/ _` | __/ _ \\| '__|\n");
    printf("  | | (__| | | | (_| | || (_| | || (_) | |\n");
    printf("  |_|\\___|_| |_|\\__,_|\\__\\__,_|\\__\\___/|_|\n\n");
}

// Fonction pour afficher le menu MEMBRE
void print_menu_membre() {
    printf("\n\033[48;2;189;196;38m\033[38;2;0;0;0m\033[1m=== Menu Membre ===\033[0m\n");  // Bleu et gras
    printf("\033[1m1. \033[0mAfficher l'annuaire des professionnels\n");                                      // Gras pour le numéro
    printf("\033[1m2. \033[0mEnvoyer un message\n");                                       // Gras pour le numéro
    printf("\033[1m3. \033[0mModifier un message\n");
    printf("\033[1m4. \033[0mSupprimer un message\n");
    printf("\033[1m5. \033[0mHistorique des messages\n");
    printf("\033[1m6. \033[0mRegénérer clé API\n");
    printf("\033[1m7. \033[0mDéconnexion\n");
    printf("Votre choix : ");
}

// Fonction pour afficher le menu PRO
void print_menu_pro() {
    printf("\n\033[48;2;242;131;34m\033[38;2;0;0;0m\033[1m=== Menu Pro ===\033[0m\n");  // Vert et gras
    printf("\033[1m1. \033[0mAfficher l'annuaire des membres\n");
    printf("\033[1m2. \033[0mEnvoyer un message\n");
    printf("\033[1m3. \033[0mModifier un message\n");
    printf("\033[1m4. \033[0mSupprimer un message\n");
    printf("\033[1m5. \033[0mHistorique des messages\n");
    printf("\033[1m6. \033[0mRegénérer clé API\n");
    printf("\033[1m7. \033[0mBloquer un membre\n");
    printf("\033[1m8. \033[0mDébloquer un membre\n");
    printf("\033[1m9. \033[0mDéconnexion\n");
    printf("Votre choix : ");
}

// Fonction pour afficher le menu ADMIN
void print_menu_admin() {
    printf("\n\033[1;31m=== Menu Admin ===\033[0m\n");  // Rouge et gras
    printf("\033[1m1. \033[0mBloquer un membre\n");
    printf("\033[1m2. \033[0mDébloquer un membre\n");
    printf("\033[1m3. \033[0mBannir un membre\n");
    printf("\033[1m4. \033[0mDébannir un membre\n");
    printf("\033[1m5. \033[0mDéconnexion\n");
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
// Fonction pour afficher une réponse du serveur avec une couleur
void handle_server_response(int socket) {
    char buffer[BUFFER_SIZE];
    memset(buffer, 0, BUFFER_SIZE);

    int len = recv(socket, buffer, BUFFER_SIZE - 1, 0);
    if (len < 0) {
        perror("\033[1;31mErreur de réception du serveur\033[0m");  // Rouge pour l'erreur
    } else {
        printf("\033[1;32mRéponse du serveur : \033[0m%s\n", buffer);  // Vert pour succès
    }
}

// Fonction pour afficher un message d'erreur avec couleur
void print_error_message(const char *message) {
    printf("\033[1;31m[ERREUR] \033[0m%s\n", message);  // Rouge pour les erreurs
}

// Fonction pour afficher un message de succès avec couleur
void print_success_message(const char *message) {
    printf("\033[1;32m[SUCCÈS] \033[0m%s\n", message);  // Vert pour les succès
}

// Exemple d'utilisation dans le main (lors de la connexion)
char *login(int socket, int *connected) {
    char buffer[1024];
    int essais = 3;
    char *api_key = malloc(sizeof(buffer));
    if (api_key == NULL) {
        perror("Erreur d'allocation mémoire");
        return NULL;
    }

    while (essais > 0) {
        printf("\033[1mEntrez la clé API : \033[0m");
        if (fgets(buffer, sizeof(buffer), stdin) == NULL) {
            perror("\033[1;31mErreur lors de la lecture de l'entrée\033[0m");
            break;
        }

        strcpy(api_key, buffer);
        buffer[strcspn(buffer, "\n")] = '\0';  // Supprime le retour à la ligne

        if (strlen(buffer) == 0) {
            print_error_message("Erreur : clé API vide");
            essais--;
            printf("Nombre d'essai(s) restant : %d\n", essais);
            continue;
        }

        if (send(socket, buffer, strlen(buffer), 0) < 0) {
            print_error_message("Erreur lors de l'envoi de la clé API");
            essais--;
            printf("Nombre d'essai(s) restant : %d\n", essais);
            continue;
        }

        int len = recv(socket, buffer, sizeof(buffer) - 1, 0);
        if (len < 0) {
            print_error_message("Erreur lors de la réception de la réponse");
            essais--;
            printf("Nombre d'essai(s) restant : %d\n", essais);
            continue;
        } else if (len == 0) {
            print_error_message("Le serveur a fermé la connexion de manière inattendue");
            essais--;
            printf("Nombre d'essai(s) restant : %d\n", essais);
            continue;
        }

        buffer[len] = '\0';
        remove_ansi_codes(buffer);

        if (strstr(buffer, "Erreur : La clé API n'existe pas\n") != NULL) {
            *connected = 0;
            print_error_message(buffer);
            essais--;
            if (essais == 0) {
                printf("\033[1;33mNombre d'essais dépassé. Veuillez réessayer dans 10 secondes.\033[0m\n");
                for (int i = 10; i > 0; i--) {
                    printf("\033[1;33m%d seconde(s)...\033[0m\n", i);
                    fflush(stdout);
                    sleep(1);
                }
                printf("\033[1;33mVous pouvez réessayer maintenant.\033[0m\n");
            } else {
                printf("Nombre d'essai(s) restant : %d\n", essais);
            }
            continue;
        }

        *connected = 1;
        print_success_message(buffer);
        break;
    }

    return api_key;
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

    handle_server_response(socket);
}

void update_message(int socket) {
    char buffer[BUFFER_SIZE];
    int id_msg;
    char message[BUFFER_SIZE];

    // Demande l'ID du destinataire
    printf("ID du message : ");
    scanf("%d", &id_msg);
    getchar();  // Consomme le retour à la ligne restant

    // Demande le contenu du message
    printf("Contenu du message : ");
    fgets(message, BUFFER_SIZE, stdin);
    message[strcspn(message, "\n")] = 0;  // Supprime le caractère \n
    // Formate et envoie le message au serveur
    snprintf(buffer, sizeof(buffer), "MDF %d %s\n", id_msg, message);

    printf("Message : %s", message);
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

    handle_server_response(socket);
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
    printf("3. Voir les messages reçus (lus et non lus)\n");
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

    printf("Vous avez été déconnecté\n");
}

// Fonction pour supprimer un message
void delete_message(int socket) {
    char buffer[BUFFER_SIZE];
    int id_msg;

    // Demande à l'utilisateur l'ID du message à supprimer
    printf("Entrez l'ID du message à supprimer :\n");
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

void block(int socket) {
    char buffer[BUFFER_SIZE];
    int id_user;

    // Demande à l'utilisateur l'ID de l'utilisateur à bloquer
    printf("Entrez l'ID de l'utilisateur à bloquer :\n");
    scanf("%d", &id_user);
    getchar();  // Consomme le retour à la ligne restant

    // Envoie la commande BLOCK avec l'ID de l'utilisateur
    snprintf(buffer, sizeof(buffer), "BLOCK %d\n", id_user);
    if (send(socket, buffer, strlen(buffer), 0) == -1) {
        perror("Erreur lors de l'envoi de la commande BLOCK");
        return;
    }

    // Reçoit et affiche la réponse du serveur
    int len = recv(socket, buffer, sizeof(buffer) - 1, 0);
    if (len <= 0) {
        perror("Erreur lors de la réception de la réponse");
        return;
    }
    buffer[len] = '\0';

    printf("%s\n", buffer);  // Affiche la réponse du serveur
}

void unblock(int socket) {
    char buffer[BUFFER_SIZE];
    int id_user;

    printf("Entrez l'ID de l'utilisateur à débloquer :\n");
    scanf("%d", &id_user);
    getchar();

    snprintf(buffer, sizeof(buffer), "UNBLOCK %d\n", id_user);
    if (send(socket, buffer, strlen(buffer), 0) == -1) {
        perror("Erreur lors de l'envoi de la commande UNBLOCK");
        return;
    }

    int len = recv(socket, buffer, sizeof(buffer) - 1, 0);
    if (len <= 0) {
        perror("Erreur lors de la réception de la réponse");
        return;
    }
    buffer[len] = '\0';

    printf("%s\n", buffer);
}

void ban(int socket) {
    char buffer[BUFFER_SIZE];
    int id_user;

    printf("Entrez l'ID de l'utilisateur à bannir :\n");
    scanf("%d", &id_user);
    getchar();

    snprintf(buffer, sizeof(buffer), "BAN %d\n", id_user);
    if (send(socket, buffer, strlen(buffer), 0) == -1) {
        perror("Erreur lors de l'envoi de la commande BAN");
        return;
    }

    int len = recv(socket, buffer, sizeof(buffer) - 1, 0);
    if (len <= 0) {
        perror("Erreur lors de la réception de la réponse");
        return;
    }
    buffer[len] = '\0';

    printf("%s\n", buffer);
}

void unban(int socket) {
    char buffer[BUFFER_SIZE];
    int id_user;

    printf("Entrez l'ID de l'utilisateur à débannir :\n");
    scanf("%d", &id_user);
    getchar();

    snprintf(buffer, sizeof(buffer), "UNBAN %d\n", id_user);
    if (send(socket, buffer, strlen(buffer), 0) == -1) {
        perror("Erreur lors de l'envoi de la commande UNBAN");
        return;
    }

    int len = recv(socket, buffer, sizeof(buffer) - 1, 0);
    if (len <= 0) {
        perror("Erreur lors de la réception de la réponse");
        return;
    }
    buffer[len] = '\0';

    printf("%s\n", buffer);
}
