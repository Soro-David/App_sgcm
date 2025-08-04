$(document).ready(function () {
    // Initialiser Select2
    $('#agent_id').select2({
        width: '100%',
        placeholder: '-- Sélectionnez un agent --',
        allowClear: true
    });

    // Lorsqu'on change d'agent
    $('#agent_id').on('change', function () {
        const agentId = $(this).val();
        const montantInput = $('#montant_percu');

        if (agentId) {
            $.ajax({
                url: `/versements/${agentId}`, // correspond à ta route
                type: 'GET',
                success: function (response) {
                    montantInput.val(response.montant);
                },
                error: function () {
                    console.error("Erreur lors de la récupération du montant.");
                    montantInput.val('');
                }
            });
        } else {
            montantInput.val('');
        }
    });
});
