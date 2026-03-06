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
                <h1 class="h3 mb-2">Liste des taxes</h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('mairie.taxe.export.excel') }}" class="btn btn-outline-success">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="{{ route('mairie.taxe.export.pdf') }}" class="btn btn-outline-danger">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaxeModal">
                        <i class="fas fa-plus"></i> Ajouter une taxe
                    </button>
                </div>
            </div>
        </div>

        <!-- Import Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Importer des taxes</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('mairie.taxe.import') }}" method="POST" enctype="multipart/form-data"
                    class="row g-3 align-items-center">
                    @csrf
                    <div class="col-auto">
                        <label for="file" class="visually-hidden">Fichier</label>
                        <input type="file" name="file" class="form-control" id="file" accept=".xlsx,.xls,.csv"
                            required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Importer
                        </button>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Format supporté: Excel (.xlsx, .xls), CSV. Colonnes : <strong>nom,
                                frequence, montant</strong>. La fréquence doit être l'une des suivantes : jour, mois,
                            an.</small>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="taxesTable" class="table table-bordered table-hover" style="width:100%"
                        data-ajax-url="{{ route('mairie.taxe.get_list_taxes') }}"
                        data-lang-url="{{ asset('js/fr-FR.json') }}">

                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Date de Création</th>
                                <th>Fréquence</th>
                                <th>Montant</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Inclusion du modal d'ajout --}}
    @include('mairie.taxe.partials.add_taxe')
    {{-- Inclusion du modal de modification --}}
    @include('mairie.taxe.partials.edit_taxe')
@endsection

@push('js')
    <script src="{{ asset('assets/js/taxes.js') }}"></script>
@endpush
