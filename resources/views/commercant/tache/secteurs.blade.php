@extends('mairie.layouts.app')

@section('content')
<div class="container-fluid">

    {{-- Affichage des messages de session (succès ou erreur) --}}
    @if(session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    {{-- Formulaire d'ajout d'un nouveau secteur --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ajouter un nouveau secteur</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('mairie.secteurs.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code_secteur">Code Secteur</label>
                            {{-- Le champ est readonly car le code est généré automatiquement --}}
                            <input type="text" class="form-control @error('code_secteur') is-invalid @enderror" id="code_secteur" name="code_secteur" readonly required>
                            @error('code_secteur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nom">Nom du secteur</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Ajouter le secteur</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tableau listant les secteurs existants --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des secteurs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="secteurs-table" width="100%" cellspacing="0"
                        data-ajax-url="{{ route('mairie.secteurs.list') }}"
                        data-code-generator-url="{{ route('mairie.secteurs.genererCode') }}"
                        style="width:100%"
                        data-lang-url="{{ asset('js/fr-FR.json') }}">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code Secteur</th>
                            <th>Nom</th>
                            <th>Date de création</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Le contenu de la table sera chargé dynamiquement par DataTables --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    {{-- Inclusion du fichier JavaScript dédié à cette page --}}
    <script src="{{ asset('assets/js/secteurs.js') }}"></script>
@endpush