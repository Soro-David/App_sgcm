@extends('agent.layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3">Liste des Contribuables à Encaisser</h1>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="commercantsTable"
                       class="table table-bordered table-hover"
                       style="width:100%"
                       data-ajax-url="{{ route('agent.encaissement.get_list_commercant') }}"
                       data-edit-url="{{ route('agent.encaissement.edit', ['encaissement' => '__ID__']) }}"
                       data-lang-url="{{ asset('js/fr-FR.json') }}">
                    <thead class="table-light">
                        <tr>
                            <th>N° Commerce</th>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Dernier Paiement</th>
                            <th>Statut (Mois)</th>
                            <th style="width: 180px;">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/agent_encaissement_list.js') }}"></script>
@endpush

@push('css')
<style>
    #commercantsTable tbody tr { cursor: pointer; }
    .row-green { background-color: #259b2f !important; }
    #commercantsTable .btn { white-space: nowrap; }
</style>
@endpush