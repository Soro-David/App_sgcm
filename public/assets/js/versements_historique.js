$(document).ready(function () {
    const tableElement = $('#versementsTable');

    if (tableElement.length) {
        tableElement.DataTable({
            processing: true,
            serverSide: true, // Active le traitement côté serveur
            ajax: {
                url: tableElement.data('ajax-url'),
                type: 'GET'
            },
            language: {
                url: tableElement.data('lang-url')
            },
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'nom_agent', name: 'agent.name' },
                { data: 'montant_percu', name: 'montant_percu' },
                { data: 'montant_verse', name: 'montant_verse' },
                { data: 'reste', name: 'reste' },
            ],
            order: [[0, 'desc']]
        });
    }
});