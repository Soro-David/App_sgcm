@extends('agent.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Détails du Contribuable</h1>
        <a href="{{ route('agent.encaissement.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h5 class="card-title mb-0">Informations Générales</h5></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6"><p><strong>Nom :</strong> {{ $commercant->nom }}</p></div>
                <div class="col-md-6"><p><strong>N° Commerce :</strong> {{ $commercant->num_commerce }}</p></div>
                <div class="col-md-6"><p><strong>Téléphone :</strong> {{ $commercant->telephone }}</p></div>
                <div class="col-md-6"><p><strong>Secteur :</strong> {{ $commercant->secteur->nom ?? 'N/A' }}</p></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="card-title mb-0">Historique des Paiements</h5></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mois Payé</th>
                            <th>Taxe</th>
                            <th>Montant</th>
                            <th>Date d'Encaissement</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paiements as $paiement)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($paiement->periode)->isoFormat('MMMM YYYY') }}</td>
                                <td>{{ $paiement->taxe->nom ?? 'N/A' }}</td>
                                <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                                <td>{{ $paiement->created_at->format('d/m/Y à H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">Aucun paiement enregistré.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection