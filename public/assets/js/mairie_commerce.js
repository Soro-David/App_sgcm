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
        language: {
            url: $('#commercantsTable').data('lang-url')
        },
        columns: [
            { data: 'created_at', name: 'created_at' },
            { data: 'num_commerce', name: 'num_commerce' },
            { data: 'nom', name: 'nom' },
            { data: 'email', name: 'email' },
            { data: 'telephone', name: 'telephone' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                $('input', this.footer()).on('keyup change clear', function () {
                    if (column.search() !== this.value) {
                        column.search(this.value).draw();
                    }
                });
            });
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