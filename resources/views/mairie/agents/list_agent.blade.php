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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Liste des Agents Collectors</h3>
                <a href="{{ route('mairie.agents.add_agent') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Ajouter un agent
                </a>
            </div>
            <div class="container card-body">
                <div class="table-responsive">
                    <table id="agents-table" class="table table-bordered table-hover" style="width:100%"
                        data-url="{{ route('mairie.agents.get_list_agent') }}" data-lang-url="{{ asset('js/fr-FR.json') }}">
                        <thead class="table-light">
                            <tr>
                                <th>Ajouter Par</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Date de Création</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/agent_list.js') }}"></script>
@endpush
