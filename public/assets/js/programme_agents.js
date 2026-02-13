$(function() {
    const langUrl = $('#programmes-table').data('lang-url');

    $('.select2').select2({
        width: '100%',
        placeholder: "Veuillez choisir",
        allowClear: true
    });

    const table = $('#programmes-table').DataTable({
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

    // Gestion de la suppression
    $('#programmes-table').on('click', '.delete-programme', function(e) {
        e.preventDefault();
        
        const deleteUrl = $(this).data('url');
        
        if (confirm('Êtes-vous sûr de vouloir supprimer ce programme ? Les taxes et le secteur seront retirés de cet agent.')) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        table.ajax.reload();
                    } else {
                        alert('Erreur : ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Erreur lors de la suppression : ' + (xhr.responseJSON?.message || 'Erreur inconnue'));
                }
            });
        }
    });
});
