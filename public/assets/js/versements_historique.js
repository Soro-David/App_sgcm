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
                { data: 'nom_versement', name: 'nom_versement' },
                { data: 'total_due', name: 'total_due' },
                { data: 'montant_verse', name: 'montant_verse' },
                { data: 'reste', name: 'reste' },
                { data: 'recorded_by_name', name: 'recorded_by' }
            ],
            order: [[0, 'desc']]
        });
    }
});