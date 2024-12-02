// Sélectionne le bouton et le conteneur
const downloadBtn = document.getElementById('download-btn');
const factureContainer = document.getElementById('facture-container');

// Écouteur pour générer le PDF
downloadBtn.addEventListener('click', () => {
    const options = {
        margin: 1,
        filename: 'facture.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    // Génère le PDF à partir de la facture
    html2pdf().set(options).from(factureContainer).save();
});
