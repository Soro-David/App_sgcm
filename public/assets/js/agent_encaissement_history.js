$(document).ready(function () {
    const tableElement = $('#historyTable');

    if (tableElement.length) {
        const table = tableElement.DataTable({
            processing: true,
            serverSide: true,
            ajax: { 
                url: tableElement.data('ajax-url'), 
                type: 'GET' 
            },
            language: { 
                url: tableElement.data('lang-url') 
            },
            columns: [
                { data: 'num_commerce', name: 'num_commerce' },
                { data: 'nom_commerce', name: 'nom_commerce' },
                { data: 'telephone', name: 'telephone' },
                { data: 'statut_paiement', name: 'statut_paiement', orderable: false, searchable: false },
                { data: 'montant', name: 'montant' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']] // Par défaut, on trie par n° commerce ou date (si on ajoute la date)
        });
    }
});
