<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    document.getElementById('download-pdf').addEventListener('click', function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Titre de la facture
        doc.setFontSize(22);
        doc.text("Facture #12345", 20, 20);

        // Informations sur le client
        doc.setFontSize(14);
        doc.text("Client: Jean Dupont", 20, 40);
        doc.text("Adresse: 123 Rue Exemple, Paris, France", 20, 50);
        doc.text("Date: 30 Novembre 2024", 20, 60);

        // Détails des articles
        doc.autoTable({
            head: [['Description', 'Quantité', 'Prix Unitaire', 'Total']],
            body: [
                ['Service A', '2', '€50', '€100'],
                ['Service B', '1', '€150', '€150']
            ],
            startY: 70,
            theme: 'grid'
        });

        // Total de la facture
        doc.text("Total: €250", 140, doc.lastAutoTable.finalY + 10);

        // Télécharger le PDF
        doc.save("facture_12345.pdf");
    });
</script>
