// Sélection des éléments
const autresButton = document.getElementById('autres-button');
const filtersSection = document.getElementById('filters-section');

// Gestion du clic sur le bouton "Autres"
autresButton.addEventListener('click', () => {
    // Alterne entre affichage et masquage
    filtersSection.classList.toggle('hidden');
});
