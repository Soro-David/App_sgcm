@extends('agent.layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="row mb-3">
            <div class="col d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="h3 mb-2">Liste des Commerçants</h1>
                <div class="d-flex gap-2">
                    <button id="printSelectedBtn" class="btn btn-primary d-none">
                        <i class="fas fa-print me-1"></i> Imprimer la sélection
                    </button>
                    <a href="{{ route('agent.contribuable.print_bulk_cards', ['ids' => 'all']) }}" target="_blank"
                        class="btn btn-outline-primary">
                        <i class="fas fa-print me-1"></i> Imprimer Tout
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="commercantsTable" class="table table-bordered table-hover" style="width:100%"
                        data-ajax-url="{{ route('agent.contribuable.list_commercant') }}"
                        data-lang-url="{{ asset('js/fr-FR.json') }}"
                        data-print-url="{{ route('agent.contribuable.print_bulk_cards') }}">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 30px;">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>Date de Création</th>
                                <th>Numéro du Commerce</th>
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
@endsection

@push('js')
    <script src="{{ asset('assets/js/agent_commerce_index.js') }}"></script>
@endpush

@push('css')
    <style>

    </style>
@endpush
