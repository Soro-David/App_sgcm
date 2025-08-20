@extends('mairie.layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-3">Liste des Encaissements</h1>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="encaissementsTable" 
                            class="table table-bordered table-hover" 
                            style="width:100%"
                            data-ajax-url="{{ route('mairie.encaissement.get_list') }}"
                            data-lang-url="{{ asset('js/fr-FR.json') }}">
    
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Montant Percu</th>
                                <th>Agent</th>
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
    <script src="{{ asset('assets/js/mairie_encaissements.js') }}"></script>
@endpush