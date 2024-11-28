// Fonction pour basculer l'état du bouton slider
function toggleSlider() {
    var slider = document.querySelector('.slider');
    var offerStatusText = document.getElementById('offer-status');
    
    // Bascule la classe 'active' pour déplacer le cercle et changer la couleur
    slider.classList.toggle('active');
    
    // Vérifie si le bouton est activé ou non et change l'état de l'offre
    var isOnline = slider.classList.contains('active');
    var newStatus = isOnline ? "En Ligne" : "Hors Ligne";
    
    // Met à jour le texte de l'état de l'offre
    offerStatusText.textContent = newStatus;

    // Envoyer l'état à PHP via AJAX pour mettre à jour la base de données
    updateOfferState(isOnline);
}

// Fonction AJAX pour mettre à jour l'état de l'offre en ligne
function updateOfferState(isOnline) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_offer_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Envoie l'état de l'offre (true pour "En Ligne", false pour "Hors Ligne")
    xhr.send("en_ligne=" + (isOnline ? 1 : 0));

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Si la requête est réussie, tu peux afficher un message ou une confirmation
            console.log("L'état de l'offre a été mis à jour avec succès.");
        }
    };
}
