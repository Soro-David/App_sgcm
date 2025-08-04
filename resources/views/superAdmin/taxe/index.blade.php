@extends('superAdmin.layouts.app')

@section('content')
<div class="container-fluid">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-3">
        <div class="col d-flex justify-content-between align-items-center flex-wrap">
            <h1 class="h3 mb-2">Liste des Taxes</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMairieModal">
                <i class="fas fa-plus"></i> Ajouter une nouvelle taxe
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="taxesTable" 
                    class="table table-bordered table-hover"
                    data-ajax-url="{{ route('superadmin.taxes.get_list_taxes') }}"
                    data-lang-url="{{ asset('js/fr-FR.json') }}">
                    <thead class="table-light">
                        <tr>
                            <th>Date de Création</th>
                            <th>Nom</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot class="table-light">
                        <tr>
                            <th><input type="text" placeholder="Rechercher..." class="form-control form-control-sm"></th>
                            <th><input type="text" placeholder="Rechercher..." class="form-control form-control-sm"></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Le Modal d'ajout est inclus ici --}}
@include('superAdmin.taxe.partials.add_taxe')
@endsection

@push('js')
<script src="{{ asset('assets/js/taxes.js') }}"></script>
@endpush
