document.addEventListener("DOMContentLoaded", () => {
    const autresButton = document.querySelector(".autres-button");
    const filtersContainer = document.querySelector(".filters-container");
    const closeFiltersButton = document.querySelector(".close-filters");

    // Ouvrir la section filtres
    autresButton.addEventListener("click", () => {
        filtersContainer.classList.toggle("hidden");
    });

    // Fermer la section filtres
    closeFiltersButton.addEventListener("click", () => {
        filtersContainer.classList.add("hidden");
    });

    // Optionnel : fermer en cliquant à l'extérieur
    document.addEventListener("click", (event) => {
        if (!filtersContainer.contains(event.target) && !autresButton.contains(event.target)) {
            filtersContainer.classList.add("hidden");
        }
    });
});

