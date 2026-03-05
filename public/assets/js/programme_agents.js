$(function() {
    const langUrl = $('#programmes-table').data('lang-url');

    $('.select2').select2({
        width: '100%',
        placeholder: "Veuillez choisir",
        allowClear: true
    });

    const table = $('#programmes-table').DataTable({
        processing: true,
        serverSide: true,
        language: { url: langUrl },
        ajax: $('#programmes-table').data('ajax-url'),
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'secteur', name: 'secteur', orderable: false, searchable: false },
            { data: 'taxes', name: 'taxes', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
    });

    // Gestion de la modification
    $('#programmes-table').on('click', '.edit-programme', function(e) {
        e.preventDefault();
        
        const data = table.row($(this).parents('tr')).data();
        const updateUrl = $(this).data('url');
        
        // Remplir les champs
        $('#programme_id').val(data.id);
        $('#agent_id').val(data.id).trigger('change').prop('disabled', true);
        
        // Secteur (tableau dans le modèle, on prend le premier)
        const secteurId = Array.isArray(data.secteur_id) ? data.secteur_id[0] : data.secteur_id;
        $('#secteur_id').val(secteurId).trigger('change');
        
        // Taxes (tableau dans le modèle)
        $('#taxe_ids').val(data.taxe_id).trigger('change');
        
        // Changer le texte du bouton et afficher Annuler
        $('#submitBtn').text('Mettre à jour').removeClass('btn-success').addClass('btn-warning');
        $('#cancelEdit').show();
        
        // Faire défiler vers le formulaire
        $('html, body').animate({
            scrollTop: $("#programmeForm").offset().top - 100
        }, 500);
    });

    // Rendre toute la ligne cliquable pour l'édition
    $('#programmes-table tbody').on('click', 'tr', function(e) {
        // Ne pas déclencher si on clique sur un bouton ou une icône d'action
        if ($(e.target).closest('.btn-table-action').length) {
            return;
        }
        $(this).find('.edit-programme').trigger('click');
    });

    // Annuler la modification
    $('#cancelEdit').on('click', function() {
        $('#programmeForm')[0].reset();
        $('#programme_id').val('');
        $('#agent_id').prop('disabled', false).val(null).trigger('change');
        $('#secteur_id').val(null).trigger('change');
        $('#taxe_ids').val(null).trigger('change');
        $('#submitBtn').text('Enregistrer').removeClass('btn-warning').addClass('btn-success');
        $(this).hide();
    });

    // Activer le champ agent_id avant l'envoi du formulaire pour qu'il soit inclus dans la requête
    $('#programmeForm').on('submit', function() {
        $('#agent_id').prop('disabled', false);
    });

    // Gestion de la suppression
    $('#programmes-table').on('click', '.delete-programme', function(e) {
        e.preventDefault();
        
        const deleteUrl = $(this).data('url');
        
        if (confirm('Êtes-vous sûr de vouloir supprimer ce programme ? Les taxes et le secteur seront retirés de cet agent.')) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        table.ajax.reload();
                    } else {
                        alert('Erreur : ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Erreur lors de la suppression : ' + (xhr.responseJSON?.message || 'Erreur inconnue'));
                }
            });
        }
    });
});
