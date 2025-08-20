$(document).ready(function () {
    const tableElement = $('#paiementsTable');
    
    if (tableElement.length) {
        tableElement.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: tableElement.data('ajax-url'),
                type: 'GET',
                error: function (xhr, error, code) {
                    console.error("Erreur AJAX DataTables:", xhr.responseText);
                    alert("Une erreur est survenue lors du chargement des données. Veuillez rafraîchir la page.");
                }
            },
            language: {
                url: tableElement.data('lang-url')
            },
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'periode', name: 'periode' },
                { data: 'montant', name: 'montant' },
                { data: 'taxe_nom', name: 'taxe.nom', orderable: false, searchable: false },
                { data: 'commercant_info', name: 'commercant.nom', orderable: false, searchable: true },
                { data: 'statut', name: 'statut' },
            ],
            order: [[0, 'desc']]
        });
    }
});