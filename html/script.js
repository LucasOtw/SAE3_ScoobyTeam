// Gestion des tooltips
const tooltip = document.querySelector('.tooltip');
const infoIcon = document.querySelector('.info-icon');

infoIcon.addEventListener('mouseenter', () => {
    tooltip.style.opacity = '1';
    tooltip.style.visibility = 'visible';
});

infoIcon.addEventListener('mouseleave', () => {
    tooltip.style.opacity = '0';
    tooltip.style.visibility = 'hidden';
});

// Gestion des sliders pour la plage de prix
const priceMin = document.getElementById("price-min");
const priceMax = document.getElementById("price-max");
const priceMinDisplay = document.getElementById("price-min-display");
const priceMaxDisplay = document.getElementById("price-max-display");

priceMin.addEventListener("input", () => {
    if (parseInt(priceMin.value) > parseInt(priceMax.value)) {
        priceMin.value = priceMax.value;
    }
    priceMinDisplay.textContent = priceMin.value;
});

priceMax.addEventListener("input", () => {
    if (parseInt(priceMax.value) < parseInt(priceMin.value)) {
        priceMax.value = priceMin.value;
    }
    priceMaxDisplay.textContent = priceMax.value;
});
