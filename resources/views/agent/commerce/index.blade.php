@extends('agent.layouts.app')

@section('content')
    <div class="container-fluid">

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
        {{-- Avertissement si pas lie à une taxe ni secteur --}}
        @if($warningMessage)
            <div class="alert alert-warning">
                {{ $warningMessage }}
            </div>
        @endif

        <div class="row mb-3">
            <div class="col d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="h3 mb-2">Liste des Commerçants</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMairieModal">
                    <i class="fas fa-plus"></i> Ajouter un Commerçant
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="commercantsTable"
                        class="table table-bordered table-hover"
                        style="width:100%"
                        data-ajax-url="{{ route('agent.commerce.list_commercant') }}"
                        data-lang-url="{{ asset('js/fr-FR.json') }}">
                        <thead class="table-light">
                            <tr>
                                <th>Date de Création</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>

    {{-- Le Modal d'ajout est inclus ici --}}
    @include('agent.commerce.partials.add_comm')
@endsection

@push('js')
    <script src="{{ asset('assets/js/agent_commerce.js') }}"></script>
@endpush