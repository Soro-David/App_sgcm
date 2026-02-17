$(function () {
    // Configuration AJAX pour inclure le token CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var agentsTable = $('#agents-table');

    var ajaxUrl = agentsTable.data('url');
    var langUrl = agentsTable.data('lang-url');

    const table = agentsTable.DataTable({
        processing: true,
        serverSide: true,
        
        ajax: ajaxUrl, 
        
        columns: [
            { data: 'added_by', name: 'added_by' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'type', name: 'type' }, 
            { data: 'created_at', name: 'created_at' },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false,
                searchable: false
            }
        ],
        language: {
            url: langUrl
        }
    });

    // Gestion de la suppression avec SweetAlert2
    agentsTable.on('click', '.btn-delete', function () {
        const deleteUrl = $(this).data('url');
        
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Voulez-vous vraiment supprimer cet agent ? Cette action est irréversible.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Supprimé !',
                            text: response.success || 'Suppression réussie.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        table.ajax.reload();
                    },
                    error: function (jqXHR) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: jqXHR.responseJSON?.error || 'Erreur lors de la suppression.',
                        });
                        console.error(jqXHR.responseText);
                    }
                });
            }
        });
    });
});
