$(document).ready(function () {
    const tableElement = $('#encaissementsTable');
    
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
                { data: 'montant_percu', name: 'montant_percu' },
                { data: 'agent_nom', name: 'agent.name', orderable: false, searchable: false },
                { data: 'taxe_nom', name: 'taxe.nom', orderable: false, searchable: false },
                { data: 'commercant_info', name: 'commercant.nom', orderable: false, searchable: true },
                { data: 'statut', name: 'statut' },
            ],
            order: [[0, 'desc']]
        });
    }
});