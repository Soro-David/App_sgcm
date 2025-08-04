@extends('mairie.layouts.app')

@section('content')
<div class="container-fluid">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Programmer un agent à des taxes</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('mairie.agents.store_programme_agent') }}" method="POST">
                @csrf
                <div class="row">
                    {{-- Agent --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="agent_id">Sélectionnez un agent</label>
                            <select class="form-control select2 @error('agent_id') is-invalid @enderror" name="agent_id" id="agent_id" required>
                                <option value="">-- Choisir un agent --</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                @endforeach
                            </select>
                            @error('agent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="secteur_id">Sélectionnez un secteur</label>
                            <select class="form-control select2 @error('secteur_id') is-invalid @enderror" name="secteur_id" id="secteur_id" required>
                                <option value="">-- Choisir un secteur --</option>
                                @foreach($secteurs as $secteur)
                                    <option value="{{ $secteur->id }}">{{ $secteur->nom }}</option>
                                @endforeach
                            </select>
                            @error('secteur_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    {{-- Taxes --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="taxe_ids">Sélectionnez une ou plusieurs taxes</label>
                            <select class="form-control select2 @error('taxe_ids') is-invalid @enderror" name="taxe_ids[]" id="taxe_ids" multiple required>
                                @foreach($taxes as $taxe)
                                    <option value="{{ $taxe->id }}">{{ $taxe->nom }}</option>
                                @endforeach
                            </select>
                            @error('taxe_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
  {{-- Tableau listant les programmes --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Programmes</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="programmes-table" width="100%" cellspacing="0"
                       data-ajax-url="{{ route('mairie.agents.list_programmes') }}"
                       data-lang-url="{{ asset('js/fr-FR.json') }}">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom Agent</th>
                            <th>Secteur</th>
                            <th>Taxes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Le contenu sera chargé dynamiquement par DataTables --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="{{ asset('assets/js/programme_agents.js') }}"></script>

@endpush
