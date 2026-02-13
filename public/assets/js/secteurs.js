$(function () {
    const codeInput = $('#code_secteur');
    const nomInput = $('#nom');
    const secteursTable = $('#secteurs-table');
    const formCard = $('.card[data-code-generator-url]');
    const codeGenUrl = formCard.data('code-generator-url');
    const langUrl = secteursTable.data('lang-url');

    if (codeInput.length && nomInput.length) {
        function chargerCodeSecteur() {
            if (!codeInput.val()) {
                $.get(codeGenUrl, function (response) {
                    codeInput.val(response.code || 'Erreur de génération');
                }).fail(() => codeInput.val('Erreur réseau'));
            }
        }

        chargerCodeSecteur();

        nomInput.on('input', function () {
            const nom = $(this).val();
            if (nom.length >= 3) {
                $.ajax({
                    url: codeGenUrl,
                    method: 'GET',
                    data: { nom },
                    success: (res) => res.code && codeInput.val(res.code),
                    error: () => codeInput.val('Erreur...')
                });
            }
        });
    }

    if (secteursTable.length) {
        const table = secteursTable.DataTable({
            processing: true,
            serverSide: true,
            ajax: secteursTable.data('ajax-url'),
            language: { url: langUrl },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'code', name: 'code' },
                { data: 'nom', name: 'nom' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Event handler for Edit button
        secteursTable.on('click', '.btn-edit', function () {
            const id = $(this).data('id');
            const url = secteursTable.data('ajax-url').replace('liste-ajax', id + '/edit');
            
            $.get(url, function (response) {
                if (response.success) {
                    $('#edit_secteur_id').val(response.secteur.id);
                    $('#edit_nom').val(response.secteur.nom);
                    $('#edit_code').val(response.secteur.code);
                    $('#editSecteurModal').modal('show');
                }
            }).fail(function() {
                Swal.fire('Erreur', 'Impossible de charger les données du secteur.', 'error');
            });
        });

        // Event handler for Update form submission
        $('#editSecteurForm').on('submit', function (e) {
            e.preventDefault();
            const id = $('#edit_secteur_id').val();
            const url = secteursTable.data('ajax-url').replace('liste-ajax', id);
            const formData = $(this).serialize();

            $.ajax({
                url: url,
                method: 'PUT',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        $('#editSecteurModal').modal('hide');
                        Swal.fire('Succès', response.message, 'success');
                        table.ajax.reload(null, false);
                    }
                },
                error: function (xhr) {
                    let msg = 'Une erreur est survenue lors de la mise à jour.';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    Swal.fire('Erreur', msg, 'error');
                }
            });
        });

        // Event handler for Delete button
        secteursTable.on('click', '.btn-delete', function () {
            const id = $(this).data('id');
            const url = secteursTable.data('ajax-url').replace('liste-ajax', id);
            
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Supprimé !', response.message, 'success');
                                table.ajax.reload(null, false);
                            }
                        },
                        error: function (xhr) {
                            Swal.fire('Erreur', 'Une erreur est survenue lors de la suppression.', 'error');
                        }
                    });
                }
            });
        });
    }
});

