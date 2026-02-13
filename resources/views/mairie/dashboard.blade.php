@extends('mairie.layouts.app')

@section('title', 'Tableau de Bord - Admin Mairie')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/premium_dashboard.css') }}">
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-dark mb-1">Espace Admin Mairie</h2>
                <p class="text-muted">Vue d'ensemble de la municipalité et gestion des agents.</p>
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
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="premium-card p-4 border-start border-primary border-4">
                    <div class="icon-box bg-primary-soft">
                        <i class="fas fa-users-line"></i>
                    </div>
                    <div class="stat-label">Agents Terrain</div>
                    <div class="stat-value">{{ $stats['countRecouvrement'] + $stats['countRecensement'] }}</div>
                    <div class="mt-2 text-muted small">Recouvrement & Recensement</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="premium-card p-4 border-start border-success border-4">
                    <div class="icon-box bg-secondary-soft">
                        <i class="fas fa-id-card-clip"></i>
                    </div>
                    <div class="stat-label">Contribuables</div>
                    <div class="stat-value">{{ number_format($stats['totalContribuables'], 0, ',', ' ') }}</div>
                    <div class="mt-2 text-muted small">Total enregistrés</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="premium-card p-4 border-start border-success border-4">
                    <div class="icon-box bg-success-soft">
                        <i class="fas fa-user-tie text-success"></i>
                    </div>
                    <div class="stat-label">Agent Finance</div>
                    <div class="stat-value text-success">{{ number_format($stats['countAgentFinancier'], 0, ',', ' ') }}
                    </div>
                    <div class="mt-2 text-muted small">Total agents</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="premium-card p-4 border-start border-danger border-4">
                    <div class="icon-box bg-danger-soft">
                        <i class="fas fa-user-shield text-danger"></i>
                    </div>
                    <div class="stat-label">Responsable Finance</div>
                    <div class="stat-value text-danger">{{ number_format($stats['countAdminFinancier'], 0, ',', ' ') }}
                    </div>
                    <div class="mt-2 text-muted small">Administrateurs</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Graphe de progression -->
            <div class="col-lg-12">
                <div class="premium-card p-4">
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
                    </div>
                    <div class="chart-container">
                        <canvas id="progressionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Répartition détaillée -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="premium-card p-4">
                    <h6 class="fw-bold mb-3 text-muted">Terrain</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Agents Recensement</span>
                            <span class="fw-bold">{{ $stats['countRecensement'] }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Agents Recouvrement</span>
                            <span class="fw-bold">{{ $stats['countRecouvrement'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="premium-card p-4">
                    <h6 class="fw-bold mb-3 text-muted">Finances</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Administrateurs Finances</span>
                            <span class="fw-bold">{{ $stats['countAdminFinancier'] }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Agents Finances</span>
                            <span class="fw-bold">{{ $stats['countAgentFinancier'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="premium-card p-4">
                    <h6 class="fw-bold mb-3 text-muted">Caisse</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Agents Caissiers</span>
                            <span class="fw-bold">{{ $stats['countCaissier'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Liste des Utilisateurs Connectés -->
        <div class="row">
            <div class="col-12">
                <div class="premium-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Utilisateurs en Ligne (Mairie & Terrain)</h5>
                        <button class="btn btn-primary btn-sm rounded-pill px-4" id="refresh-users">
                            <i class="fas fa-sync-alt me-2"></i> Actualiser
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="innovative-table border-top">
                            <thead>
                                <tr>
                                    <th>Nom Complet</th>
                                    <th>Poste / Rôle</th>
                                    <th>Heure Log</th>
                                    <th>Statut</th>
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
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($stats['progressionLabels']) !!},
                    datasets: [{
                        label: 'Recettes Journalières (FCFA)',
                        data: {!! json_encode($stats['progressionValues']) !!},
                        backgroundColor: '#2563eb',
                        borderRadius: 8,
                        barThickness: 30
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
                            }
                        },
                        x: {
                            grid: {
                                display: false
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
                                    <td class="text-muted small">${user.login_time}</td>
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
            setInterval(loadUserStatus, 30000);
        });
    </script>
@endpush
