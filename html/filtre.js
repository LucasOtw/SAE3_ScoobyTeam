document.addEventListener('DOMContentLoaded', function(){
    // Sélection des éléments
    const autresButton = document.getElementById('autres-button');
    const filtersSection = document.getElementById('filters-section');

    let i = 0;
    
    // Gestion du clic sur le bouton "Autres"
    autresButton.addEventListener('click', () => {
        // Alterne entre affichage et masquage
        if(filtersSection.style.display === "flex"){
            filtersSection.style.display = "none";
        } else {
            filtersSection.style.display = "flex";
        }
    });
});
