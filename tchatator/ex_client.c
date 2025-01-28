#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <arpa/inet.h>

#define SERVER_IP "127.0.0.1"
#define SERVER_PORT 8080
#define BUFFER_SIZE 1024

// Fonction pour afficher le menu
void print_menu() {
    printf("\nMenu :\n");
    printf("1. Se connecter\n");
    printf("2. Envoyer un message\n");
    printf("3. Obtenir les messages non lus\n");
    printf("4. Quitter\n");
    printf("Entrez votre choix : ");
}

// Fonction pour gérer la réponse du serveur
void handle_server_response(int socket) {
    char buffer[BUFFER_SIZE];
    memset(buffer, 0, BUFFER_SIZE);

    if (recv(socket, buffer, BUFFER_SIZE - 1, 0) < 0) {
        perror("Erreur de réception du serveur");
    } else {
        printf("Réponse du serveur : %s\n", buffer);
    }
}

int main() {
    int client_socket;
    struct sockaddr_in server_addr;
    char buffer[BUFFER_SIZE];
    int choice;

    // Création du socket
    client_socket = socket(AF_INET, SOCK_STREAM, 0);
    if (client_socket < 0) {
        perror("Échec de la création du socket");
        exit(EXIT_FAILURE);
    }

    // Configuration de la structure d'adresse du serveur
    memset(&server_addr, 0, sizeof(server_addr));
    server_addr.sin_family = AF_INET;
    server_addr.sin_port = htons(SERVER_PORT);

    if (inet_pton(AF_INET, SERVER_IP, &server_addr.sin_addr) <= 0) {
        perror("Adresse invalide");
        close(client_socket);
        exit(EXIT_FAILURE);
    }

    // Connexion au serveur
    if (connect(client_socket, (struct sockaddr *)&server_addr, sizeof(server_addr)) < 0) {
        perror("Échec de la connexion au serveur");
        close(client_socket);
        exit(EXIT_FAILURE);
    }

    printf("Connecté au serveur à %s:%d\n", SERVER_IP, SERVER_PORT);

    while (1) {
        print_menu();  // Affichage du menu
        scanf("%d", &choice);
        getchar(); // Consommer le caractère de nouvelle ligne

        switch (choice) {
            case 1:
                printf("Entrez la clé API : ");
                fgets(buffer, BUFFER_SIZE, stdin);
                buffer[strcspn(buffer, "\n")] = 0; // Enlever le caractère de nouvelle ligne
                snprintf(buffer, BUFFER_SIZE, "LOGIN:%s\n", buffer);
                send(client_socket, buffer, strlen(buffer), 0);
                handle_server_response(client_socket);
                break;

            case 2:
                printf("Entrez le message : ");
                fgets(buffer, BUFFER_SIZE, stdin);
                buffer[strcspn(buffer, "\n")] = 0; // Enlever le caractère de nouvelle ligne
                snprintf(buffer, BUFFER_SIZE, "MSG:<token>,%ld,%s\n", strlen(buffer), buffer);
                send(client_socket, buffer, strlen(buffer), 0);
                handle_server_response(client_socket);
                break;

            case 3:
                snprintf(buffer, BUFFER_SIZE, "GET_UNREAD:<token>\n");
                send(client_socket, buffer, strlen(buffer), 0);
                handle_server_response(client_socket);
                break;

            case 4:
                printf("Fermeture du programme...\n");
                close(client_socket);
                exit(EXIT_SUCCESS);
                break;

            default:
                printf("Choix invalide. Veuillez réessayer.\n");
        }
    }

    return 0;
}
