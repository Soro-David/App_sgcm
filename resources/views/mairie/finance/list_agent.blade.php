@extends('mairie.layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Liste des Agents</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="agents-table" class="table table-bordered table-striped" style="width:100%"
                        data-url="{{ route('mairie.finance.get_list') }}" data-lang-url="{{ asset('js/fr-FR.json') }}">
                        <thead>
                            <tr>
                                <th>Ajouter Par</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Le corps du tableau sera rempli dynamiquement par DataTables --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- L'inclusion de votre fichier JS externe est correcte --}}
    <script src="{{ asset('assets/js/agent_list.js') }}"></script>
@endpush
