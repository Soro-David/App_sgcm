@extends('mairie.layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Liste des Agents</h3>
        </div>
        <div class="card-body">
            {{-- 
                Ce code est correct. L'attribut data-url passe l'URL nécessaire 
                à notre fichier JavaScript pour la requête AJAX.
            --}}
            <table id="agents-table" class="table table-bordered table-striped" style="width:100%"
                   data-url="{{ route('mairie.agents.get_list_agent') }}"
                   data-lang-url="{{ asset('js/fr-FR.json') }}">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th> {{-- L'en-tête est "Rôle" --}}
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
@endsection

@push('js')
    {{-- L'inclusion de votre fichier JS externe est correcte --}}
    <script src="{{ asset('assets/js/agent_list.js') }}"></script>
@endpush