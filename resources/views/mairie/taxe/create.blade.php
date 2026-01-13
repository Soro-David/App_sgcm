@extends('mairie.layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                {{-- Messages de session --}}
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>Ajouter une Taxe</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('mairie.taxe.store') }}">
                            @csrf

                            {{-- Nom --}}
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom de la taxe *</label>
                                <input type="text" name="nom" id="nom" class="form-control"
                                    value="{{ old('nom') }}" required>
                            </div>

                            {{-- Montant --}}
                            <div class="mb-3">
                                <label for="montant" class="form-label">Montant (FCFA)</label>
                                <input type="number" step="0.01" name="montant" id="montant" class="form-control"
                                    value="{{ old('montant') }}">
                            </div>

                            {{-- Fréquence --}}
                            <div class="mb-3">
                                <label for="frequence" class="form-label">Fréquence *</label>
                                <select name="frequence" id="frequence" class="form-select" required>
                                    <option value="">-- Sélectionnez --</option>
                                    <option value="jour" {{ old('frequence') == 'jour' ? 'selected' : '' }}>Journalier
                                    </option>
                                    <option value="mois" {{ old('frequence') == 'mois' ? 'selected' : '' }}>Mensuel
                                    </option>
                                    <option value="an" {{ old('frequence') == 'an' ? 'selected' : '' }}>Annuel</option>
                                </select>
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>

                            {{-- Mairie ID (caché) --}}
                            <input type="hidden" name="mairie_ref" value="{{ Auth::guard('mairie')->user()->mairie_ref }}">

                            {{-- Boutons --}}
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('mairie.dashboard.index') }}" class="btn btn-secondary me-2">Annuler</a>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
