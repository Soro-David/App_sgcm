@extends('mairie.layouts.app')

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
            <h1 class="h3 mb-2">Liste des Versements</h1>
        </div>
    </div>

    <div class="card" id="versement-container" data-montant-url="{{ route('mairie.versements.montant_nonverse', ['agent_id' => 'AGENT_ID']) }}">
        <div class="card-body">
            <div class="table-responsive">
                <table id="versementsTable"
                       class="table table-bordered table-hover"
                       style="width:100%"
                       data-ajax-url="{{ route('mairie.versements.versements_liste') }}"
                       data-lang-url="{{ asset('js/fr-FR.json') }}">
                    <thead class="table-light">
                        <tr>
                            <th>Date Versement</th>
                            <th>Nom Agent</th>
                            <th>Montant Percu</th>
                            <th>Dette</th>
                            <th>Montant Versé</th>
                            <th>Montant Restant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/versements.js') }}"></script>
@endpush