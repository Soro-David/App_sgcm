@extends('mairie.layouts.app')

@section('content')
    <div class="container-fluid">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row mb-3">
            <div class="col d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="h3 mb-2">Liste des secteurs</h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('mairie.secteurs.export.excel') }}" class="btn btn-outline-success">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="{{ route('mairie.secteurs.export.pdf') }}" class="btn btn-outline-danger">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMairieModal">
                        <i class="fas fa-plus"></i> Ajouter un secteur
                    </button>
                </div>
            </div>
        </div>

        <!-- Import Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Importer des secteurs</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('mairie.secteurs.import') }}" method="POST" enctype="multipart/form-data"
                    class="row g-3 align-items-center">
                    @csrf
                    <div class="col-auto">
                        <label for="file" class="visually-hidden">Fichier</label>
                        <input type="file" name="file" class="form-control" id="file" accept=".xlsx,.xls,.csv"
                            required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary mb-3 mt-3">
                            <i class="fas fa-upload"></i> Importer
                        </button>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Format supporté: Excel (.xlsx, .xls), CSV. La colonne doit être nommée
                            "nom" ou "nom du secteur".</small>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4" data-code-generator-url="{{ route('mairie.secteurs.genererCode') }}">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Liste des secteurs</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="secteurs-table" data-ajax-url="{{ route('mairie.secteurs.list') }}"
                        data-code-generator-url="{{ route('mairie.secteurs.genererCode') }}"
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
                            {{-- Rempli par DataTables --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Le Modal d'ajout est inclus ici --}}
    @include('mairie.secteur.partials.add_secteur')
    {{-- Le Modal de modification est inclus ici --}}
    @include('mairie.secteur.partials.edit_secteur')
@endsection

@push('js')
    <script src="{{ asset('assets/js/secteurs.js') }}"></script>
@endpush
