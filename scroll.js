document.addEventListener("DOMContentLoaded", function () {

    // Récupérer la largeur d'une carte
    const element = document.querySelector('.card-a-la-une');
    
    if (!element) {
        console.error("Aucun élément avec la classe 'card-a-la-une' trouvé dans le DOM.");
        return;
    }

    const largeurCard = element.offsetWidth;

    // Fonction pour faire défiler le contenu à gauche
    function scrollcontentLeft() {
        const container = document.querySelector('.a-la-une');
        if (container) {
            container.scrollBy({ left: -largeurCard, behavior: 'smooth' });
        } else {
            console.error("Conteneur '.a-la-une' introuvable.");
        }
    }

    // Fonction pour faire défiler le contenu à droite
    function scrollcontentRight() {
        const container = document.querySelector('.a-la-une');
        if (container) {
            container.scrollBy({ left: largeurCard, behavior: 'smooth' });
        } else {
            console.error("Conteneur '.a-la-une' introuvable.");
        }
    }

    // Ajouter les événements sur les boutons de défilement
    const btnLeft = document.querySelector('.card-scroll-btn-left');
    const btnRight = document.querySelector('.card-scroll-btn-right');

    if (btnLeft) {
        btnLeft.addEventListener('click', scrollcontentLeft);
    } else {
        console.error("Bouton de défilement gauche introuvable.");
    }

    if (btnRight) {
        btnRight.addEventListener('click', scrollcontentRight);
    } else {
        console.error("Bouton de défilement droit introuvable.");
    }
});
