document.addEventListener("DOMContentLoaded", () => {
    const openMenuButton = document.getElementById("openMenu");
    const closeMenuButton = document.getElementById("closeMenu");
    const filterMenu = document.getElementById("filterMenu");
    const overlay = document.getElementById("overlay");

    // Ouvrir le menu
    openMenuButton.addEventListener("click", () => {
        document.body.classList.add("menu-open");
    });

    // Fermer le menu
    closeMenuButton.addEventListener("click", () => {
        document.body.classList.remove("menu-open");
    });

    // Fermer le menu en cliquant sur l'overlay
    overlay.addEventListener("click", () => {
        document.body.classList.remove("menu-open");
    });
});
