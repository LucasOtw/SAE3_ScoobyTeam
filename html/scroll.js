const scrollAmount = 300;

function scrollcontentLeft() {
    const container = document.querySelector('.a-la-une');
    container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
}

function scrollcontentRight() {
    const container = document.querySelector('.a-la-une');
    container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
}

function toggleInfoBox() {
    const infoBox = document.getElementById("infoBox");
    const arrow = document.querySelector(".arrow");

    if (infoBox.style.maxHeight) {
      // Ferme la boîte
      infoBox.style.maxHeight = null;
      infoBox.style.padding = "0 15px";
      infoBox.style.overflowY = "hidden";  // Masque la barre de défilement lors de la fermeture
      arrow.style.transform = "rotate(0deg)";
    } else {
      // Ouvre la boîte
      infoBox.style.maxHeight = infoBox.scrollHeight + "px";
      infoBox.style.padding = "15px";
      infoBox.style.overflowY = "auto";  // Ajoute une barre de défilement verticale si nécessaire
      arrow.style.transform = "rotate(180deg)";
    }
}

document.querySelector('.info-icon').addEventListener('mouseenter', () => {
  const tooltip = document.querySelector('.tooltip');
  tooltip.style.opacity = '1';
  tooltip.style.visibility = 'visible';
});

document.querySelector('.info-icon').addEventListener('mouseleave', () => {
  const tooltip = document.querySelector('.tooltip');
  tooltip.style.opacity = '0';
  tooltip.style.visibility = 'hidden';
});

