<script>
    // On s'assure que l'élément est bien cliqué
    document.getElementById('download-pdf').addEventListener('click', function() {
        const { jsPDF } = window.jspdf; // On utilise la bibliothèque jsPDF

        // Créer un nouveau document PDF
        const doc = new jsPDF();

        // Ajouter un titre
        doc.setFontSize(22);
        doc.text("Facture #12345", 20, 20);

        // Ajouter des informations sur le client
        doc.setFontSize(14);
        doc.text("Client: Jean Dupont", 20, 40);
        doc.text("Adresse: 123 Rue Exemple, Paris, France", 20, 50);
        doc.text("Date: 30 Novembre 2024", 20, 60);

        // Ajouter des détails d'articles
        doc.autoTable({
            head: [['Description', 'Quantité', 'Prix Unitaire', 'Total']],
            body: [
                ['Service A', '2', '€50', '€100'],
                ['Service B', '1', '€150', '€150']
            ],
            startY: 70,
            theme: 'grid'
        });

        // Ajouter le total de la facture
        doc.text("Total: €250", 140, doc.lastAutoTable.finalY + 10);

        // Télécharger le PDF
        doc.save("facture_12345.pdf");
    });
</script>
