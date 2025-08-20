$(document).ready(function () {
    const tableElement = $('#commercantsTable');

    const table = tableElement.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: tableElement.data('ajax-url'),
            type: 'GET'
        },
        language: {
            url: tableElement.data('lang-url')
        },
        columns: [
            { data: 'num_commerce', name: 'num_commerce' },
            { data: 'nom', name: 'nom' },
            { data: 'telephone', name: 'telephone' },
            { data: 'dernier_paiement', name: 'dernier_paiement', orderable: false, searchable: false },
            { data: 'statut_paiement', name: 'statut_paiement', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        createdRow: function(row, data, dataIndex) {
            if (data.statut_paiement.includes('bg-success')) {
                $(row).addClass('row-green');
            }
        }
    });

    $('#commercantsTable tbody').on('click', 'tr', function (e) {
        if ($(e.target).closest('td').index() === table.columns().header().length - 1) {
            return;
        }

        const data = table.row(this).data();
        if (data && data.id) {
            const baseUrl = tableElement.data('edit-url');
            const url = baseUrl.replace('ID_PLACEHOLDER', data.id);
            window.location.href = url;
        }
    });
});