$(document).ready(function () {
    const searchForm = $('#search_form');
    const searchButton = $('#search_button');
    const resultsContainer = $('#results_container');
    const spinner = searchButton.find('.spinner-border');
    let dataTableInstance = null; // Pour garder une référence à l'instance de la DataTable

    // Initialiser les selects avec Select2
    $('.select2').select2({
        theme: "bootstrap-5"
    });

    // Gérer la soumission du formulaire de recherche en AJAX
    searchForm.on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: 'GET',
            data: $(this).serialize(),
            beforeSend: function () {
                spinner.removeClass('d-none');
                searchButton.prop('disabled', true);
                resultsContainer.html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                
                // Détruire l'instance précédente de DataTable si elle existe
                if (dataTableInstance) {
                    dataTableInstance.destroy();
                }
            },
            success: function (response) {
                resultsContainer.html(response.html);

                // Initialiser DataTable sur la nouvelle table si elle existe
                if ($('#recettes_datatable').length) {
                    dataTableInstance = $('#recettes_datatable').DataTable({
                        "language": {
                            "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
                        },
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                    });
                }
            },
            error: function (xhr) {
                console.error("Erreur AJAX:", xhr.responseText);
                resultsContainer.html('<div class="alert alert-danger">Erreur lors du chargement des résultats. Veuillez réessayer.</div>');
            },
            complete: function () {
                spinner.addClass('d-none');
                searchButton.prop('disabled', false);
            }
        });
    });

    // Gérer la case "Tout sélectionner" en utilisant la délégation d'événements
    resultsContainer.on('change', '#select_all_paiements', function() {
        // Cibler uniquement les checkboxes dans le corps de la DataTable
        const checkboxes = dataTableInstance.rows({ search: 'applied' }).nodes().to$().find('input.paiement-checkbox');
        checkboxes.prop('checked', $(this).prop('checked'));
    });

    resultsContainer.on('change', '.paiement-checkbox', function() {
        if ($('.paiement-checkbox:checked').length === $('.paiement-checkbox').length) {
            $('#select_all_paiements').prop('checked', true);
        } else {
            $('#select_all_paiements').prop('checked', false);
        }
    });
});