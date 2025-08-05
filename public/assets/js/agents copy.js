// public/assets/js/mairies.js
$(document).ready(function () {
    // Configuration globale d'AJAX pour inclure le token CSRF
    // C'est la seule fois où on a besoin de le définir.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const mairiesTableElement = $('#mairiesTable');
    const ajaxUrl = mairiesTableElement.data('ajax-url');
    const langUrl = mairiesTableElement.data('lang-url');
    console.log(mairiesTableElement,ajaxUrl,langUrl);
    // Initialisation de la DataTable
    const table = mairiesTableElement.DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        language: {
            url: langUrl
        },
        ajax: ajaxUrl,
        columns: [
            { data: 'created_at', name: 'created_at' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
    });

    // Recherche par colonne dans le footer
    mairiesTableElement.find('tfoot input').on('keyup change clear', function () {
        const index = $(this).closest('th').index();
        if (table.column(index).search() !== this.value) {
            table.column(index).search(this.value).draw();
        }
    });

    // Gestion de la suppression
    mairiesTableElement.on('click', '.btn-delete', function () {
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

    // Gestion du chargement dynamique des communes dans le modal
    $('#region').on('change', function() {
        var regionName = $(this).val();
        var url = $(this).data('url').replace('PLACEHOLDER', regionName);
        var communeSelect = $('#commune');

        communeSelect.html('<option value="" selected disabled>Chargement...</option>');

        if (regionName) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    communeSelect.empty().append('<option value="" selected disabled>-- Sélectionnez une commune --</option>');
                    // CORRECTION : Suppression du ajaxSetup en double
                    $.each(data, function(key, value) {
                        communeSelect.append('<option value="' + value.nom + '">' + value.nom + '</option>');
                    });
                },
                error: function() {
                    communeSelect.html('<option value="" selected disabled>Erreur de chargement</option>');
                }
            });
        } else {
             communeSelect.html('<option value="" selected disabled>-- Sélectionnez d\'abord une région --</option>');
        }
    });
});

// AJAX DE SELECTION DES REGION ET COMMUNE

$(function() {

    $('#region').on('change', function() {
        let selectedRegion = $(this).val();
        let communeSelect = $('#commune');
        
        console.log(selectedRegion, communeSelect);
        // On récupère l'URL de base depuis l'attribut data-url
        let urlTemplate = $(this).data('url');
        
        if (selectedRegion) {
            // On construit l'URL finale en remplaçant le placeholder et en encodant la valeur
            let finalUrl = urlTemplate.replace('PLACEHOLDER', encodeURIComponent(selectedRegion));

            communeSelect.empty().append('<option selected disabled>Chargement...</option>').trigger('change');

            $.ajax({
                url: finalUrl, // <-- On utilise la nouvelle URL sécurisée
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    communeSelect.empty();
                    
                    if (data.length > 0) {
                        communeSelect.append('<option value="" selected disabled>-- Sélectionnez une commune --</option>');
                        
                        $.each(data, function(index, commune) {
                            // AMÉLIORATION : Utiliser l'ID de la commune comme valeur
                            communeSelect.append(`<option value="${commune.id}">${commune.nom}</option>`);
                        });

                    } else {
                        communeSelect.append('<option value="" selected disabled>Aucune commune trouvée</option>');
                    }
                    communeSelect.trigger('change');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Erreur AJAX:", textStatus, errorThrown, jqXHR.responseText);
                    communeSelect.empty().append('<option value="" selected disabled>Erreur de chargement</option>').trigger('change');
                }
            });
        } else {
            communeSelect.empty().append('<option value="" selected disabled>-- Sélectionnez d\'abord une région --</option>').trigger('change');
        }
    });
    // Réinitialise les champs quand la modale est fermée 
    $('#addMairieModal').on('hidden.bs.modal', function () {
        // Réinitialise le formulaire entier (champs nom, email)
        $(this).find('form')[0].reset();
        
        // Réinitialise les Select2 à leur état initial
        $('#region').val(null).trigger('change');
        $('#commune').empty().append('<option value="" selected disabled>-- Sélectionnez d\'abord une région --</option>').trigger('change');
    });

});

$(document).ready(function() {
    $('.select2').select2({
        dropdownParent: $('#addMairieModal')
    });
});