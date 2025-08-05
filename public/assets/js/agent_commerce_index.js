$(document).ready(function () {
    const table = $('#commercantsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: $('#commercantsTable').data('ajax-url'),
            type: 'GET',
            error: function (xhr, error, thrown) {
                console.error('Erreur AJAX DataTables:', xhr.responseText);
                alert('Erreur lors du chargement des données. Voir console.');
            }
        },
        language: {
            url: $('#commercantsTable').data('lang-url')
        },
        columns: [
            { data: 'created_at', name: 'created_at' },
            { data: 'num_commerce', name: 'num_commerce' },
            { data: 'nom', name: 'nom' },
            { data: 'email', name: 'email' },
            { data: 'telephone', name: 'telephone' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
    });

});