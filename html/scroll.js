const scrollAmount = 300;

function scrollcontentLeft(class) {
    const container1 = document.querySelector(class);
    container1.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
}

function scrollcontentRight(class) {
    const container1 = document.querySelector(class);
    container1.scrollBy({ left: scrollAmount, behavior: 'smooth' });
}

function scrollcontentLeftA() {
    const container2 = document.querySelector('.a-la-une');
    container2.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
}

function scrollcontentRightA() {
    const container2 = document.querySelector('.a-la-une');
    container2.scrollBy({ left: scrollAmount, behavior: 'smooth' });
}

function scrollcontentLeftR() {
    const container3 = document.querySelector('.vu-recemment');
    container3.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
}

function scrollcontentRightR() {
    const container3 = document.querySelector('.vu-recemment');
    container3.scrollBy({ left: scrollAmount, behavior: 'smooth' });
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

const infoIcon = document.querySelector('.info-icon');
if (infoIcon) {
  infoIcon.addEventListener('mouseenter', () => {
    const tooltip = document.querySelector('.tooltip');
    tooltip.style.opacity = '1';
    tooltip.style.visibility = 'visible';
  });

  infoIcon.addEventListener('mouseleave', () => {
    const tooltip = document.querySelector('.tooltip');
    tooltip.style.opacity = '0';
    tooltip.style.visibility = 'hidden';
  });
}


