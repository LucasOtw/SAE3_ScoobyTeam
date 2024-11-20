function toggleInfoBox() {
    const infoBox = document.getElementById("infoBox");
    const arrow = document.querySelector(".arrow");
  
    if (infoBox.style.maxHeight) {
      // Ferme la boîte
      infoBox.style.maxHeight = null;
      infoBox.style.padding = "0 15px";
      arrow.style.transform = "rotate(0deg)";
    } else {
      // Ouvre la boîte
      infoBox.style.maxHeight = infoBox.scrollHeight + "px";
      infoBox.style.padding = "15px";
      arrow.style.transform = "rotate(180deg)";
    }
  }
  