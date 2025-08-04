@extends('mairie.layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- CORRECTION : Le meta tag CSRF a été déplacé dans le layout principal --}}

        {{-- Affichage des messages de session --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row mb-3">
            <div class="col d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="h3 mb-2">Liste des Agents et les tache</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMairieModal">
                    <i class="fas fa-plus"></i> Ajouter un agents
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="mairiesTable" 
                        class="table table-bordered table-hover" 
                        style="width:100%"
                        data-ajax-url="{{ route('mairie.agents.get_list_mairie') }}"
                        data-lang-url="{{ asset('js/fr-FR.json') }}">
                        <thead class="table-light">
                            <tr>
                                <th>Date de Création</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        {{-- <tfoot class="table-light">
                            <tr>
                                <th><input type="text" placeholder="Rechercher..." class="form-control form-control-sm"></th>
                                <th><input type="text" placeholder="Rechercher..." class="form-control form-control-sm"></th>
                                <th><input type="text" placeholder="Rechercher..." class="form-control form-control-sm"></th>
                                <th></th>
                            </tr>
                        </tfoot> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Le Modal d'ajout est inclus ici --}}
    @include('mairie.tache.partials.add_tache')
@endsection

@push('js')
    <script src="{{ asset('assets/js/agents.js') }}"></script>
@endpush