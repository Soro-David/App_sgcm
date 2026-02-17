@extends('mairie.layouts.app')

@section('content')
    <div class="container py-5">
        <h4 class="fw-bold mb-4">Ajout d'un personnel</h4>
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

                <form method="POST" action="{{ route('mairie.agents.store') }}">
                    @csrf
                    <div class="row">
                        {{-- Nom & Prénoms --}}
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label">Nom & Prénoms *</label>
                            <input type="text" class="form-control" id="name" name="name" required
                                value="{{ old('name') }}">
                        </div>
                        {{-- Genre --}}
                        <div class="col-md-6 mb-4">
                            <label for="genre" class="form-label">Genre *</label>
                            <select name="genre" id="genre" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionnez le genre --</option>
                                <option value="masculin" {{ old('genre') == 'masculin' ? 'selected' : '' }}>Masculin
                                </option>
                                <option value="féminin" {{ old('genre') == 'féminin' ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>

                        {{-- Date de naissance --}}
                        <div class="col-md-6 mb-4">
                            <label for="date_naissance" class="form-label">Date de naissance *</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required
                                value="{{ old('date_naissance') }}">
                        </div>
                        {{-- Type de pièce --}}
                        <div class="col-md-6 mb-4">
                            <label for="type_piece" class="form-label">Type de Pièce *</label>
                            <select name="type_piece" id="type_piece" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionnez un type --</option>
                                <option value="cni" {{ old('type_piece') == 'cni' ? 'selected' : '' }}>CNI</option>
                                <option value="passport" {{ old('type_piece') == 'passport' ? 'selected' : '' }}>Passeport
                                </option>
                                <option value="autre" {{ old('type_piece') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        {{-- Numéro de pièce --}}
                        <div class="col-md-6 mb-4">
                            <label for="numero_piece" class="form-label">N° de Pièce *</label>
                            <input type="text" class="form-control" id="numero_piece" name="numero_piece" required
                                value="{{ old('numero_piece') }}">
                        </div>
                        {{-- Type d'agent --}}
                        <div class="col-md-6 mb-4">
                            <label for="type_agent" class="form-label">Type d'agent *</label>
                            <select name="type_agent" id="type_agent" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionnez un type d'agent --</option>
                                <option value="responsable_financier"
                                    {{ old('type_agent') == 'responsable_financier' ? 'selected' : '' }}>
                                    Responsable financier</option>
                                <option value="caissier" {{ old('type_agent') == 'caissier' ? 'selected' : '' }}>
                                    Caissier</option>
                                <option value="finance" {{ old('type_agent') == 'finance' ? 'selected' : '' }}>
                                    Agent financier</option>
                            </select>
                        </div>
                        {{-- Lieu d'habitation --}}
                        <div class="col-md-6 mb-4">
                            <label for="adresse" class="form-label">Lieu d'habitation *</label>
                            <input type="text" class="form-control" id="adresse" name="adresse" required
                                value="{{ old('adresse') }}">
                        </div>
                        {{-- Contact 1 --}}
                        <div class="col-md-6 mb-4">
                            <label for="telephone1" class="form-label">Contact 1 *</label>
                            <input type="tel" class="form-control" id="telephone1" name="telephone1" maxlength="9" required
                                value="{{ old('telephone1') }}">
                        </div>
                        {{-- Contact 2 --}}
                        <div class="col-md-6 mb-4">
                            <label for="telephone2" class="form-label">Contact 2</label>
                            <input type="tel" class="form-control" id="telephone2" name="telephone2" maxlength="10"
                                value="{{ old('telephone2') }}">
                        </div>
                        {{-- Adresse e-mail --}}
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label">Adresse e-mail *</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                value="{{ old('email') }}">
                        </div>
                    </div>
                    <input type="hidden" name="mairie_ref" value="{{ $mairie->mairie_ref }}">
                    @if ($mairie->commune)
                        <input type="hidden" name="region" value="{{ $mairie->region }}">
                        <input type="hidden" name="commune" value="{{ $mairie->commune }}">
                    @endif


                    {{-- Boutons --}}
                    <div class="d-flex justify-content-end mt-5">
                        <button type="submit" class="btn btn-primar px-4 py-2"><i class="fa fa-save"></i>
                            Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
