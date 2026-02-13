@extends('agent.layouts.app')

@section('title', 'Tableau de Bord - Agent')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/premium_dashboard.css') }}">
@endpush

@section('content')
    {{-- Initialisation des variables pour alléger le code --}}
    @php
        $agent = Auth::guard('agent')->user();
        $isRecouvrement = $agent->type === 'recouvrement';
        $isRecensement = $agent->type === 'recensement';
    @endphp

    <div class="container-fluid py-2">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Espace Agent Terrain</h2>
                <p class="text-muted">
                    Tableau de bord de vos activités de {{ $agent->type }}.
                </p>
            </div>
            <div class="text-end">
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
                        <a href="{{ route('agent.dashboard', ['filter' => 'jour']) }}"
                            class="btn btn-sm {{ $stats['currentFilter'] === 'jour' ? 'btn-primary rounded-pill px-4' : 'btn-light border-0 rounded-pill px-3 text-muted' }}">Jour</a>
                        <a href="{{ route('agent.dashboard', ['filter' => 'mois']) }}"
                            class="btn btn-sm {{ $stats['currentFilter'] === 'mois' ? 'btn-primary rounded-pill px-4' : 'btn-light border-0 rounded-pill px-3 text-muted' }}">Mois</a>
                        <a href="{{ route('agent.dashboard', ['filter' => 'annee']) }}"
                            class="btn btn-sm {{ $stats['currentFilter'] === 'annee' ? 'btn-primary rounded-pill px-4' : 'btn-light border-0 rounded-pill px-3 text-muted' }}">Année</a>
                        <a href="{{ route('agent.dashboard', ['filter' => 'tout']) }}"
                            class="btn btn-sm {{ $stats['currentFilter'] === 'tout' ? 'btn-primary rounded-pill px-4' : 'btn-light border-0 rounded-pill px-3 text-muted' }}">Tout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATISTIQUES -->
        <div class="row g-4 mb-4">
            @if ($isRecouvrement)
                <!-- Stat: Total Encaissé -->
                <div class="col-md-4 col-xl-4">
                    <div class="premium-card p-4 h-100">
                        <div class="icon-box bg-success-soft">
                            <i class="fas fa-sack-dollar"></i>
                        </div>
                        <div class="stat-label">
                            Total Encaissé
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
                        <div class="stat-value text-success">
                            {{ number_format($stats['totalEncaisse'], 0, ',', ' ') }}
                            <small class="fs-6">FCFA</small>
                        </div>
                        <div class="mt-2 text-muted small">Vos encaissements cumulés</div>
                    </div>
                </div>

                <!-- Stat: Reste à Verser -->
                <div class="col-md-4 col-xl-4">
                    <div class="premium-card p-4 h-100">
                        <div class="icon-box bg-danger-soft">
                            <i class="fas fa-hand-holding-dollar"></i>
                        </div>
                        <div class="stat-label">
                            Reste à Verser
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
                        <div class="stat-value text-danger">
                            {{ number_format($stats['montantNonVerse'], 0, ',', ' ') }}
                            <small class="fs-6">FCFA</small>
                        </div>
                        <div class="mt-2 text-muted small">Fonds à remettre à la mairie</div>
                    </div>
                </div>
                <div class="col-md-4 col-xl-4">
                    <div class="premium-card p-4 h-100">
                        <div class="icon-box bg-info-soft">
                            <i class="fas fa-users-viewfinder"></i>
                        </div>
                        <div class="stat-label">Nombre de contribuables</div>
                        <div class="stat-value">{{ $stats['countContribuablesRecenses'] }}</div>

                        {{-- Logique PHP sécurisée pour le secteur --}}
                        @php
                            $secteurNom = 'Aucun secteur';
                            if (!empty($agent->secteur_id) && isset($agent->secteur_id[0])) {
                                $secteur = \App\Models\Secteur::find((int) $agent->secteur_id[0]);
                                if ($secteur) {
                                    $secteurNom = $secteur->nom;
                                }
                            }
                        @endphp

                        <div class="mt-2">
                            <span class="text-muted small">Mon secteur :</span>
                            <span class="trend-badge trend-up">
                                {{ $secteurNom }}
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <!-- Stat: Total Mairie -->
                <div class="col-md-6 col-xl-6">
                    <div class="premium-card p-4 h-100">
                        <div class="icon-box bg-primary-soft">
                            <i class="fas fa-city text-primary"></i>
                        </div>
                        <div class="stat-label">Total Contribuables (Mairie)</div>
                        <div class="stat-value text-primary">{{ $stats['totalContribuablesMairie'] }}</div>

                        <div class="mt-2">
                            <span class="text-muted small">Ensemble de la mairie</span>
                        </div>
                    </div>
                </div>

                <!-- Stat: Mes Recensements -->
                <div class="col-md-6 col-xl-6">
                    <div class="premium-card p-4 h-100">
                        <div class="icon-box bg-success-soft">
                            <i class="fas fa-user-check text-success"></i>
                        </div>
                        <div class="stat-label">
                            Mes Recensements
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
                        <div class="stat-value text-success">{{ $stats['countContribuablesRecenses'] }}</div>

                        {{-- Logique PHP sécurisée pour le secteur --}}
                        @php
                            $secteurNom = 'Aucun secteur';
                            if (!empty($agent->secteur_id) && isset($agent->secteur_id[0])) {
                                $secteur = \App\Models\Secteur::find((int) $agent->secteur_id[0]);
                                if ($secteur) {
                                    $secteurNom = $secteur->nom;
                                }
                            }
                        @endphp

                        <div class="mt-2">
                            <span class="text-muted small">Mon secteur :</span>
                            <span class="trend-badge trend-up">
                                {{ $secteurNom }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- ACTIONS RAPIDES -->
        <div class="row g-3 mb-4">
            <div class="col-lg-12">
                <div class="premium-card p-4">
                    <h5 class="fw-bold mb-4">Actions Rapides</h5>
                    <div class="row g-3">

                        @if ($isRecensement)
                            <div class="col-md-4">
                                <a href="{{ route('agent.contribuable.create') }}"
                                    class="btn btn-primary w-100 py-3 rounded-pill shadow-sm">
                                    <i class="fas fa-user-plus me-2"></i> Recenser Contribuable
                                </a>
                            </div>
                            <div class="col-md-8">
                                {{-- liste des quatre derniers contribuables ajouter par l'agent récemment --}}
                                <h6 class="fw-bold text-muted mb-3">
                                    Derniers recensements
                                    {{ $stats['currentFilter'] === 'tout' ? '' : ' (Période sélectionnée)' }}
                                </h6>
                                <div class="table-responsive">
                                    <table class="innovative-table w-100">
                                        <thead>
                                            <tr>
                                                <th>N° Contribuable</th>
                                                <th>Nom</th>
                                                <th>Date / Heure</th>
                                                <th>E-mail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($stats['dernieresActivites'] as $contribuable)
                                                <tr>
                                                    <td class="fw-bold">{{ $contribuable->num_commerce }}</td>
                                                    <td>{{ $contribuable->nom }}</td>
                                                    <td>{{ $contribuable->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $contribuable->email ?? 'N/A' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-3 text-muted">Aucun
                                                        contribuable recensé récemment</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @if ($isRecouvrement)
                            <div class="col-md-4">
                                <a href="{{ route('agent.encaissement.index') }}"
                                    class="btn btn-secondary w-100 py-3 rounded-pill shadow-sm">
                                    <i class="fas fa-wallet me-2"></i> Faire Encaissement
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('agent.profile') }}"
                                    class="btn btn-light w-100 py-3 rounded-pill border shadow-sm">
                                    <i class="fas fa-chart-line me-2"></i> Mon Bilan Financier
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <!-- DERNIÈRES ACTIVITÉS (Uniquement Recouvrement) -->
        @if ($isRecouvrement)
            <div class="row">
                <div class="col-12">
                    <div class="premium-card p-4">
                        <h5 class="fw-bold mb-4">
                            Derniers Encaissements Effectués
                            <small class="text-muted fw-normal fs-6">
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
                        </h5>

                        @if (count($stats['dernieresActivites']) > 0)
                            <div class="table-responsive">
                                <table class="innovative-table w-100">
                                    <thead>
                                        <tr>
                                            <th>Contribuable</th>
                                            <th>Montant</th>
                                            <th>Date / Heure</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stats['dernieresActivites'] as $act)
                                            <tr>
                                                <td class="fw-bold">{{ $act->num_commerce }}</td>
                                                <td class="text-success fw-bold">
                                                    {{ number_format($act->montant_percu, 0, ',', ' ') }} FCFA
                                                </td>
                                                <td>{{ $act->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">Enregistré</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted mb-0">Aucune activité récente.</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
