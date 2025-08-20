$(document).ready(function () {
    const table = $('#commercantsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: $('#commercantsTable').data('ajax-url'),
            type: 'GET',
            error: function (xhr) {
                console.error('Erreur AJAX DataTables:', xhr.responseText);
                alert('Erreur lors du chargement des données.');
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

    // ✅ Redirection au clic sur une ligne, sauf colonne Action
    $('#commercantsTable tbody').on('click', 'tr', function (e) {
        // Empêche la redirection si le clic vient de la dernière colonne (action)
        const columnIndex = $(e.target).closest('td').index();
        const totalColumns = $('#commercantsTable thead th').length;
        if (columnIndex === totalColumns - 1) return;

        const data = table.row(this).data();
        if (data && data.id) {
            const url = `/agent/commerce/carte-virtuelle/edit/${data.id}`;
            window.location.href = url;
        }
    });
});
