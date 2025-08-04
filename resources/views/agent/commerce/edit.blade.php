@extends('agent.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-4">
            <h1 class="h3 mb-2">Modifier un commerçant</h1>
            <form action="{{ route('agent.commerce.update', $commercant->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label>Nom</label>
                        <input type="text" name="nom" value="{{ old('nom', $commercant->nom) }}" class="form-control" required>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $commercant->email) }}" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label>Téléphone</label>
                        <input type="text" name="telephone" value="{{ old('telephone', $commercant->telephone) }}" class="form-control">
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Adresse</label>
                        <input type="text" name="adresse" value="{{ old('adresse', $commercant->adresse) }}" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label>Secteur</label>
                        <select name="secteur_id" class="form-select" required>
                            @foreach($secteurs as $secteur)
                                <option value="{{ $secteur->id }}" {{ $commercant->secteur_id == $secteur->id ? 'selected' : '' }}>
                                    {{ $secteur->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label>Taxe(s) applicable(s)</label>
                        <select name="taxe_ids[]" class="form-select select2" multiple="multiple" required>
                            @foreach ($taxes as $taxe)
                                <option value="{{ $taxe->id }}" 
                                    {{ in_array($taxe->id, $selectedTaxes ?? []) ? 'selected' : '' }}>
                                    {{ $taxe->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button class="btn btn-primary" type="submit">Mettre à jour</button>
                <a href="{{ route('agent.commerce.index') }}" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
@endsection