
$(document).ready(function () {
    const tableElement = $('#depensesTable');
    let depensesTable;

    if (tableElement.length) {
        depensesTable = tableElement.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: tableElement.data('ajax-url'),
                type: 'GET',
            },
            language: {
                url: tableElement.data('lang-url')
            },
            columns: [
                { data: 'date_depense', name: 'date_depense' },
                { data: 'motif', name: 'motif' },
                { data: 'montant', name: 'montant' },
                { data: 'description', name: 'description' },
                { data: 'mode_paiement', name: 'mode_paiement' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            order: [[0, 'desc']]
        });
    }

    $('#depenseForm').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        const saveButton = $('#save_button');
        const spinner = saveButton.find('.spinner-border');
        const messagesDiv = $('#form-messages');

        messagesDiv.html('');
        saveButton.prop('disabled', true);
        spinner.removeClass('d-none');
        const formData = new FormData(this);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                messagesDiv.html('<div class="alert alert-success">' + response.success + '</div>');
                form[0].reset();
                if (depensesTable) {
                    depensesTable.ajax.reload();
                }
            },
            error: function (xhr) {
                let errorHtml = '<div class="alert alert-danger"><ul>';
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        errorHtml += '<li>' + value[0] + '</li>';
                    });
                } else {
                    errorHtml += '<li>Une erreur inattendue est survenue.</li>';
                }
                errorHtml += '</ul></div>';
                messagesDiv.html(errorHtml);
            },
            complete: function () {
                saveButton.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });

});