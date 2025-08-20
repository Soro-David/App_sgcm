$(document).ready(function () {
    const searchForm = $('#search_form');
    const searchButton = $('#search_button');
    const resultsContainer = $('#results_container');
    const spinner = searchButton.find('.spinner-border');

    // Gérer la soumission du formulaire de recherche en AJAX
    searchForm.on('submit', function (e) {
        e.preventDefault(); // Empêcher le rechargement de la page

        $.ajax({
            url: $(this).attr('action'),
            type: 'GET',
            data: $(this).serialize(),
            beforeSend: function () {
                // Montrer le spinner et désactiver le bouton
                spinner.removeClass('d-none');
                searchButton.prop('disabled', true);
                resultsContainer.html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            },
            success: function (response) {
                // Mettre à jour la section des résultats avec le HTML reçu
                resultsContainer.html(response.html);
            },
            error: function (xhr) {
                console.error("Erreur AJAX:", xhr.responseText);
                alert("Une erreur est survenue lors de la recherche. Veuillez consulter la console.");
                resultsContainer.html('<div class="alert alert-danger">Erreur lors du chargement des résultats.</div>');
            },
            complete: function () {
                // Cacher le spinner et réactiver le bouton
                spinner.addClass('d-none');
                searchButton.prop('disabled', false);
            }
        });
    });

    // Logique pour la case "Tout sélectionner"
    // Utiliser la délégation d'événements car le contenu est chargé en AJAX
    resultsContainer.on('change', '#select_all_paiements', function() {
        $('.paiement-checkbox').prop('checked', $(this).prop('checked'));
    });

    resultsContainer.on('change', '.paiement-checkbox', function() {
        if ($('.paiement-checkbox:checked').length === $('.paiement-checkbox').length) {
            $('#select_all_paiements').prop('checked', true);
        } else {
            $('#select_all_paiements').prop('checked', false);
        }
    });
});