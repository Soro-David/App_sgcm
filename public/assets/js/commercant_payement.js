$(function () {
    let historiqueTable;
    let montantParPeriode = 0;
    
    // ... initHistoriqueTable() et reloadHistorique() restent inchangées ...
    function initHistoriqueTable() {
        historiqueTable = $('#historique-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: window.payementConfig.historiqueUrl,
                dataSrc: 'data'
            },
            columns: [
                { data: "taxe.nom", defaultContent: "N/A" },
                { data: "montant", render: data => `${data} CFA` },
                { data: "created_at", render: data => new Date(data).toLocaleString() },
                {
                    data: "statut",
                    render: function (data) {
                        const badgeClass = data === 'payé' ? 'badge bg-success' : 'badge bg-warning';
                        return `<span class="${badgeClass}">${data}</span>`;
                    }
                }
            ],
            language: { url: $('#historique-table').data('lang-url') },
            order: [[2, "desc"]]
        });
    }

    function reloadHistorique() {
        if (historiqueTable) {
            historiqueTable.ajax.reload(null, false);
        }
    }


    /**
     * Récupère et affiche les périodes.
     * @param {number|null} nombrePeriodes - Si null, effectue le calcul automatique des impayés.
     *                                      - Si un nombre, récupère ce nombre de périodes.
     */
    function fetchAndDisplayPeriods(nombrePeriodes = null) {
        const taxeId = $('#taxe_id').val();
        if (!taxeId) return;

        const urlTemplate = $('#taxe_id').data('periodes-url');
        const url = urlTemplate.replace('PLACEHOLDER', taxeId);
        
        // Prépare les données à envoyer : n'inclut 'nombre_periodes' que si une valeur est fournie.
        const requestData = {};
        if (nombrePeriodes) {
            requestData.nombre_periodes = nombrePeriodes;
        }

        $('#periodes-impayees').html('<span class="text-muted">Chargement...</span>');

        $.get(url, requestData, function (data) {
            montantParPeriode = parseFloat(data.montant_par_periode) || 0;

            // Si c'était un calcul automatique (nombrePeriodes est null), on pré-remplit les champs.
            if (nombrePeriodes === null) {
                $('#nombre_periodes').val(data.count > 0 ? data.count : '');
                $('#montant').val(data.count > 0 ? (data.count * montantParPeriode).toFixed(0) : '');
            } else {
                // Sinon (saisie manuelle), on met juste à jour le montant.
                const nb = parseInt($('#nombre_periodes').val()) || 0;
                 $('#montant').val(nb > 0 ? (nb * montantParPeriode).toFixed(0) : '');
            }

            // Met à jour la zone d'affichage dans tous les cas.
            let html = `<strong>${data.summary_text}</strong>`;
            if (data.count > 0) {
                let list = '<ul class="list-unstyled mt-2">';
                data.periods_list.forEach(p => list += `<li>${p}</li>`);
                list += '</ul>';
                html += list;
            }
            $('#periodes-impayees').html(html);

        }).fail(function () {
            $('#periodes-impayees').html('<span class="text-danger">Erreur de récupération.</span>');
            montantParPeriode = 0;
            $('#montant').val('');
        });
    }

    // Événement au changement de la taxe : lance le calcul automatique.
    $('#taxe_id').on('change', function () {
        // Appelle la fonction sans argument pour déclencher le calcul automatique.
        fetchAndDisplayPeriods();
    });

    // Événement à la saisie manuelle du nombre de périodes.
    $('#nombre_periodes').on('input', function () {
        let val = parseInt($(this).val());
        
        if (isNaN(val) || val < 1) {
            $('#montant').val('');
            $('#periodes-impayees').html('<span class="text-muted">(Veuillez saisir un nombre valide)</span>');
            return;
        }
        
        // Appelle la fonction avec le nombre saisi pour mettre à jour la liste.
        fetchAndDisplayPeriods(val);
    });

    // ... la fonction de soumission du formulaire reste inchangée ...
     $('#payment-form').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const submitButton = $('#submit-button');
        const messageDiv = $('#payment-message');

        submitButton.prop('disabled', true).text('Paiement en cours...');
        messageDiv.html('');

        $.post(form.attr('action'), form.serialize())
            .done(function (response) {
                messageDiv.html(`<div class="alert alert-success">${response.message}</div>`);
                reloadHistorique();
                // Relance le calcul automatique pour la taxe actuelle.
                fetchAndDisplayPeriods();
            })
            .fail(function (jqXHR) {
                const errorMessage = jqXHR.responseJSON?.message || "Une erreur est survenue lors du paiement.";
                messageDiv.html(`<div class="alert alert-danger">${errorMessage}</div>`);
            })
            .always(function () {
                submitButton.prop('disabled', false).text('Payer');
            });
    });

    initHistoriqueTable();
});