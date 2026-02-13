@extends('mairie.layouts.app')

@section('title', 'Tableau de Bord - Admin Financier')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/premium_dashboard.css') }}">
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-dark mb-1">Espace Admin Financier</h2>
                <p class="text-muted">Aperçu global des performances financières et des agents.</p>
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

        <!-- Statistiques Principales -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="premium-card p-4">
                    <div class="icon-box bg-primary-soft">
                        <i class="fas fa-users-viewfinder"></i>
                    </div>
                    <div class="stat-label">Total Contribuables</div>
                    <div class="stat-value">{{ number_format($stats['totalContribuables'], 0, ',', ' ') }}</div>
                    <div class="mt-2">
                        <span class="trend-badge trend-up">
                            <i class="fas fa-arrow-up me-1"></i> Focus Mairie
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="premium-card p-4">
                    <div class="icon-box bg-secondary-soft">
                        <i class="fas fa-hand-holding-dollar"></i>
                    </div>
                    <div class="stat-label">
                        Recettes Totales
                        <small class="text-muted">
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
                    <div class="stat-value text-success">{{ number_format($stats['montantPaye'], 0, ',', ' ') }} <small
                            class="fs-6">FCFA</small></div>
                    <div class="mt-2 text-muted small">Total encaissé validé</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="premium-card p-4">
                    <div class="icon-box bg-danger-soft">
                        <i class="fas fa-file-invoice-dollar text-danger"></i>
                    </div>
                    <div class="stat-label">
                        Dette Totale
                        <small class="text-muted">
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
                    <div class="stat-value text-danger">{{ number_format($stats['totalDette'], 0, ',', ' ') }} <small
                            class="fs-6">FCFA</small></div>
                    <div class="mt-2 text-muted small">Reste à recouvrer par les agents</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="premium-card p-4">
                    <div class="icon-box bg-info-soft">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-label">Agents Financiers</div>
                    <div class="stat-value">{{ $stats['countAgentFinancier'] }}</div>
                    <div class="mt-2 text-muted small">Actifs dans la mairie</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Graphe de progression -->
            <div class="col-lg-8">
                <div class="premium-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">
                            @if ($stats['currentFilter'] === 'jour')
                                Evolution des Recettes (Aujourd'hui)
                            @elseif($stats['currentFilter'] === 'mois')
                                Evolution des Recettes (Ce Mois)
                            @elseif($stats['currentFilter'] === 'annee')
                                Evolution des Recettes (Cette Année)
                            @else
                                Evolution des Recettes (7 derniers jours)
                            @endif
                        </h5>
                        <div class="dropdown">
                            <span class="badge bg-info-soft text-info">
                                Filtre : {{ ucfirst($stats['currentFilter']) }}
                            </span>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="progressionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Répartition des Agents -->
            <div class="col-lg-4">
                <div class="premium-card p-4 h-100">
                    <h5 class="fw-bold mb-4">Effectifs par Type</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-person-walking-arrow-right"></i>
                                </div>
                                <span class="fw-medium">Recouvrement</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $stats['countRecouvrement'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-clipboard-user"></i>
                                </div>
                                <span class="fw-medium">Recensement</span>
                            </div>
                            <span class="badge bg-success rounded-pill">{{ $stats['countRecensement'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-cash-register"></i>
                                </div>
                                <span class="fw-medium">Caissiers</span>
                            </div>
                            <span class="badge bg-warning rounded-pill">{{ $stats['countCaissier'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des Agents Connectés -->
        <div class="row">
            <div class="col-12">
                <div class="premium-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Agents Actuellement en Ligne</h5>
                        <button class="btn btn-primary btn-sm rounded-pill px-4" id="refresh-users">
                            <i class="fas fa-sync-alt me-2"></i> Actualiser
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="innovative-table">
                            <thead>
                                <tr>
                                    <th>Nom de l'Agent</th>
                                    <th>Fonction / Rôle</th>
                                    <th>Dernière Connexion</th>
                                    <th>Statut Actuel</th>
                                </tr>
                            </thead>
                            <tbody id="users-status-table-body" data-url="{{ route('mairie.dashboard.users_status') }}">
                                {{-- Rempli via JS --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Graphe de progression
            const ctx = document.getElementById('progressionChart').getContext('2d');
            const progressionChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($stats['progressionLabels']) !!},
                    datasets: [{
                        label: 'Paiements (FCFA)',
                        data: {!! json_encode($stats['progressionValues']) !!},
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: '#f1f5f9'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });

            // Logique de statut des utilisateurs
            function loadUserStatus() {
                const tbody = document.getElementById('users-status-table-body');
                const url = tbody.getAttribute('data-url');

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        tbody.innerHTML = '';
                        data.forEach(user => {
                            const isOnline = user.status === 'En ligne';
                            const row = `
                                <tr>
                                    <td class="fw-bold text-dark">${user.name}</td>
                                    <td><span class="badge bg-light text-dark border">${user.role}</span></td>
                                    <td class="text-muted">${user.login_time}</td>
                                    <td>
                                        <div class="status-indicator ${isOnline ? 'status-online' : 'status-offline'}">
                                            <div class="status-dot"></div>
                                            ${user.status}
                                        </div>
                                    </td>
                                </tr>
                            `;
                            tbody.innerHTML += row;
                        });
                    });
            }

            loadUserStatus();
            document.getElementById('refresh-users').addEventListener('click', loadUserStatus);
            setInterval(loadUserStatus, 30000); // Actualisation auto toutes les 30s
        });
    </script>
@endpush
