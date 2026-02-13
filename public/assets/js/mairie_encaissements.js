$(document).ready(function () {
    const tableElement = $('#encaissementsTable');
    
    if (tableElement.length) {
        const table = tableElement.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: tableElement.data('ajax-url'),
                type: 'GET',
                data: function(d) {
                    const filterValue = $('#filter_user').val();
                    if (filterValue) {
                        if (filterValue.startsWith('agent_')) {
                            d.agent_id = filterValue.replace('agent_', '');
                        } else if (filterValue.startsWith('caisse_')) {
                            d.recorded_by = filterValue.replace('caisse_', '');
                        }
                    }
                },
                error: function (xhr, error, code) {
                    console.error("Erreur AJAX DataTables:", xhr.responseText);
                    alert("Une erreur est survenue lors du chargement des données.");
                }
            },
            language: {
                url: tableElement.data('lang-url')
            },
            columns: [
                { data: 'date_encaissement', name: 'date_encaissement' },
                { data: 'nb_encaissements', name: 'nb_encaissements', className: 'text-center' },
                { data: 'total_percu', name: 'total_percu', className: 'text-end' },
                { data: 'agent_nom', name: 'agent_nom' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            order: [[0, 'desc']]
        });

        // Gestion du click sur le bouton détail
        tableElement.on('click', '.btn-detail', function() {
            const btn = $(this);
            const agentId = btn.data('agent-id');
            const recordedBy = btn.data('recorded-by');
            const date = btn.data('date');
            const agentName = btn.data('agent-name');
            const detailsUrl = tableElement.data('details-url');

            // Formatage de la date pour l'affichage
            const dateObj = new Date(date);
            const formattedDate = dateObj.toLocaleDateString('fr-FR');
            
            $('#modalAgentDate').text(`Agent : ${agentName} | Date : ${formattedDate}`);

            // Reset du tableau
            const tbody = $('#detailsTable tbody');
            tbody.html('<tr><td colspan="5" class="text-center">Chargement...</td></tr>');
            $('#modalTotal').text('...');
            
            // Affichage du modal
            // Utilisation de jQuery pour Bootstrap 5 ou 4
            $('#detailsModal').modal('show');

            // Requête AJAX
            $.ajax({
                url: detailsUrl,
                type: 'GET',
                data: { 
                    agent_id: agentId, 
                    recorded_by: recordedBy,
                    date: date 
                },
                success: function(response) {
                    tbody.empty();
                    if(response.data && response.data.length > 0) {
                        response.data.forEach(item => {
                            tbody.append(`
                                <tr>
                                    <td>${escaped(item.date_heure)}</td>
                                    <td>${escaped(item.commercant_nom)}</td>
                                    <td>${escaped(item.num_commerce)}</td>
                                    <td>${escaped(item.taxe_nom)}</td>
                                    <td class="text-end"><b>${formatMoney(item.montant)}</b></td>
                                </tr>
                            `);
                        });
                        $('#modalTotal').html('<b>' + formatMoney(response.total) + '</b>');
                    } else {
                        tbody.html('<tr><td colspan="5" class="text-center">Aucun détail trouvé.</td></tr>');
                        $('#modalTotal').text('0 FCFA');
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    tbody.html('<tr><td colspan="5" class="text-center text-danger">Erreur lors du chargement des détails.</td></tr>');
                }
            });
        });

        // Refresh table on filter change
        $('#filter_user').on('change', function() {
            table.ajax.reload();
        });

        $('#resetFilter').on('click', function() {
            $('#filter_user').val('');
            table.ajax.reload();
        });
    }

    function escaped(text) {
        if (text === null || text === undefined) return '';
        // Protection XSS basique
        return $('<div>').text(text).html();
    }

    function formatMoney(amount) {
        return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
    }
});