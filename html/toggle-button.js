// Fonction pour basculer l'état du bouton slider
function toggleSlider(slider) {
    // Basculer la classe CSS
    slider.classList.toggle('active');

    // Récupérer l'état actuel et les attributs nécessaires
    const offerId = slider.getAttribute('data-offer-id');
    const isActive = slider.getAttribute('data-active') === 'true';
    const detailsOffre = slider.getAttribute('data-details-offre');

    // Inverser l'état
    const newActiveState = !isActive;

    // Mettre à jour l'attribut data-active
    slider.setAttribute('data-active', newActiveState);

    // Envoyer la mise à jour au serveur
    fetch('/slider-details_offre.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            offerId, 
            isActive: newActiveState, 
            detailsOffre 
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Statut mis à jour avec succès');
        } else {
            console.error('Erreur lors de la mise à jour du statut:', data.message);
        }
    })
    .catch(error => {
        console.error('Erreur réseau:', error);
        // Annuler l'état local si la mise à jour échoue
        slider.setAttribute('data-active', isActive);
        slider.classList.toggle('active', isActive);
    });
}
