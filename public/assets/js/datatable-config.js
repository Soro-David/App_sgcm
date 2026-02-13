/**
 * Configuration globale pour DataTables
 * Ce fichier définit les paramètres par défaut pour toutes les tables DataTables du projet
 * Auteur: KKS Technologies
 * Date: 2026-02-11
 */

(function($) {
    'use strict';

    // Configuration par défaut pour toutes les DataTables
    $.extend(true, $.fn.dataTable.defaults, {
        // Langue française
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
        },
        
        // Options d'affichage
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Tous"]
        ],
        
        // Activation des fonctionnalités
        processing: true,
        responsive: true,
        autoWidth: false,
        
        // Style et classes
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        
        // Callback pour personnaliser l'affichage après chaque dessin
        drawCallback: function(settings) {
            // Ajouter des classes Bootstrap pour la pagination
            $('.dataTables_paginate > .pagination').addClass('pagination-sm');
            
            // Personnaliser le sélecteur de longueur
            $('.dataTables_length select').addClass('form-select form-select-sm');
            
            // Personnaliser le champ de recherche
            $('.dataTables_filter input').addClass('form-control form-control-sm');
        },
        
        // Callback après initialisation
        initComplete: function(settings, json) {
            // Personnaliser les éléments de contrôle
            var wrapper = $(this).closest('.dataTables_wrapper');
            
            // Ajouter des classes au sélecteur de longueur
            wrapper.find('.dataTables_length select')
                .removeClass('form-control-sm')
                .addClass('form-select form-select-sm d-inline-block w-auto');
            
            // Ajouter des classes au champ de recherche
            wrapper.find('.dataTables_filter input')
                .removeClass('form-control-sm')
                .addClass('form-control form-control-sm')
                .attr('placeholder', 'Rechercher...');
            
            // Ajouter une icône de recherche
            wrapper.find('.dataTables_filter label').addClass('d-flex align-items-center gap-2');
        }
    });

    // Fonction utilitaire pour initialiser une DataTable avec des options personnalisées
    window.initDataTable = function(selector, customOptions) {
        var defaultOptions = {
            processing: true,
            serverSide: false,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
            },
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Tous"]
            ],
            responsive: true,
            autoWidth: false,
            drawCallback: function() {
                $('.dataTables_paginate > .pagination').addClass('pagination-sm');
                $('.dataTables_length select').addClass('form-select form-select-sm');
                $('.dataTables_filter input').addClass('form-control form-control-sm');
            }
        };
        
        // Fusionner les options personnalisées avec les options par défaut
        var options = $.extend(true, {}, defaultOptions, customOptions);
        
        return $(selector).DataTable(options);
    };

    // Fonction pour réinitialiser une DataTable
    window.reloadDataTable = function(selector) {
        if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().ajax.reload(null, false);
        }
    };

    // Fonction pour détruire une DataTable
    window.destroyDataTable = function(selector) {
        if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().destroy();
        }
    };

})(jQuery);
