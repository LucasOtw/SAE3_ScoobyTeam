// Fonction pour basculer l'état du bouton slider
function toggleSlider() {
    var slider = document.querySelector('.slider');
    slider.classList.toggle('active');
    
    // Modifie le texte du bouton en fonction de son état
    if (slider.classList.contains('active')) {
        slider.textContent = 'On';
    } else {
        slider.textContent = 'Off';
    }
}

