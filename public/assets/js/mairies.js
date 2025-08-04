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

// public/assets/js/mairies.js

document.addEventListener('DOMContentLoaded', function () {
    
    // 1. Sélectionner les éléments du DOM
    const mairieSelect = document.getElementById('mairieSelect');
    const regionInput = document.getElementById('region');
    const communeInput = document.getElementById('commune');

    // Si le sélecteur n'est pas sur la page, on arrête le script
    if (!mairieSelect) {
        return;
    }

    // 2. Créer une fonction pour aller chercher les infos
    const fetchMairieInfos = (mairieId) => {
        // Si aucun ID n'est sélectionné, on vide les champs
        if (!mairieId) {
            regionInput.value = '';
            communeInput.value = '';
            return;
        }

        // On récupère l'URL depuis l'attribut data-url et on remplace le placeholder
        const url = mairieSelect.dataset.url.replace('ID_PLACEHOLDER', mairieId);

        // 3. Effectuer l'appel AJAX avec fetch
        fetch(url)
            .then(response => {
                // S'il y a une erreur réseau ou serveur
                if (!response.ok) {
                    throw new Error('La réponse du serveur n\'est pas OK');
                }
                return response.json(); // Convertir la réponse en JSON
            })
            .then(data => {
                // 4. Mettre à jour les champs avec les données reçues
                regionInput.value = data.region || ''; // Utilise la valeur ou une chaîne vide
                communeInput.value = data.commune || '';
            })
            .catch(error => {
                // Gérer les erreurs (ex: afficher dans la console)
                console.error('Erreur lors de la récupération des informations de la mairie:', error);
                regionInput.value = 'Erreur';
                communeInput.value = 'Erreur';
            });
    };

    // 5. Ajouter un écouteur d'événement sur le changement du sélecteur
    mairieSelect.addEventListener('change', function () {
        const selectedMairieId = this.value; // "this" fait référence à mairieSelect
        fetchMairieInfos(selectedMairieId);
    });

    // Optionnel mais recommandé : 
    // Déclencher la fonction une fois au chargement de la page si une mairie est déjà sélectionnée.
    // (Bien que le pré-remplissage dans Blade soit meilleur, ceci est une sécurité si JS charge avant tout)
    if (mairieSelect.value) {
       // fetchMairieInfos(mairieSelect.value); // Normalement déjà géré par le PHP/Blade
    }
});


$(document).ready(function () {
    
    // On détruit l'instance précédente pour éviter les conflits
    if ($.fn.DataTable.isDataTable('#mairieTable')) {
        $('#mairieTable').DataTable().destroy();
    }
        const ajaxUrl = mairieTable.data('url');
    // On initialise la DataTable
    $('#mairieTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: ajaxUrl, // Assurez-vous que ce nom est correct !
        columns: [
            { data: 'name', name: 'name' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        // Ajout pour un meilleur affichage des erreurs AJAX dans la console
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Erreur AJAX DataTables : ", textStatus, errorThrown);
            console.error("Réponse du serveur : ", jqXHR.responseText);
            alert("Une erreur est survenue lors du chargement des données. Veuillez vérifier la console du navigateur.");
        }
    });

});