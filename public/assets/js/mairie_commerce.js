$(document).ready(function () {
    const table = $('#commercantsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: $('#commercantsTable').data('ajax-url'),
            type: 'GET',
            error: function (xhr, error, thrown) {
                console.error('Erreur AJAX DataTables:', xhr.responseText);
                alert('Erreur lors du chargement des données. Voir console.');
            }
        },
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'num_commerce', name: 'num_commerce' },
            { data: 'nom', name: 'nom' },
            { data: 'email', name: 'email' },
            { data: 'telephone', name: 'telephone' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // Gestion de la sélection globale
    $('#selectAll').on('click', function () {
        $('.contribuable-checkbox').prop('checked', this.checked);
        updatePrintButtonVisibility();
    });

    // Gestion de la sélection individuelle (délégation d'événement car le contenu est dynamique)
    $('#commercantsTable').on('change', '.contribuable-checkbox', function () {
        updatePrintButtonVisibility();
        
        // Décocher "Tout sélectionner" si une case est décochée
        if (!this.checked) {
            $('#selectAll').prop('checked', false);
        } else {
            // Recocher "Tout sélectionner" si toutes les cases sont cochées
            if ($('.contribuable-checkbox:checked').length === $('.contribuable-checkbox').length) {
                $('#selectAll').prop('checked', true);
            }
        }
    });

    function updatePrintButtonVisibility() {
        const selectedCount = $('.contribuable-checkbox:checked').length;
        if (selectedCount > 0) {
            $('#printSelectedBtn').removeClass('d-none');
        } else {
            $('#printSelectedBtn').addClass('d-none');
        }
    }

    // Action d'impression de la sélection
    $('#printSelectedBtn').on('click', function () {
        const selectedIds = $('.contribuable-checkbox:checked').map(function () {
            return $(this).val();
        }).get().join(',');

        if (selectedIds) {
            const printUrl = $('#commercantsTable').data('print-url') + '?ids=' + selectedIds;
            window.open(printUrl, '_blank');
        }
    });

    // Fonction suppression d'un commerçant
    window.deleteCommercant = function(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer ce commerçant ?")) {
            fetch(`/agent/commerce/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    table.ajax.reload();
                    alert("Commerçant supprimé avec succès !");
                } else {
                    alert("Erreur lors de la suppression.");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Erreur serveur.");
            });
        }
    }
});