@extends('commercant.layouts.app')

@section('content')
    {{-- <div class="container d-flex justify-content-center my-5">
        <div class="card p-4 shadow" style="width: 100%; max-width: 900px; border-radius: 20px; border: 1px solid #dee2e6;">

            <!-- En-tête -->
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <div>
                    <h4 class="mb-1 text-uppercase fw-bold">Carte de Contribuable</h4>
                    <p class="mb-0 text-muted">
                        Commune : <strong>{{ $commercant->mairie->nom ?? 'Non défini' }}</strong>
                    </p>
                </div>
                <div>
                    <small class="text-muted">ID Commerce : {{ $commercant->num_commerce }}</small>
                </div>
            </div>

            <!-- Corps principal -->
            <div class="row align-items-center">
                <!-- Photo -->
                <div class="col-md-3 text-center">
                    <img src="{{ $commercant->photo_profil ? Storage::url($commercant->photo_profil) : asset('images/default_avatar.jpg') }}"
                        alt="Photo de profil" class="rounded-circle shadow-sm"
                        style="width: 160px; height: 160px; object-fit: cover; border: 4px solid #fff;">
                    <h5 class="mt-3 fw-bold">{{ $commercant->nom }}</h5>
                </div>

                <!-- Infos -->
                <div class="col-md-6">
                    <ul class="list-unstyled mb-0 fs-6">
                        <li class="mb-2"><strong>Téléphone :</strong> {{ $commercant->telephone ?? 'Non fourni' }}</li>
                        <li class="mb-2"><strong>Email :</strong> {{ $commercant->email ?? 'Non fourni' }}</li>
                        <li class="mb-2"><strong>Adresse :</strong> {{ $commercant->adresse ?? 'Non fournie' }}</li>
                        <li class="mb-2"><strong>Secteur :</strong> {{ $commercant->secteur->nom ?? 'Non défini' }}</li>
                        <li class="mb-2"><strong>Type de Contribuable :</strong>
                            {{ $commercant->typeContribuable->nom ?? 'Non défini' }}</li>
                    </ul>
                </div>

                <!-- QR Code -->
                <div class="col-md-3 text-center">
                    @if ($commercant->qr_code_path)
                        <img src="{{ Storage::url($commercant->qr_code_path) }}" alt="QR Code" class="img-fluid"
                            style="max-width: 180px;">
                        <p class="mt-2 text-muted small">Scannez pour plus d'infos</p>
                    @else
                        <div class="alert alert-warning">QR Code indisponible.</div>
                    @endif
                </div>
            </div>

            <!-- Pied -->
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('commercant.dashboard') }}" class="btn btn-outline-secondary btn-sm me-2">Retour au
                    tableau de bord</a>
                <a href="#" onclick="window.print();" class="btn btn-outline-primary btn-sm">Imprimer</a>
            </div>
        </div>
    </div> --}}

    <!-- Formulaire Paiement -->
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h3>Effectuer un paiement</h3>
                <hr><br>
                <div id="payment-message" class="mt-3"></div>

                <form id="payment-form" method="POST" action="{{ route('commercant.payement.effectuer') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="taxe_id" class="form-label">Sélectionnez une taxe</label>
                            <select name="taxe_id" id="taxe_id" class="form-select" required
                                data-periodes-url="{{ route('commercant.payement.periodes_impayees', ['taxeId' => 'PLACEHOLDER']) }}">
                                <option value="" disabled selected>-- Sélectionnez une taxe --</option>
                                @foreach ($taxes as $taxe)
                                    <option value="{{ $taxe->id }}">{{ $taxe->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <h5>Détail des périodes à payer :</h5>
                            <div id="periodes-impayees" class="text-muted">(Veuillez sélectionner une taxe)</div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="nombre_periodes">Nombre de périodes à payer</label>
                            <input type="number" min="1" id="nombre_periodes" name="nombre_periodes"
                                class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="montant">Montant Total à Payer (CFA)</label>
                            <input type="text" id="montant" name="montant" class="form-control" readonly required>
                        </div>
                        <div class="col-md-4 align-self-end">
                            <button type="submit" id="submit-button" class="btn btn-primary w-100">Payer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        window.payementConfig = {
            historiqueUrl: "{{ route('commercant.payement.historique') }}",
        };
    </script>
    <script src="{{ asset('assets/js/commercant_payement.js') }}"></script>
@endpush
