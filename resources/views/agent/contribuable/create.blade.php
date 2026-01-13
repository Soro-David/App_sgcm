@extends('agent.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container mt-4">
                <form id="addCommerceForm" method="POST" action="{{ route('agent.contribuable.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header justify-content-center">
                        <h3 class="modal-title">Ajoute d'un contribuable</h3>
                    </div>

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
                        {{-- Section des champs --}}
                        <div class="row g-3 mt-2">
                            <div class="col-md-7"><label for="nom" class="form-label">Nom & Prénm</label><input
                                    type="text" class="form-control" id="nom" name="nom" required
                                    value="{{ old('nom') }}"></div>
                            <div class="col-md-5"><label for="num_commerce" class="form-label">Numéro Commerce</label><input
                                    type="text" class="form-control" id="num_commerce" name="num_commerce"
                                    value="{{ $num_commerce }}" readonly></div>
                            <div class="col-md-6"><label for="email" class="form-label">Adresse e-mail</label><input
                                    type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}"></div>
                            <div class="col-md-6"><label for="telephone" class="form-label">Téléphone</label><input
                                    type="text" class="form-control" id="telephone" name="telephone"
                                    value="{{ old('telephone') }}"></div>
                            <div class="col-md-6"><label for="adresse" class="form-label">Adresse</label><input
                                    type="text" class="form-control" id="adresse" name="adresse"
                                    value="{{ old('adresse') }}"></div>
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
                                            <option value="{{ $type_contribuable->id }}">{{ $type_contribuable->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#addTypeModal">
                                    +
                                </button>
                            </div>

                            <div class="col-md-6"><label for="taxe_ids" class="form-label">Taxe(s)
                                    applicable(s)</label><select name="taxe_ids[]" id="taxe_ids"
                                    class="form-select select2 w-100" multiple required>
                                    @foreach ($taxes as $taxe)
                                        <option value="{{ $taxe->id }}">{{ $taxe->nom }}</option>
                                    @endforeach
                                </select></div>
                            <div class="col-md-6"><label for="type_piece" class="form-label">Type de pièce</label><select
                                    name="type_piece" id="type_piece" class="form-select" required>
                                    <option value="" disabled selected>-- Sélectionnez --</option>
                                    <option value="cni">CNI</option>
                                    <option value="attestation">Attestation</option>
                                    <option value="passeport">Passeport</option>
                                    <option value="consulaire">Consulaire</option>
                                    <option value="autre">Autre</option>
                                </select></div>
                            <div class="col-md-6"><label for="numero_piece" class="form-label">N° Pièce</label><input
                                    type="text" class="form-control" id="numero_piece" name="numero_piece"
                                    value="{{ old('numero_piece') }}"></div>
                            <div class="col-md-6 d-none" id="autre_type_piece_container"><label for="autre_type_piece"
                                    class="form-label">Précisez le type de pièce</label><input type="text"
                                    class="form-control" id="autre_type_piece" name="autre_type_piece"
                                    placeholder="Type de pièce">
                            </div>
                            <div class="row g-3 mt-4">
                                <!-- Photo de Profil -->
                                <div class="col-md-4 text-center position-relative">
                                    <label class="form-label">Photo de profil :</label>
                                    <img id="preview_profil" src="{{ asset('images/default_avatar.jpg') }}"
                                        alt="Profil" class="img-thumbnail"
                                        style="height:120px; width:120px; object-fit: cover;">
                                    <div class="position-absolute top-50 end-0 translate-middle-y me-2">
                                        <button type="button" id="upload_profil" class="btn btn-light btn-sm me-1"><i
                                                class="fa fa-upload"></i></button>
                                        <button type="button" id="camera_profil" class="btn btn-light btn-sm"><i
                                                class="fa fa-camera"></i></button>
                                    </div>
                                    <input type="file" accept="image/*" class="d-none" id="photo_profil"
                                        name="photo_profil">
                                </div>
                                <!-- Photo Pièce Recto -->
                                <div class="col-md-4 text-center position-relative">
                                    <label class="form-label">Photo pièce Recto :</label>
                                    <img id="preview_recto" src="{{ asset('images/idrecto.jpg') }}" alt="Pièce Recto"
                                        class="img-thumbnail" style="height:120px; width:120px; object-fit: cover;">
                                    <div class="position-absolute top-50 end-0 translate-middle-y me-2">
                                        <button type="button" id="upload_recto" class="btn btn-light btn-sm me-1"><i
                                                class="fa fa-upload"></i></button>
                                        <button type="button" id="camera_recto" class="btn btn-light btn-sm"><i
                                                class="fa fa-camera"></i></button>
                                    </div>
                                    <input type="file" accept="image/*" class="d-none" id="photo_recto"
                                        name="photo_recto">
                                </div>
                                <!-- Photo Pièce Verso -->
                                <div class="col-md-4 text-center position-relative">
                                    <label class="form-label">Photo pièce Verso :</label>
                                    <img id="preview_verso" src="{{ asset('images/default_piece_verso.jpg') }}"
                                        alt="Pièce Verso" class="img-thumbnail"
                                        style="height:120px; width:120px; object-fit: cover;">
                                    <div class="position-absolute top-50 end-0 translate-middle-y me-2">
                                        <button type="button" id="upload_verso" class="btn btn-light btn-sm me-1"><i
                                                class="fa fa-upload"></i></button>
                                        <button type="button" id="camera_verso" class="btn btn-light btn-sm"><i
                                                class="fa fa-camera"></i></button>
                                    </div>
                                    <input type="file" accept="image/*" class="d-none" id="photo_verso"
                                        name="photo_verso">
                                </div>
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
