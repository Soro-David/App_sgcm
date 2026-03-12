@extends($layout)

@section('title', 'Mon Profil')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="font-weight-bold text-dark mb-0">Mon Profil</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            @php
                                $dashboardRoute = match ($guard) {
                                    'agent' => route('agent.dashboard'),
                                    'commercant' => route('commercant.dashboard'),
                                    'web' => route('superadmin.dashboard'),
                                    default => route('mairie.dashboard.index'),
                                };
                            @endphp
                            <li class="breadcrumb-item"><a href="{{ $dashboardRoute }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profil</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Sidebar Profil -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                    <div class="card-body text-center p-5">
                        <div class="position-relative d-inline-block mb-4">
                            <img src="{{ $user->photo_profil ? asset('storage/' . $user->photo_profil) : asset('images/default_avatar.jpg') }}"
                                alt="Avatar" class="rounded-circle shadow-sm"
                                style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #fff;">
                            <span
                                class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle p-2"
                                title="En ligne"></span>
                        </div>

                        <h4 class="font-weight-bold text-dark">{{ $user->name ?? $user->nom }}</h4>
                        <p class="text-muted mb-3">{{ strtoupper($guard) }}</p>
                        <div class="badge bg-primary-light text-primary px-3 py-2 rounded-pill mb-4">
                            <i class="fas fa-user-shield mr-1"></i> {{ strtoupper($user->role ?? $guard) }}
                        </div>

                        @if ($user instanceof \App\Models\Mairie && $user->logo)
                            <div class="mt-3">
                                <small class="text-muted d-block mb-2 text-uppercase font-weight-bold">Logo de la
                                    Mairie</small>
                                <img src="{{ asset('storage/' . $user->logo) }}" alt="Logo Mairie"
                                    class="img-fluid rounded border p-2 bg-white" style="max-height: 80px;">
                            </div>
                        @endif

                        <hr class="my-4 opacity-10">

                        <div class="text-start">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-light text-primary rounded-circle p-2 mr-3">
                                    <i class="fas fa-envelope fa-sm"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">E-mail</small>
                                    <span class="text-dark font-weight-medium">{{ $user->email }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-light text-success rounded-circle p-2 mr-3">
                                    <i class="fas fa-phone fa-sm"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Téléphone</small>
                                    <span
                                        class="text-dark font-weight-medium">{{ $user->telephone1 ?? ($user->telephone ?? 'N/A') }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-light text-info rounded-circle p-2 mr-3">
                                    <i class="fas fa-map-marker-alt fa-sm"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Adresse</small>
                                    <span class="text-dark font-weight-medium">{{ $user->adresse ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de Modification -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="card-title font-weight-bold text-dark mb-4">Informations personnelles</h5>

                        @php
                            $updateRoute = match ($guard) {
                                'agent' => route('agent.profile.update'),
                                'commercant' => route('commercant.profile.update'),
                                'web' => route('superadmin.profile.update'),
                                default => route('mairie.profile.update'),
                            };
                        @endphp
                        <form action="{{ $updateRoute }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label text-muted small text-uppercase font-weight-bold">Nom
                                        complet</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-user text-muted"></i></span>
                                        <input type="text" name="{{ isset($user->nom) ? 'nom' : 'name' }}"
                                            class="form-control bg-light border-0 @error($user instanceof \App\Models\Commercant ? 'nom' : 'name') is-invalid @enderror"
                                            value="{{ $user->name ?? $user->nom }}" required>
                                    </div>
                                    @error($user instanceof \App\Models\Commercant ? 'nom' : 'name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label
                                        class="form-label text-muted small text-uppercase font-weight-bold">E-mail</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-envelope text-muted"></i></span>
                                        <input type="email" name="email"
                                            class="form-control bg-light border-0 @error('email') is-invalid @enderror"
                                            value="{{ $user->email }}" required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if (isset($user->matricule) || isset($user->filiation))
                                <div class="row mb-4">
                                    @if (isset($user->matricule))
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <label
                                                class="form-label text-muted small text-uppercase font-weight-bold">Matricule</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-0"><i
                                                        class="fas fa-id-card-alt text-muted"></i></span>
                                                <input type="text" name="matricule"
                                                    class="form-control bg-light border-0" value="{{ $user->matricule }}">
                                            </div>
                                        </div>
                                    @endif
                                    @if (isset($user->filiation))
                                        <div class="col-md-6">
                                            <x-select2-filiation field-id="filiation_profil" field-name="filiation"
                                                :required="false" :current-value="$user->filiation"
                                                label-class="form-label text-muted small text-uppercase font-weight-bold" />
                                            <small class="text-muted"
                                                style="font-size:0.78em; margin-top: -10px; display: block;">
                                                <i class="fas fa-info-circle"></i>
                                                Tapez pour créer une nouvelle filiation.
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label
                                        class="form-label text-muted small text-uppercase font-weight-bold">Téléphone</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-phone text-muted"></i></span>
                                        <input type="text" name="telephone" class="form-control bg-light border-0"
                                            value="{{ $user->telephone1 ?? ($user->telephone ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label
                                        class="form-label text-muted small text-uppercase font-weight-bold">Adresse</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-map-marker-alt text-muted"></i></span>
                                        <input type="text" name="adresse" class="form-control bg-light border-0"
                                            value="{{ $user->adresse }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted small text-uppercase font-weight-bold">Photo de
                                    profil</label>
                                <input type="file" name="photo_profil" class="form-control mb-1">
                                <small class="text-muted d-block mb-3">Laissez vide pour conserver la photo actuelle
                                    (Format: JPG, PNG, Max: 2Mo)</small>
                            </div>

                            @if ($user instanceof \App\Models\Mairie)
                                <div class="mb-4">
                                    <label class="form-label text-muted small text-uppercase font-weight-bold">Logo de la
                                        Mairie</label>
                                    <input type="file" name="logo" class="form-control mb-1">
                                    <small class="text-muted d-block">Laissez vide pour conserver le logo actuel (Format:
                                        JPG, PNG, SVG, Max: 2Mo)</small>
                                </div>
                            @endif

                            <h5 class="card-title font-weight-bold text-dark mt-5 mb-4 border-top pt-4">Sécurité
                                (Changement de mot de passe)</h5>

                            <div class="row mb-4">
                                <div class="col-12 mb-3">
                                    <label class="form-label text-muted small text-uppercase font-weight-bold">Mot de passe
                                        actuel</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-lock text-muted"></i></span>
                                        <input type="password" name="current_password"
                                            class="form-control bg-light border-0 @error('current_password') is-invalid @enderror">
                                    </div>
                                    @error('current_password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label text-muted small text-uppercase font-weight-bold">Nouveau mot
                                        de passe</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-key text-muted"></i></span>
                                        <input type="password" name="new_password"
                                            class="form-control bg-light border-0 @error('new_password') is-invalid @enderror">
                                    </div>
                                    @error('new_password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small text-uppercase font-weight-bold">Confirmer le
                                        nouveau mot de passe</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-key text-muted"></i></span>
                                        <input type="password" name="new_password_confirmation"
                                            class="form-control bg-light border-0">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-5">
                                <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm"
                                    style="border-radius: 8px;">
                                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-primary-light {
            background-color: rgba(75, 73, 172, 0.1);
        }

        .icon-box {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: "\f105";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #ccc;
        }

        .form-control:focus {
            background-color: #fff !important;
            box-shadow: 0 0 0 0.25rem rgba(75, 73, 172, 0.1);
            border-color: rgba(75, 73, 172, 0.5) !important;
        }

        .input-group-text {
            border-radius: 8px 0 0 8px;
        }

        .form-control {
            border-radius: 0 8px 8px 0;
        }

        .btn-primary {
            background-color: #4B49AC;
            border-color: #4B49AC;
        }

        .btn-primary:hover {
            background-color: #3f3e8e;
            border-color: #3f3e8e;
        }

    @endsection
