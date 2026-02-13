$(document).ready(function () {
    $('#agent_id').select2({
        width: '100%',
        placeholder: '-- Sélectionnez un agent --',
        allowClear: true
    });

    const montantUrlTemplate = $('#versement-container').data('montant-url-template');

    function updateCalculations() {
        const montantPercu = parseFloat($('#montant_percu').val()) || 0;
        const dette = parseFloat($('#dette').val()) || 0;
        const montantVerse = parseFloat($('#montant_verse').val()) || 0;

        const totalDue = montantPercu + dette;
        const montantRestant = Math.max(0, totalDue - montantVerse);
        $('#montant_restant').val(montantRestant.toFixed(2));

        // Appreciation automatique
        let appreciation = '';
        if (totalDue > 0) {
            const percentage = (montantVerse / totalDue) * 100;
            if (percentage >= 100) appreciation = 'excellent';
            else if (percentage >= 75) appreciation = 'bon';
            else if (percentage >= 50) appreciation = 'moyen';
            else appreciation = 'faible';
        } else {
            appreciation = 'excellent';
        }
        $('#appreciation').val(appreciation).trigger('change');
    }

    // Trigger change on load if an agent is selected (e.g. after validation error)
    if ($('#agent_id').val()) {
        $('#agent_id').trigger('change');
    }

    $('#agent_id').on('change', function () {
        const agentId = $(this).val();
        const montantInput = $('#montant_percu');
        const detteInput = $('#dette');

        console.log("Agent sélectionné:", agentId);

        if (agentId && montantUrlTemplate) {
            // Remplacer l'ID dans le template d'URL
            const url = montantUrlTemplate.replace('AGENT_ID', agentId);
            console.log("Appel Ajax vers:", url);

            // Interface focus - indicateur de chargement
            montantInput.val('Chargement...');
            detteInput.val('Chargement...');

            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    console.log("Réponse reçue:", response);
                    const montant = parseFloat(response.montant) || 0;
                    const dette = parseFloat(response.dette) || 0;
                    
                    montantInput.val(montant);
                    detteInput.val(dette);
                    
                    // Remplissage du tableau des encaissements
                    const tbody = $('#encaissements-body');
                    tbody.empty();
                    
                    if (response.encaissements && response.encaissements.length > 0) {
                        response.encaissements.forEach(function(enc) {
                            const date = new Date(enc.created_at).toLocaleDateString('fr-FR', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            const taxe = enc.taxe ? enc.taxe.nom : 'N/A';
                            const commercant = enc.commercant ? enc.commercant.nom : (enc.num_commerce || 'N/A');
                            const montantEnc = parseFloat(enc.montant_percu).toLocaleString('fr-FR') + ' FCFA';
                            
                            tbody.append(`
                                <tr>
                                    <td>${date}</td>
                                    <td>${taxe}</td>
                                    <td>${commercant}</td>
                                    <td class="text-end">${montantEnc}</td>
                                </tr>
                            `);
                        });
                        $('#total-percu-footer').text(montant.toLocaleString('fr-FR') + ' FCFA');
                    } else {
                        tbody.append('<tr><td colspan="4" class="text-center text-muted">Aucun encaissement non versé trouvé pour cet agent</td></tr>');
                        $('#total-percu-footer').text('0 FCFA');
                    }

                    updateCalculations();
                },
                error: function (xhr, status, error) {
                    console.error("Erreur Ajax:", error);
                    montantInput.val(0);
                    detteInput.val(0);
                    $('#encaissements-body').empty().append('<tr><td colspan="4" class="text-center text-danger">Erreur lors du chargement des données</td></tr>');
                    updateCalculations();
                }
            });
        } else {
            montantInput.val(0);
            detteInput.val(0);
            $('#encaissements-body').empty().append('<tr><td colspan="4" class="text-center text-muted">Sélectionnez un agent pour voir les détails</td></tr>');
            $('#total-percu-footer').text('0 FCFA');
            updateCalculations();
        }
    });

    $('#montant_verse').on('input', function () {
        updateCalculations();
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