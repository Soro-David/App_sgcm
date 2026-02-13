@extends('commercant.layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="container mt-4">
                    <h3>Historique des paiements</h3>
                    <div class="table-responsive">
                        <table class="table" id="historique-table" data-lang-url="{{ asset('js/fr-FR.json') }}">
                            <thead>
                                <tr>
                                    <th>Taxe</th>
                                    <th>Montant</th>
                                    <th>Date de Paiement</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Configuration passée de Blade au JS
        window.payementConfig = {
            historiqueUrl: "{{ route('commercant.payement.historique') }}",
        };
    </script>
    {{-- Assurez-vous que le chemin vers votre fichier JS est correct --}}
    <script src="{{ asset('assets/js/commercant_payement.js') }}"></script>
@endpush
