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
                <h1 class="h3 mb-2">Liste des tache</h1>
                {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMairieModal">
                    <i class="fas fa-plus"></i> Ajouter un agents
                </button> --}}
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                   <div class="row">
                        <div class="col-md-6">
                            <label for=""> Selection un agent</label>
                            <select name="" id="">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for=""> Selection une Taxe</label>
                            <select name="" id="">
                                <option value=""></option>
                            </select>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-md-6">
                            <label for="">Secteur</label>
                            <select name="" id="">
                                <option value=""></option>
                            </select>
                        </div>
                   </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Le Modal d'ajout est inclus ici --}}
    {{-- @include('mairie.tache.partials.add_tache') --}}
@endsection

@push('js')
    <script src="{{ asset('assets/js/taches.js') }}"></script>
@endpush