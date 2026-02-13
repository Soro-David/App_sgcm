$(document).ready(function () {
    const tableElement = $('#paiementsTable');
    
    if (tableElement.length) {
        const table = tableElement.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: tableElement.data('ajax-url'),
                type: 'GET',
                error: function (xhr, error, code) {
                    console.error("Erreur AJAX DataTables:", xhr.responseText);
                    alert("Une erreur est survenue lors du chargement des données. Veuillez rafraîchir la page.");
                }
            },
            language: {
                url: tableElement.data('lang-url')
            },
            columns: [
                { data: 'commercant_info', name: 'commercant.nom', orderable: false, searchable: true },
                { data: 'nombre_paiements', name: 'nombre_paiements', orderable: false, searchable: false, className: 'text-center' },
                { data: 'total_montant', name: 'total_montant', orderable: true, searchable: false },
                { data: 'dernier_paiement', name: 'dernier_paiement', orderable: true, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' },
            ],
            order: [[2, 'desc']] // Order by total_montant by default
        });

        // Handler pour voir les détails
        tableElement.on('click', '.view-details', function() {
            const id = $(this).data('id');
            const url = tableElement.data('details-url').replace(':id', id);
            
            $('#detailsBody').html('<tr><td colspan="5" class="text-center"><div class="spinner-border spinner-border-sm text-primary"></div> Chargement...</td></tr>');
            $('#detailsModal').modal('show');

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let html = '';
                        if (response.data.length > 0) {
                            response.data.forEach(p => {
                                html += `
                                    <tr>
                                        <td>${p.date}</td>
                                        <td>${p.taxe}</td>
                                        <td>${p.periode}</td>
                                        <td><strong>${p.montant}</strong></td>
                                        <td><span class="badge ${p.statut.toLowerCase() === 'payé' ? 'bg-success' : 'bg-secondary'}">${p.statut}</span></td>
                                    </tr>
                                `;
                            });
                        } else {
                            html = '<tr><td colspan="5" class="text-center">Aucun paiement trouvé.</td></tr>';
                        }
                        $('#detailsBody').html(html);
                    }
                },
                error: function() {
                    $('#detailsBody').html('<tr><td colspan="5" class="text-center text-danger">Erreur lors du chargement des détails.</td></tr>');
                }
            });
        });
    }
});