@extends('mairie.layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-3">Liste des Paiements des Commerçants</h1>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="paiementsTable"
                            class="table table-bordered table-hover" 
                            style="width:100%"
                            data-ajax-url="{{ route('mairie.paiement.get_list') }}"
                            data-lang-url="{{ asset('js/fr-FR.json') }}">
                        <thead class="table-light">
                            <tr>
                                <th>Date du Paiement</th>
                                <th>Période Réglée</th>
                                <th>Montant</th>
                                <th>Taxe</th>
                                <th>Commerçant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/mairie_paiements.js') }}"></script>
@endpush