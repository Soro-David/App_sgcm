/**
 * Initialisation du composant Select2 pour les filiations
 * Supporte la création de tags (tags: true) et le thème Bootstrap 5
 */
(function ($) {
    'use strict';

    /**
     * Initialise ou ré-initialise les champs de filiation
     */
    function initFiliationSelect2() {
        $('.s2-filiation-input').each(function () {
            var $el = $(this);

            // Éviter la double initialisation qui peut bloquer l'interactivité
            if ($el.hasClass('select2-hidden-accessible')) {
                return;
            }

            // Configuration pour Select2 avec support Tags et Thème Bootstrap 5
            var options = {
                tags: true,
                placeholder: "Sélectionner ou saisir une filiation",
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5', // Crucial pour le rendu correct avec le CSS chargé
                language: {
                    noResults: function () {
                        return "Aucun résultat trouvé. Appuyez sur Entrée pour ajouter.";
                    }
                }
            };

            // Gestion du parent si on est dans une modal (fix focus standard)
            var $modal = $el.closest('.modal');
            if ($modal.length) {
                options.dropdownParent = $modal;
            }

            // Initialisation de Select2
            $el.select2(options);
        });
    }

    // Fix pour le focus automatique du champ de recherche à l'ouverture (problème courant Select2)
    $(document).on('select2:open', function (e) {
        if ($(e.target).hasClass('s2-filiation-input')) {
            setTimeout(function () {
                var searchField = document.querySelector('.select2-container--open .select2-search__field');
                if (searchField) {
                    searchField.focus();
                }
            }, 50);
        }
    });

    // Chargement au démarrage de la page
    $(function () {
        initFiliationSelect2();
    });

    // Support pour les chargements dynamiques (ex: ouverture de modal Bootstrap)
    $(document).on('shown.bs.modal', function () {
        initFiliationSelect2();
    });

})(window.jQuery);

