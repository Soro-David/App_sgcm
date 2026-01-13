@extends('agent.layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="card shadow-lg p-4" style="width: 900px; border-radius: 20px; border: 1px solid #dee2e6;">
        
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <div>
                <h4 class="mb-1 text-uppercase fw-bold">Carte de Contribuable</h4>
                <p class="mb-0 text-muted">Commune : <strong>{{ $commercant->mairie->nom ?? 'Non défini' }}</strong></p>
            </div>
            <div>
                <small class="text-muted">ID Commerce : {{ $commercant->num_commerce }}</small>
            </div>
        </div>

        <!-- Corps principal en format paysage -->
        <div class="row align-items-center">
            <!-- Colonne 1 : Photo de profil -->
            <div class="col-md-3 text-center">
                <img src="{{ $commercant->photo_profil ? Storage::url($commercant->photo_profil) : asset('images/default_avatar.jpg') }}"
                     alt="Photo de profil"
                     class="rounded-circle shadow-sm"
                     style="width: 160px; height: 160px; object-fit: cover; border: 4px solid #fff;">
                <h5 class="mt-3 fw-bold">{{ $commercant->nom }}</h5>
            </div>

            <!-- Colonne 2 : Infos -->
            <div class="col-md-6">
                <ul class="list-unstyled mb-0 fs-6">
                    <li class="mb-2"><strong>Téléphone :</strong> {{ $commercant->telephone ?? 'Non fourni' }}</li>
                    <li class="mb-2"><strong>Email :</strong> {{ $commercant->email ?? 'Non fourni' }}</li>
                    <li class="mb-2"><strong>Adresse :</strong> {{ $commercant->adresse ?? 'Non fournie' }}</li>
                    <li class="mb-2"><strong>Secteur :</strong> {{ $commercant->secteur->nom ?? 'Non défini' }}</li>
                    <li class="mb-2"><strong>Type de Contribuable :</strong> {{ $commercant->typeContribuable->nom ?? 'Non défini' }}</li>
                </ul>
            </div>

            <!-- Colonne 3 : QR Code -->
            <div class="col-md-3 text-center">
                @if($commercant->qr_code_path)
                    <img src="{{ Storage::url($commercant->qr_code_path) }}" alt="QR Code" class="img-fluid" style="max-width: 180px;">
                    <p class="mt-2 text-muted small">Scannez pour plus d'infos</p>
                @else
                    <div class="alert alert-warning">QR Code indisponible.</div>
                @endif
            </div>
        </div>

        <!-- Pied de carte -->
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('agent.contribuable.index') }}" class="btn btn-outline-secondary btn-sm me-2">Retour à la liste</a>
            <a href="#" onclick="window.print();" class="btn btn-outline-primary btn-sm">Imprimer</a>
        </div>
    </div>
</div>
@endsection
