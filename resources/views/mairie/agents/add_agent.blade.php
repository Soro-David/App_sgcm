@extends('mairie.layouts.app')

@section('content')
    <div class="container py-5">
        <h3 class="fw-bold mb-4">Ajout d'un Agent</h3>
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

                <form method="POST" action="{{ route('mairie.agents.store_agent') }}">
                    @csrf

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
                                value="{{ old('name') }}">
                        </div>
                        {{-- Genre --}}
                        <div class="col-md-6 mb-4">
                            <label for="genre" class="form-label">Genre <span class="text-danger">*</span></label>
                            <select name="genre" id="genre" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionnez le genre --</option>
                                <option value="masculin" {{ old('genre') == 'masculin' ? 'selected' : '' }}>Masculin
                                </option>
                                <option value="féminin" {{ old('genre') == 'féminin' ? 'selected' : '' }}>Féminin</option>
                            </select>
                        </div>

                        {{-- Date de naissance --}}
                        <div class="col-md-6 mb-4">
                            <label for="date_naissance" class="form-label">Date de naissance <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('date_naissance') is-invalid @enderror"
                                id="date_naissance" name="date_naissance" required value="{{ old('date_naissance') }}">
                            @error('date_naissance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- <div class="col-md-6 mb-4">
                            <label for="type_piece" class="form-label">Type de Pièce  <span
                                    class="text-danger">*</span></label>
                            <select name="type_piece" id="type_piece" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionnez un type --</option>
                                <option value="cni" {{ old('type_piece') == 'cni' ? 'selected' : '' }}>CNI</option>
                                <option value="passport" {{ old('type_piece') == 'passport' ? 'selected' : '' }}>Passeport
                                </option>
                                <option value="autre" {{ old('type_piece') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="numero_piece" class="form-label">N° de Pièce  <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="numero_piece" name="numero_piece" required
                                value="{{ old('numero_piece') }}">
                        </div> --}}
                        {{-- Numéro de pièce --}}
                        <div class="col-md-6 mb-4">
                            <label for="matricule" class="form-label">Matricule<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="matricule" name="matricule" required
                                value="{{ old('matricule') }}">
                        </div>

                        {{-- Lieu d'habitation --}}
                        <div class="col-md-6 mb-4">
                            <label for="adresse" class="form-label">Lieu d'habitation <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="adresse" name="adresse" required
                                value="{{ old('adresse') }}">
                        </div>
                        {{-- Contact --}}
                        <div class="col-md-6 mb-4">
                            <label for="telephone1" class="form-label">Contact <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="telephone1" name="telephone1" maxlength="10"
                                required value="{{ old('telephone1') }}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        {{-- Type d'agent --}}
                        <div class="col-md-6 mb-4">
                            <label for="type_agent" class="form-label">Type d'agent <span
                                    class="text-danger">*</span></label>
                            <select name="type_agent" id="type_agent" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionnez un type d'agent --</option>
                                <option value="recensement" {{ old('type_agent') == 'recensement' ? 'selected' : '' }}>
                                    Recensement (agent de mairie)</option>
                                <option value="recouvrement" {{ old('type_agent') == 'recouvrement' ? 'selected' : '' }}>
                                    Recouvrement</option>
                            </select>
                        </div>

                        {{-- Adresse e-mail --}}
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label">Adresse e-mail <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required
                                value="{{ old('email') }}">
                        </div>
                        <div class="col-12 mt-4 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="fw-bold text-primary text-uppercase small me-3">Urgence & Contact</span>
                                <div class="flex-grow-1 border-top border-primary opacity-25"></div>
                            </div>
                        </div>
                        {{-- Filiation --}}
                        <div class="col-md-6 mb-4">
                            <x-select2-filiation :current-value="old('filiation')" />
                        </div>
                        {{-- Contact --}}
                        <div class="col-md-6 mb-4">
                            <label for="telephone2" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="telephone2" maxlength="10" name="telephone2"
                                value="{{ old('telephone2') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                required>
                        </div>


                    </div>

                    {{-- @dd($mairie) --}}
                    <input type="hidden" name="mairie_ref" value="{{ $mairie->mairie_ref }}">



                    {{-- Boutons --}}
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm">
                            <i class="fa fa-save me-2"></i> Enregistrer l'Agent
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
