@extends('agent.layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="row mb-3">
            <div class="col d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="h3 mb-2">Liste des Commerçants</h1>
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
