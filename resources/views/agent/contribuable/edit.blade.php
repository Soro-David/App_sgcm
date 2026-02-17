@extends('agent.layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container mt-4">
                <h1 class="h3 mb-2">Modifier un commerçant</h1>

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

                <form action="{{ route('agent.contribuable.commerce_update', $commercant->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label>Nom</label>
                            <input type="text" name="nom" value="{{ old('nom', $commercant->nom) }}"
                                class="form-control" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $commercant->email) }}"
                                class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label>Téléphone</label>
                            <input type="text" name="telephone" value="{{ old('telephone', $commercant->telephone) }}"
                                class="form-control" maxlength="10" minlength="10"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label>Adresse</label>
                            <input type="text" name="adresse" value="{{ old('adresse', $commercant->adresse) }}"
                                class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label>Secteur</label>
                            <select name="secteur_id" class="form-select" required>
                                @foreach ($secteurs as $secteur)
                                    <option value="{{ $secteur->id }}"
                                        {{ $commercant->secteur_id == $secteur->id ? 'selected' : '' }}>
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

                    <div class="row g-3 mt-4">
                        <!-- Photo de Profil -->
                        <div class="col-md-4 text-center position-relative">
                            <label class="form-label">Photo de profil :</label>
                            <img id="preview_profil"
                                src="{{ $commercant->photo_profil ? asset('storage/' . $commercant->photo_profil) : asset('images/default_avatar.jpg') }}"
                                alt="Profil" class="img-thumbnail" style="height:120px; width:120px; object-fit: cover;">
                            <div class="position-absolute top-50 end-0 translate-middle-y me-2">
                                <button type="button" id="upload_profil" class="btn btn-light btn-sm me-1"><i
                                        class="fa fa-upload"></i></button>
                                <button type="button" id="camera_profil" class="btn btn-light btn-sm"><i
                                        class="fa fa-camera"></i></button>
                            </div>
                            <input type="file" accept="image/*" class="d-none" id="photo_profil" name="photo_profil">
                        </div>

                        <!-- Photo Pièce Recto -->
                        <div class="col-md-4 text-center position-relative">
                            <label class="form-label">Photo pièce Recto :</label>
                            <img id="preview_recto"
                                src="{{ $commercant->photo_recto ? asset('storage/' . $commercant->photo_recto) : asset('images/idrecto.jpg') }}"
                                alt="Pièce Recto" class="img-thumbnail"
                                style="height:120px; width:120px; object-fit: cover;">
                            <div class="position-absolute top-50 end-0 translate-middle-y me-2">
                                <button type="button" id="upload_recto" class="btn btn-light btn-sm me-1"><i
                                        class="fa fa-upload"></i></button>
                                <button type="button" id="camera_recto" class="btn btn-light btn-sm"><i
                                        class="fa fa-camera"></i></button>
                            </div>
                            <input type="file" accept="image/*" class="d-none" id="photo_recto" name="photo_recto">
                        </div>

                        <!-- Photo Pièce Verso -->
                        <div class="col-md-4 text-center position-relative">
                            <label class="form-label">Photo pièce Verso :</label>
                            <img id="preview_verso"
                                src="{{ $commercant->photo_verso ? asset('storage/' . $commercant->photo_verso) : asset('images/default_piece_verso.jpg') }}"
                                alt="Pièce Verso" class="img-thumbnail"
                                style="height:120px; width:120px; object-fit: cover;">
                            <div class="position-absolute top-50 end-0 translate-middle-y me-2">
                                <button type="button" id="upload_verso" class="btn btn-light btn-sm me-1"><i
                                        class="fa fa-upload"></i></button>
                                <button type="button" id="camera_verso" class="btn btn-light btn-sm"><i
                                        class="fa fa-camera"></i></button>
                            </div>
                            <input type="file" accept="image/*" class="d-none" id="photo_verso" name="photo_verso">
                        </div>
                    </div>


                    <button class="btn btn-primary" type="submit">Mettre à jour</button>
                    <a href="{{ route('agent.contribuable.index') }}" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('assets/js/agent_commerce_create.js') }}"></script>
@endpush
