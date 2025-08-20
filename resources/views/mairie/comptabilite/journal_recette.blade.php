@extends('mairie.layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Section du formulaire de recherche --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary text-center">Journal de Recette</h4>
        </div>

        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Le name="search_form" est utilisé par le JS --}}
            <form id="search_form" action="{{ route('mairie.comptabilite.journal_recette') }}" method="GET">
                <div class="row">
                    {{-- Taxe --}}
                    <div class="col-md-6 mb-3">
                        <label for="taxe_id">Sélectionnez une taxe</label>
                        <select name="taxe_id" id="taxe_id" class="form-select select2">
                            <option value="">-- Toutes les taxes --</option>
                            @foreach ($taxes ?? [] as $taxe)
                                <option value="{{ $taxe->id }}" {{ request('taxe_id') == $taxe->id ? 'selected' : '' }}>
                                    {{ $taxe->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Secteur --}}
                    <div class="col-md-6 mb-3">
                        <label for="secteur_id">Sélectionnez un secteur</label>
                        <select name="secteur_id" id="secteur_id" class="form-select select2">
                            <option value="">-- Tous les secteurs --</option>
                            @foreach ($secteurs ?? [] as $secteur)
                                <option value="{{ $secteur->id }}" {{ request('secteur_id') == $secteur->id ? 'selected' : '' }}>
                                    {{ $secteur->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" id="search_button" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        🔍 Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Section des résultats (sera remplie par AJAX) --}}
    <div id="results_container">
        {{-- Si la page est chargée avec des résultats (sans AJAX), on les inclut ici --}}
        @if(request()->has('taxe_id') || request()->has('secteur_id'))
            @include('mairie.comptabilite.partials.resultats_recette')
        @endif
    </div>
</div>
@endsection

@push('js')
    {{-- Le nom du fichier JS a été mis à jour pour correspondre à la page --}}
    <script src="{{ asset('assets/js/mairie_journal_recette.js') }}"></script>
@endpush