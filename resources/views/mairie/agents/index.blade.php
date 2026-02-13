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

        <div class="card">
            <div class="container card-body">
                <div class="table-responsive">
                    <table id="mairiesTable" class="table table-bordered table-hover" style="width:100%"
                        data-ajax-url="{{ route('mairie.agents.get_list_mairie') }}"
                        data-lang-url="{{ asset('js/fr-FR.json') }}">
                        <thead class="table-light">
                            <tr>
                                <th>Date de Création</th>
                                <th>Ajouter Par</th>
                                <th>Nom</th>
                                <th>Rôle</th>
                                <th>Email</th>
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
    <script src="{{ asset('assets/js/agents.js') }}"></script>
@endpush
