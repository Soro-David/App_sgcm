@extends('mairie.layouts.app')

@section('title', 'Tableau de Bord - Agent Financier')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/premium_dashboard.css') }}">
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-dark mb-1">Espace Agent Financier</h2>
                <p class="text-muted">Suivi de vos versements et vue d'ensemble de la mairie.</p>
            </div>
            <div class="text-end">
                <div id="real-time-clock" class="fw-bold text-primary fs-5 mb-1"></div>
                <div class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Aujourd'hui : {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </div>
            </div>
        </div>

        <!-- Statistiques Principales -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="premium-card p-4 border-start border-primary border-4">
                    <div class="icon-box bg-primary-soft">
                        <i class="fas fa-money-bill-transfer text-primary"></i>
                    </div>
                    <div class="stat-label">Mes Versements</div>
                    <div class="stat-value text-primary">{{ number_format($stats['monTotalVersements'], 0, ',', ' ') }}
                        <small class="fs-6">FCFA</small>
                    </div>
                    <div class="mt-2 text-muted small">Total récupéré par vous</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="premium-card p-4">
                    <div class="icon-box bg-info-soft">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-label">Agents Terrain</div>
                    <div class="stat-value">{{ $stats['countRecouvrement'] + $stats['countRecensement'] }}</div>
                    <div class="mt-2 text-muted small">Recouvrement & Recensement</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="premium-card p-4">
                    <div class="icon-box bg-warning-soft">
                        <i class="fas fa-shop"></i>
                    </div>
                    <div class="stat-label">Contribuables</div>
                    <div class="stat-value">{{ number_format($stats['totalContribuables'], 0, ',', ' ') }}</div>
                    <div class="mt-2 text-muted small">Total enregistrés</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="premium-card p-4">
                    <div class="icon-box bg-danger-soft">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <div class="stat-label">Caissiers</div>
                    <div class="stat-value">{{ $stats['countCaissier'] }}</div>
                    <div class="mt-2 text-muted small">Total mairie</div>
                </div>
            </div>
        </div>

        <!-- Liste des Agents Connectés -->
        <div class="row">
            <div class="col-12">
                <div class="premium-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Collègues en Ligne</h5>
                        <button class="btn btn-primary btn-sm rounded-pill px-4" id="refresh-users">
                            <i class="fas fa-sync-alt me-2"></i> Actualiser
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="innovative-table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Rôle</th>
                                    <th>Dernière Connexion</th>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logique de statut des utilisateurs (Même logique que les autres dashboards)
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
            setInterval(loadUserStatus, 30000);
        });
    </script>
@endpush
