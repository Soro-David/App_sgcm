@extends('agent.layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-3">Mes Encaissements</h1>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="historyTable" class="table table-hover align-middle" style="width:100%"
                        data-ajax-url="{{ route('agent.encaissement.get_list_encaissement') }}"
                        data-lang-url="{{ asset('js/fr-FR.json') }}">
                        <thead class="table-light">
                            <tr>
                                <th>N° Commerce</th>
                                <th>Commerce</th>
                                <th>Téléphone</th>
                                <th>Statut Commerçant</th>
                                <th>Montant</th>
                                <th style="width: 150px;">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/agent_encaissement_history.js') }}"></script>
    <script>
        function showDetails(id) {
            // Logique pour afficher les détails si nécessaire
            // Pour l'instant on peut rediriger vers une page de détails ou ouvrir un modal
            alert("Détails de l'encaissement #" + id + " (Fonctionnalité à venir)");
        }

        function deleteEncaissement(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet encaissement ?')) {
                $.ajax({
                    url: "{{ url('/agent/encaissement/mes-encaissements') }}/" + id,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        if (response.success) {
                            // alert(response.message);
                            $('#historyTable').DataTable().ajax.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Une erreur est survenue: ' + (xhr.responseJSON ? xhr.responseJSON.message :
                            'Erreur inconnue'));
                    }
                });
            }
        }
    </script>
@endpush

@push('css')
    <style>
        #historyTable .btn {
            white-space: nowrap;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.5em 0.75em;
        }
    </style>
@endpush
