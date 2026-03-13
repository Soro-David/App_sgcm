@extends('mairie.layouts.app')

@section('content')
    <div class="container py-5">
        <h3 class="fw-bold mb-4">Modifier l'Agent de la régie</h3>
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

                <form method="POST" action="{{ route('mairie.finance.update', ['source' => $source, 'id' => $agent->id]) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-12 mt-4 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="fw-bold text-primary text-uppercase small me-3">Informations
                                    personnelles</span>
                                <div class="flex-grow-1 border-top border-primary opacity-25"></div>
                            </div>
                        </div>
                        {{-- Nom & Prénoms --}}
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label">Nom & Prénoms <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required
                                value="{{ old('name', $agent->name) }}">
                        </div>
                        {{-- Genre --}}
                        <div class="col-md-6 mb-4">
                            <label for="genre" class="form-label">Genre <span class="text-danger">*</span></label>
                            <select name="genre" id="genre" class="form-select" required>
                                <option value="" disabled>-- Sélectionnez le genre --</option>
                                <option value="masculin" {{ old('genre', $agent->genre) == 'masculin' ? 'selected' : '' }}>
                                    Masculin
                                </option>
                                <option value="féminin" {{ old('genre', $agent->genre) == 'féminin' ? 'selected' : '' }}>
                                    Féminin</option>
                            </select>
                        </div>

                        {{-- Date de naissance --}}
                        <div class="col-md-6 mb-4">
                            <label for="date_naissance" class="form-label">Date de naissance <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date_naissance') is-invalid @enderror"
                                id="date_naissance" name="date_naissance" required
                                value="{{ old('date_naissance', $agent->date_naissance ? \Carbon\Carbon::parse($agent->date_naissance)->format('Y-m-d') : '') }}">
                            @error('date_naissance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Matricule --}}
                        <div class="col-md-6 mb-4">
                            <label for="matricule" class="form-label">Matricule<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="matricule" name="matricule" required
                                value="{{ old('matricule', $agent->matricule) }}">
                        </div>

                        {{-- Lieu d'habitation --}}
                        <div class="col-md-6 mb-4">
                            <label for="adresse" class="form-label">Lieu d'habitation <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="adresse" name="adresse" required
                                value="{{ old('adresse', $agent->adresse) }}">
                        </div>
                        {{-- Contact --}}
                        <div class="col-md-6 mb-4">
                            <label for="telephone1" class="form-label">Contact <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="telephone1" name="telephone1" maxlength="10"
                                required value="{{ old('telephone1', $agent->telephone1) }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        {{-- Adresse e-mail --}}
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label">Adresse e-mail <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required
                                value="{{ old('email', $agent->email) }}">
                        </div>
                        {{-- Type d'agent --}}
                        <div class="col-md-6 mb-4">
                            <label for="type_agent" class="form-label">Type d'agent <span
                                    class="text-danger">*</span></label>
                            <select name="type_agent" id="type_agent" class="form-select" required
                                {{ $source === 'financier' ? 'disabled' : '' }}>
                                <option value="" disabled>-- Sélectionnez un type d'agent --</option>
                                <option value="responsable_financier"
                                    {{ old('type_agent', $agent->role) == 'financiers' || old('type_agent', $agent->role) == 'responsable_financier' ? 'selected' : '' }}>
                                    Responsable de la régie</option>
                                <option value="caissier"
                                    {{ old('type_agent', $agent->role) == 'caissier' ? 'selected' : '' }}>
                                    Caissier</option>
                                <option value="finance"
                                    {{ old('type_agent', $agent->role) == 'finance' ? 'selected' : '' }}>
                                    Agent de la régie</option>
                            </select>
                            @if ($source === 'financier')
                                <input type="hidden" name="type_agent" value="responsable_financier">
                            @endif
                        </div>
                        <div class="col-12 mt-4 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="fw-bold text-primary text-uppercase small me-3">Urgence & Contact</span>
                                <div class="flex-grow-1 border-top border-primary opacity-25"></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <x-select2-filiation :current-value="old('filiation', $agent->filiation)" />
                        </div>
                        {{-- Contact --}}
                        <div class="col-md-6 mb-4">
                            <label for="telephone2" class="form-label">Contact <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="telephone2" maxlength="10" name="telephone2"
                                value="{{ old('telephone2', $agent->telephone2) }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                        </div>

                    </div>

                    {{-- Boutons --}}
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('mairie.finance.index') }}"
                            class="btn btn-secondary px-4 py-2 me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm">
                            <i class="fa fa-save me-2"></i> Mettre à jour l'Agent
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
