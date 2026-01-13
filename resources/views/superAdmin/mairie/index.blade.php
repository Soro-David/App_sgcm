@extends('superAdmin.layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- Affichage des messages de session --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="h3 mb-2">Liste des mairies</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMairieModal">
                    <i class="fas fa-plus"></i> Ajouter une mairie
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="mairiesTable" class="table table-bordered table-hover" style="width:100%"
                        data-ajax-url="{{ route('superadmin.mairies.get_list_mairie') }}"
                        data-lang-url="{{ asset('js/fr-FR.json') }}">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Nom mairie</th>
                                <th>email</th>
                                {{-- <th>Commune</th>
                            <th>Création</th> --}}
                                <th style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Inclusion du Modal d'ajout --}}
    @include('superAdmin.mairie.partials.add_mairie_modal')
@endsection

@push('js')
    <script src="{{ asset('assets/js/mairies.js') }}"></script>
@endpush
