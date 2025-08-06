@extends('agent.layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="card shadow-lg" style="width: 380px; border-radius: 20px; border: 1px solid #dee2e6;">
        
        <div class="card-header bg-dark text-white text-center py-3" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
            <h4 class="mb-0">CARTE DE CONTRIBUABLE</h4>
        </div>

        @if(session('success'))
            <div class="alert alert-success m-3" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card-body text-center p-4">
            <!-- Photo de profil -->
            <img src="{{ $commercant->photo_profil ? Storage::url($commercant->photo_profil) : asset('images/default_avatar.jpg') }}"
                 alt="Photo de profil de {{ $commercant->nom }}"
                 class="img-fluid rounded-circle mb-3"
                 style="width: 140px; height: 140px; object-fit: cover; border: 5px solid #fff; box-shadow: 0 0 15px rgba(0,0,0,0.2);">

            <!-- Informations du commerçant -->
            <h5 class="card-title fw-bold fs-4">{{ $commercant->nom }}</h5>
            <p class="card-text text-muted mb-1">ID: {{ $commercant->num_commerce }}</p>
            
            <ul class="list-unstyled text-start mt-4">
                <li class="d-flex align-items-center mb-2"><i class="fa fa-phone fa-fw me-3 text-secondary"></i><span>{{ $commercant->telephone ?? 'Non fourni' }}</span></li>
                <li class="d-flex align-items-center mb-2"><i class="fa fa-envelope fa-fw me-3 text-secondary"></i><span>{{ $commercant->email ?? 'Non fourni' }}</span></li>
                <li class="d-flex align-items-center"><i class="fa fa-map-marker-alt fa-fw me-3 text-secondary"></i><span>{{ $commercant->adresse ?? 'Non fournie' }}</span></li>
            </ul>
            
            <hr class="my-4">

            <!-- QR Code -->
            <div class="mt-3">
                <p class="text-muted small">Scannez pour plus d'informations</p>
                @if($commercant->qr_code_path)
                    {{-- La fonction Storage::url() génère l'URL publique correcte --}}
                    <img src="{{ Storage::url($commercant->qr_code_path) }}" alt="QR Code du commerçant" class="img-fluid" style="max-width: 200px;">
                @else
                    <div class="alert alert-warning">Le QR Code n'est pas disponible.</div>
                @endif
            </div>
        </div>

        <div class="card-footer text-center bg-light" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
            <a href="{{ route('agent.commerce.index') }}" class="btn btn-outline-secondary btn-sm">Retour à la liste</a>
            <a href="#" onclick="window.print();" class="btn btn-outline-primary btn-sm">Imprimer</a>
        </div>
    </div>
</div>
@endsection