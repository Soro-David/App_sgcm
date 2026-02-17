@extends('mairie.layouts.app')

@section('content')
    <div class="container py-5">
        <h4 class="fw-bold mb-4">Modifier le personnel</h4>
        <div class="card shadow-sm w-100 border-0">
            <div class="card-body">
                {{-- Messages de session --}}
                @if (session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('mairie.agents.update_personnel', $personnel->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Nom & Prénoms --}}
                        {{-- @dd($personnel) --}}
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label">Nom & Prénoms *</label>
                            <input type="text" class="form-control" id="name" name="name" required
                                value="{{ old('name', $personnel->name) }}">
                        </div>
                        {{-- Genre --}}
                        <div class="col-md-6 mb-4">
                            <label for="genre" class="form-label">Genre *</label>
                            <select name="genre" id="genre" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionnez le genre --</option>
                                <option value="masculin"
                                    {{ old('genre', $personnel->genre) == 'masculin' ? 'selected' : '' }}>
                                    Masculin</option>
                                <option value="féminin"
                                    {{ old('genre', $personnel->genre) == 'féminin' ? 'selected' : '' }}>
                                    Féminin</option>
                            </select>
                        </div>

                        {{-- Date de naissance --}}
                        <div class="col-md-6 mb-4">
                            <label for="date_naissance" class="form-label">Date de naissance *</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required
                                value="{{ old('date_naissance', $personnel->date_naissance ? Carbon\Carbon::parse($personnel->date_naissance)->format('Y-m-d') : '') }}">
                        </div>
                        {{-- Type de pièce --}}
                        <div class="col-md-6 mb-4">
                            <label for="type_piece" class="form-label">Type de Pièce *</label>
                            <select name="type_piece" id="type_piece" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionnez un type --</option>
                                <option value="cni"
                                    {{ old('type_piece', $personnel->type_piece) == 'cni' ? 'selected' : '' }}>CNI</option>
                                <option value="passport"
                                    {{ old('type_piece', $personnel->type_piece) == 'passport' ? 'selected' : '' }}>
                                    Passeport
                                </option>
                                <option value="autre"
                                    {{ old('type_piece', $personnel->type_piece) == 'autre' ? 'selected' : '' }}>Autre
                                </option>
                            </select>
                        </div>
                        {{-- Numéro de pièce --}}
                        <div class="col-md-6 mb-4">
                            <label for="numero_piece" class="form-label">N° de Pièce *</label>
                            <input type="text" class="form-control" id="numero_piece" name="numero_piece" required
                                value="{{ old('numero_piece', $personnel->numero_piece) }}">
                        </div>
                        {{-- Type d'agent --}}
                        <div class="col-md-6 mb-4">
                            <label for="type_agent" class="form-label">Type d'agent *</label>
                            <select name="type_agent" id="type_agent" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionnez un type d'agent --</option>
                                <option value="responsable_financier"
                                    {{ old('type_agent', $personnel->role) == 'responsable_financier' ? 'selected' : '' }}>
                                    Responsable financier</option>
                                <option value="caissier"
                                    {{ old('type_agent', $personnel->role) == 'caissier' ? 'selected' : '' }}>
                                    Caissier</option>
                                <option value="finance"
                                    {{ old('type_agent', $personnel->role) == 'finance' ? 'selected' : '' }}>
                                    Agent financier</option>
                            </select>
                        </div>
                        {{-- Lieu d'habitation --}}
                        <div class="col-md-6 mb-4">
                            <label for="adresse" class="form-label">Lieu d'habitation *</label>
                            <input type="text" class="form-control" id="adresse" name="adresse" required
                                value="{{ old('adresse', $personnel->adresse) }}">
                        </div>
                        {{-- Contact 1 --}}
                        <div class="col-md-6 mb-4">
                            <label for="telephone1" class="form-label">Contact 1 *</label>
                            <input type="text" class="form-control" id="telephone1" maxlength="10" name="telephone1"
                                required value="{{ old('telephone1', $personnel->telephone1) }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        {{-- Contact 2 --}}
                        <div class="col-md-6 mb-4">
                            <label for="telephone2" class="form-label">Contact 2</label>
                            <input type="text" class="form-control" id="telephone2" maxlength="10" name="telephone2"
                                value="{{ old('telephone2', $personnel->telephone2) }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        {{-- Adresse e-mail --}}
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label">Adresse e-mail *</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                value="{{ old('email', $personnel->email) }}">
                        </div>
                    </div>

                    {{-- Boutons --}}
                    <div class="d-flex justify-content-end mt-5">
                        <a href="{{ route('mairie.agents.index') }}" class="btn btn-secondary px-4 py-2 me-2">Annuler</a>
                        <button type="submit" class="btn btn-primar px-4 py-2"><i class="fa fa-save"></i> Mettre à
                            jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
