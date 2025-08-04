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
        secteursTable.DataTable({
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
    }
});
