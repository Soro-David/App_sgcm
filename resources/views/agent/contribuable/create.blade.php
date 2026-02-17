@extends('agent.layouts.app')

@section('content')
    <div class="card">
        <div class="modal-header justify-content-center">
            <h2 class="modal-title">Ajout d'un contribuable</h2>
        </div>
        <div class="card-body">
            <div class="container mt-4">
                <form id="addCommerceForm" method="POST" action="{{ route('agent.contribuable.store') }}"
                    enctype="multipart/form-data">
                    @csrf


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

                    <div class="modal-body">
                        {{-- Section des photos en haut --}}
                        <div class="row g-3 mb-4">
                            <!-- Photo de Profil - Centrée en haut -->
                            <div class="col-12 d-flex justify-content-center">
                                <div class="photo-profil-container text-center">
                                    <div class="photo-profil-circle position-relative">
                                        <img id="preview_profil" src="{{ asset('images/default_avatar.jpg') }}"
                                            alt="Profil" class="img-profil">
                                        <button type="button" id="upload_profil" class="btn-camera-profil">
                                            <i class="fa fa-camera"></i>
                                        </button>
                                    </div>
                                    <label class="form-label mt-2 photo-label">Photo de profil</label>
                                    <input type="file" accept="image/*" class="d-none" id="photo_profil"
                                        name="photo_profil">
                                </div>
                            </div>

                            <!-- Photos des pièces - Côte à côte -->
                            <div class="col-md-6">
                                <div class="photo-piece-container">
                                    <div class="photo-piece-box">
                                        <img id="preview_recto" src="{{ asset('images/idrecto.jpg') }}" alt="Pièce Recto"
                                            class="img-piece">
                                        <div class="photo-piece-overlay">
                                            <button type="button" id="upload_recto" class="btn-upload-piece">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <label class="form-label mt-2 text-center photo-label-piece">Photo de la pièce
                                        Recto</label>
                                    <input type="file" accept="image/*" class="d-none" id="photo_recto"
                                        name="photo_recto">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="photo-piece-container">
                                    <div class="photo-piece-box">
                                        <img id="preview_verso" src="{{ asset('images/default_piece_verso.jpg') }}"
                                            alt="Pièce Verso" class="img-piece">
                                        <div class="photo-piece-overlay">
                                            <button type="button" id="upload_verso" class="btn-upload-piece">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <label class="form-label mt-2 text-center photo-label-piece">Photo de la pièce
                                        Verso</label>
                                    <input type="file" accept="image/*" class="d-none" id="photo_verso"
                                        name="photo_verso">
                                </div>
                            </div>
                        </div>

                        {{-- Section du formulaire en deux colonnes --}}
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom & Prénom</label>
                                <input type="text" class="form-control" id="nom" name="nom" required
                                    value="{{ old('nom') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="num_commerce" class="form-label">Numéro de commerce</label>
                                <input type="text" class="form-control" id="num_commerce" name="num_commerce"
                                    value="{{ $num_commerce }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Adresse E-mail</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label">Numéro de Téléphone</label>
                                <input type="text" class="form-control" id="telephone" maxlength="10" minlength="10"
                                    name="telephone" value="{{ old('telephone') }}"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                            </div>
                            <div class="col-md-6">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse"
                                    value="{{ old('adresse') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="secteur_id" class="form-label">Secteur</label>
                                <select name="secteur_id" id="secteur_id" class="form-select" required>
                                    <option value="" disabled selected>-- Sélectionnez un secteur --</option>
                                    @foreach ($nomsSecteurs as $secteur)
                                        <option value="{{ $secteur['id'] }}">{{ $secteur['nom'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="flex-grow-1 me-2">
                                    <label for="type_contribuable" class="form-label">Type Contribuable</label>
                                    <select name="type_contribuable_id" id="type_contribuable"
                                        class="form-select select2-com w-100" required>
                                        <option value="" disabled selected>-- Sélectionnez un type --</option>
                                        @foreach ($type_contribuables as $type_contribuable)
                                            <option value="{{ $type_contribuable->id }}">
                                                {{ $type_contribuable->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#addTypeModal">
                                    +
                                </button>
                            </div>
                            <div class="col-md-6">
                                <label for="taxe_ids" class="form-label">Taxe(s) applicable(s)</label>
                                <select name="taxe_ids[]" id="taxe_ids" class="form-select select2 w-100" multiple
                                    required>
                                    @foreach ($taxes as $taxe)
                                        <option value="{{ $taxe->id }}">{{ $taxe->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="type_piece" class="form-label">Type de pièce</label>
                                <select name="type_piece" id="type_piece" class="form-select" required>
                                    <option value="" disabled selected>-- Sélectionnez --</option>
                                    <option value="cni">CNI</option>
                                    <option value="attestation">Attestation</option>
                                    <option value="passeport">Passeport</option>
                                    <option value="consulaire">Consulaire</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="numero_piece" class="form-label">N° Pièce</label>
                                <input type="text" class="form-control" id="numero_piece" name="numero_piece"
                                    value="{{ old('numero_piece') }}">
                            </div>
                            <div class="col-md-6 d-none" id="autre_type_piece_container">
                                <label for="autre_type_piece" class="form-label">Précisez le type de pièce</label>
                                <input type="text" class="form-control" id="autre_type_piece" name="autre_type_piece"
                                    placeholder="Type de pièce">
                            </div>
                        </div><br>

                        <input type="hidden" name="agent_id" value="{{ optional($agent)->id }}">
                        <input type="hidden" name="mairie_ref" value="{{ optional($agent)->mairie_ref }}">

                        <div class="modal-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    @include('agent.contribuable.partials.add_contribuable')
@endsection



@push('js')
    <script src="{{ asset('assets/js/agent_commerce_create.js') }}"></script>
@endpush
