@extends('commercant.layouts.app')

@section('title', 'Tableau de Bord')

@section('content')

    <div class="row mb-4">
        <!-- Carte Solde Compte -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card bg-success text-white shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title text-white mb-0">Mon Solde</h4>
                        <i class="typcn typcn-briefcase icon-lg opacity-50"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0 fw-bold">{{ number_format($soldeCompte ?? 0, 0, ',', ' ') }} F</h2>
                    </div>
                    <p class="mt-2 mb-0 text-white-50 small">Solde actuel disponible</p>
                </div>
            </div>
        </div>

        <!-- Carte Cumul Taxes -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card bg-warning text-white shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title text-white mb-0">Cumul Taxes</h4>
                        <i class="typcn typcn-chart-bar icon-lg opacity-50"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0 fw-bold">{{ number_format($montantTotalTaxes ?? 0, 0, ',', ' ') }} F</h2>
                    </div>
                    <p class="mt-2 mb-0 text-white-50 small">Total des taxes assignées</p>
                </div>
            </div>
        </div>

        <!-- Carte Nombre de Taxes -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card bg-info text-white shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title text-white mb-0">Mes Taxes</h4>
                        <i class="typcn typcn-document-text icon-lg opacity-50"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0 fw-bold">{{ $nombreTaxes ?? 0 }}</h2>
                    </div>
                    <p class="mt-2 mb-0 text-white-50 small">Nombre total de taxes actives</p>
                </div>
            </div>
        </div>

        <!-- Carte Raccourci -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card bg-dark text-white shadow-sm border-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h4 class="card-title text-white mb-0">Ma Carte</h4>
                            <i class="typcn typcn-card icon-lg opacity-50"></i>
                        </div>
                        <p class="text-white-50 small mb-0">Accéder à ma carte.</p>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('commercant.virtual_card') }}"
                            class="btn btn-light btn-block fw-bold text-dark btn-sm">
                            <i class="typcn typcn-eye mr-2"></i> Voir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Derniers Paiements -->
        <div class="col-xl-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-4 text-titlecase">Derniers Paiements ({{ date('Y') }})</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Taxe</th>
                                    <th>Période</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($derniersPaiements as $paiement)
                                    <tr>
                                        <td>{{ $paiement->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $paiement->taxe->nom ?? 'Taxe' }}</td>
                                        <td>{{ $paiement->periode ? $paiement->periode->format('M Y') : '-' }}</td>
                                        <td><span class="fw-bold">{{ number_format($paiement->montant, 0, ',', ' ') }}
                                                F</span></td>
                                        <td>
                                            <label
                                                class="badge badge-{{ $paiement->statut == 'payé' ? 'success' : 'warning' }}">
                                                {{ ucfirst($paiement->statut) }}
                                            </label>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Aucun paiement effectué cette
                                            année.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- @if ($derniersPaiements->count() > 0)
                        <div class="text-end mt-3">
                            <a href="{{ route('commercant.payement.historique') }}" class="btn btn-link btn-sm">Voir tout
                                l'historique</a>
                        </div>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>

@endsection


@push('js')
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script>
        function printCard() {
            window.print();
        }
    </script>
@endpush
