@extends('mairie.layouts.app')

@section('title', 'Tableau de Bord - Caissier')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/premium_dashboard.css') }}">
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-dark mb-1">Espace Caisse</h2>
                <p class="text-muted">Gérez vos encaissements et suivez votre activité journalière.</p>
            </div>
            <div class="text-end">
                <div id="real-time-clock" class="fw-bold text-primary fs-5 mb-1"></div>
                <div class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Filtre : {{ $stats['currentFilter'] === 'tout' ? 'Tout le temps' : ucfirst($stats['currentFilter']) }}
                </div>
            </div>
        </div>

        <!-- FILTRES -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="premium-card p-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="icon-box bg-light shadow-none" style="width: 40px; height: 40px;">
                            <i class="fas fa-filter text-muted fs-6"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Filtrer par période</h5>
                    </div>
                    <div class="btn-group bg-light p-1 rounded-pill shadow-sm">
                        <a href="{{ route('mairie.dashboard.index', ['filter' => 'jour']) }}"
                            class="btn btn-sm {{ $stats['currentFilter'] === 'jour' ? 'btn-primary rounded-pill px-4' : 'btn-light border-0 rounded-pill px-3 text-muted' }}">Jour</a>
                        <a href="{{ route('mairie.dashboard.index', ['filter' => 'mois']) }}"
                            class="btn btn-sm {{ $stats['currentFilter'] === 'mois' ? 'btn-primary rounded-pill px-4' : 'btn-light border-0 rounded-pill px-3 text-muted' }}">Mois</a>
                        <a href="{{ route('mairie.dashboard.index', ['filter' => 'annee']) }}"
                            class="btn btn-sm {{ $stats['currentFilter'] === 'annee' ? 'btn-primary rounded-pill px-4' : 'btn-light border-0 rounded-pill px-3 text-muted' }}">Année</a>
                        <a href="{{ route('mairie.dashboard.index', ['filter' => 'tout']) }}"
                            class="btn btn-sm {{ $stats['currentFilter'] === 'tout' ? 'btn-primary rounded-pill px-4' : 'btn-light border-0 rounded-pill px-3 text-muted' }}">Tout</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="premium-card p-4">
                    <div class="icon-box bg-primary-soft">
                        <i class="fas fa-hand-holding-dollar"></i>
                    </div>
                    <div class="stat-label">
                        Mes Encaissements
                        <small class="text-muted d-block">
                            @if ($stats['currentFilter'] === 'jour')
                                (Aujourd'hui)
                            @elseif($stats['currentFilter'] === 'mois')
                                (Ce mois)
                            @elseif($stats['currentFilter'] === 'annee')
                                (Cette année)
                            @else
                                (Tout)
                            @endif
                        </small>
                    </div>
                    <div class="stat-value">{{ number_format($stats['montantPaye'], 0, ',', ' ') }} <small
                            class="fs-6">FCFA</small></div>
                    <div class="mt-2 text-muted small">Total cumulé pour la mairie</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="premium-card p-4">
                    <div class="icon-box bg-secondary-soft">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <div class="stat-label">Action Rapide</div>
                    <a href="{{ route('mairie.caisse.index') }}" class="btn btn-primary w-100 mt-3 rounded-pill">
                        <i class="fas fa-plus me-2"></i> Nouvel Encaissement
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="premium-card p-4">
                    <div class="icon-box bg-info-soft">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="stat-label">Mon Activité</div>
                    <a href="{{ route('mairie.caisse.mes_encaissements') }}"
                        class="btn btn-light w-100 mt-3 rounded-pill border">
                        <i class="fas fa-list me-2"></i> Voir Historique
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="premium-card p-4">
                    <h5 class="fw-bold mb-4">
                        @if ($stats['currentFilter'] === 'jour')
                            Mes Encaissements d'Aujourd'hui
                        @elseif($stats['currentFilter'] === 'mois')
                            Mes Encaissements du Mois
                        @elseif($stats['currentFilter'] === 'annee')
                            Mes Encaissements de l'Année
                        @else
                            Mes Derniers Encaissements
                        @endif
                    </h5>
                    @if (isset($stats['mesEncaissements']) && count($stats['mesEncaissements']) > 0)
                        <div class="table-responsive">
                            <table class="innovative-table border-top">
                                <thead>
                                    <tr>
                                        <th>Contribuable</th>
                                        <th>Date / Heure</th>
                                        <th>Montant</th>
                                        <th>Mode de Paiement</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stats['mesEncaissements'] as $encaissement)
                                        <tr>
                                            <td class="fw-bold">{{ $encaissement->num_commerce }}</td>
                                            <td class="text-muted">{{ $encaissement->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="fw-bold text-success">
                                                {{ number_format($encaissement->montant_percu, 0, ',', ' ') }} FCFA</td>
                                            <td><span class="badge bg-light text-dark border">Espèces</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-light border rounded-circle"
                                                    title="Imprimer Reçu">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-3x text-light mb-3"></i>
                            <p class="text-muted">Aucun encaissement récent trouvé.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
