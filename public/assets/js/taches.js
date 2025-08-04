// public/assets/js/mairies.js
$(document).ready(function () {
    // Configuration globale d'AJAX pour inclure le token CSRF
    // C'est la seule fois où on a besoin de le définir.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const tachesTableElement = $('#tachesTable');
    const ajaxUrl = tachesTableElement.data('ajax-url');
    const langUrl = tachesTableElement.data('lang-url');
    console.log(tachesTableElement,ajaxUrl,langUrl);
    // Initialisation de la DataTable
    const table = tachesTableElement.DataTable({
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
    { data: 'action', name: 'action', orderable: false, searchable: false } // ← EN TROP
],

    });

    // Recherche par colonne dans le footer
    tachesTableElement.find('tfoot input').on('keyup change clear', function () {
        const index = $(this).closest('th').index();
        if (table.column(index).search() !== this.value) {
            table.column(index).search(this.value).draw();
        }
    });

    // Gestion de la suppression
    tachesTableElement.on('click', '.btn-delete', function () {
        const deleteUrl = $(this).data('url');
        if (confirm('Êtes-vous sûr de vouloir supprimer cette mairie ?')) {
            $.ajax({
                url: deleteUrl,
                type: 'POST', // Laravel attend une requête POST pour la méthode DELETE via formulaire/AJAX
                data: {
                    // _token n'est plus nécessaire ici car il est déjà dans ajaxSetup
                    _method: 'DELETE'
                },
                success: function (response) {
                    alert(response.success || 'Suppression réussie.');
                    table.ajax.reload(); // recharge les données de la table
                },
                error: function (jqXHR) {
                    alert('Erreur lors de la suppression.');
                    console.error(jqXHR.responseText);
                }
            });
        }
    });
});
