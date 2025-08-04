$(function() {
    const langUrl = $('#programmes-table').data('lang-url');

    $('.select2').select2({
        width: '100%',
        placeholder: "Veuillez choisir",
        allowClear: true
    });

    $('#programmes-table').DataTable({
        processing: true,
        serverSide: true,
        language: { url: langUrl },
        ajax: $('#programmes-table').data('ajax-url'),
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'secteur', name: 'secteur', orderable: false, searchable: false },
            { data: 'taxes', name: 'taxes', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
    });
});
