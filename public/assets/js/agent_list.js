$(function () {
    var agentsTable = $('#agents-table');

    var ajaxUrl = agentsTable.data('url');
    var langUrl = agentsTable.data('lang-url');

    agentsTable.DataTable({
        processing: true,
        serverSide: true,
        
        ajax: ajaxUrl, 
        
        columns: [
            { data: 'added_by', name: 'added_by' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'type', name: 'type' }, 
            { data: 'created_at', name: 'created_at' },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false,
                searchable: false
            }
        ],
        language: {
            url: langUrl
        }
    });
});