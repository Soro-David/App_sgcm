$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const taxesTableElement = $('#taxesTable');
    const ajaxUrl = taxesTableElement.data('ajax-url');
    const langUrl = taxesTableElement.data('lang-url');

    const table = taxesTableElement.DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            url: langUrl
        },
        ajax: ajaxUrl,
        columns: [
            { data: 'created_at', name: 'created_at' },
            { data: 'nom', name: 'nom' },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
    });

    taxesTableElement.find('tfoot input').on('keyup change clear', function () {
        const index = $(this).closest('th').index();
        if (table.column(index).search() !== this.value) {
            table.column(index).search(this.value).draw();
        }
    });

    taxesTableElement.on('click', '.btn-delete', function () {
        const deleteUrl = $(this).data('url');
        if (confirm('Êtes-vous sûr de vouloir supprimer cette taxe ?')) {
            $.ajax({
                url: deleteUrl,
                type: 'POST',
                data: { _method: 'DELETE' },
                success: function (response) {
                    alert(response.success || 'Suppression réussie.');
                    table.ajax.reload();
                },
                error: function (jqXHR) {
                    alert('Erreur lors de la suppression.');
                    console.error(jqXHR.responseText);
                }
            });
        }
    });

    // OPTIONNEL : Soumettre le formulaire d’ajout en AJAX
    $('#addMairieModal form').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const data = form.serialize();

        $.post(url, data)
            .done(function (res) {
                $('#addMairieModal').modal('hide');
                form[0].reset();
                table.ajax.reload();
                alert('Taxe ajoutée avec succès');
            })
            .fail(function (xhr) {
                alert('Erreur lors de l\'ajout : ' + xhr.responseJSON.message);
            });
    });
});
