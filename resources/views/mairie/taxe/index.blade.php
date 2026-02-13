
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
                <button class="btn btn-primar" data-bs-toggle="modal" data-bs-target="#addTaxeModal">
                    <i class="fas fa-plus"></i> Ajouter une taxe
                </button>
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

