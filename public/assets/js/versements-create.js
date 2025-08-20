$(document).ready(function () {
    $('#agent_id').select2({
        width: '100%',
        placeholder: '-- Sélectionnez un agent --',
        allowClear: true
    });

    const montantUrlTemplate = $('#versement-container').data('montant-url');

    $('#agent_id').on('change', function () {
        const agentId = $(this).val();
        const montantInput = $('#montant_percu');
        const detteInput = $('#dette');
        const montantRestantInput = $('#montant_restant');
        const montantVerseInput = $('#montant_verse');

        if (agentId && montantUrlTemplate) {
            const url = montantUrlTemplate.replace('AGENT_ID', agentId);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    const montantPercu = parseFloat(response.montant) || 0;
                    const dette = parseFloat(response.dette) || 0;

                    montantInput.val(montantPercu);
                    detteInput.val(dette);

                    const montantVerse = parseFloat(montantVerseInput.val()) || 0;
                    const montantRestant = dette + (montantPercu - montantVerse);
                    montantRestantInput.val(montantRestant.toFixed(2));
                },
                error: function () {
                    console.error("Erreur lors de la récupération des montants.");
                    montantInput.val('');
                    detteInput.val('');
                    montantRestantInput.val('');
                }
            });
        } else {
            montantInput.val('');
            detteInput.val('');
            montantRestantInput.val('');
        }
    });

    $('#montant_verse').on('input', function () {
        const montantPercu = parseFloat($('#montant_percu').val()) || 0;
        const dette = parseFloat($('#dette').val()) || 0;
        const montantVerse = parseFloat($(this).val()) || 0;

        const montantRestant = dette + (montantPercu - montantVerse);
        $('#montant_restant').val(montantRestant.toFixed(2));
    });

    // Datatable pour la liste des versements
    const table = $('#versementsTable').DataTable({
        ajax: {
            url: $('#versementsTable').data('ajax-url'),
            type: 'GET'
        },
        language: {
            url: $('#versementsTable').data('lang-url')
        },
         columns: [
            { data: 'date_creation', name: 'created_at' },
            { data: 'nom_agent', name: 'agent.nom' },
            { data: 'montant_percu', name: 'montant_percu' },
            { data: 'montant_verse', name: 'montant_verse' },
            { data: 'reste', name: 'reste' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
});