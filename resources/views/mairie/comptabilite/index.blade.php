@extends('mairie.layouts.app')

@section('content')
<div class="container-fluid">

    {{-- Affichage des messages de session --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <h1 class="h3 mb-3">Historique des Versements</h1>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="versementsTable"
                       class="table table-bordered table-hover"
                       style="width:100%"
                       data-ajax-url="{{ route('mairie.versements.versements_liste') }}"
                       data-lang-url="{{ asset('js/fr-FR.json') }}">
                    <thead class="table-light">
                        {{-- CORRECTION : Les colonnes correspondent maintenant au contrôleur et au JS --}}
                        <tr>
                            <th>Date du Versement</th>
                            <th>Nom de l'Agent</th>
                            <th>Montant Total Dû</th>
                            <th>Montant Versé</th>
                            <th>Reste à verser</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    {{-- On suppose que ce JS est pour le formulaire d'ajout, pas pour la table --}}
    {{-- <script src="{{ asset('assets/js/versement_form.js') }}"></script> --}}

    {{-- On va mettre le JS de la table ici pour plus de clarté ou dans un fichier dédié --}}
    <script src="{{ asset('assets/js/versements_historique.js') }}"></script>
@endpush