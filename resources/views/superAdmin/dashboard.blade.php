@extends('superAdmin.layouts.app')

@section('title', 'Tableau de Bord - Super Admin')

@push('css')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #2af598 0%, #009efd 100%);
            --warning-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            --danger-gradient: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            color: white;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .stat-card .card-body {
            padding: 1.5rem;
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.3;
            position: absolute;
            right: 15px;
            bottom: 10px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }

        .stat-label {
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }

        .bg-primary-grad {
            background: var(--primary-gradient);
        }

        .bg-secondary-grad {
            background: var(--secondary-gradient);
        }

        .bg-warning-grad {
            background: var(--warning-gradient);
        }

        .bg-danger-grad {
            background: var(--danger-gradient);
        }

        .bg-info-grad {
            background: var(--info-gradient);
        }

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background-color: #f8f9fa;
            border-top: none;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
        }

        .badge-soft-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="fw-bold text-dark">Tableau de Bord Global</h3>
                <p class="text-muted">Vue d'overview de toutes les mairies et transactions.</p>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card stat-card bg-primary-grad">
                    <div class="card-body position-relative">
                        <div class="stat-label">Mairies Actives</div>
                        <div class="stat-value">{{ number_format($stats['total_mairies']) }}</div>
                        <i class="fas fa-city stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card bg-secondary-grad">
                    <div class="card-body position-relative">
                        <div class="stat-label">Contribuables</div>
                        <div class="stat-value">{{ number_format($stats['total_commercants']) }}</div>
                        <i class="fas fa-users stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card bg-info-grad">
                    <div class="card-body position-relative">
                        <div class="stat-label">Agents Terrain</div>
                        <div class="stat-value">{{ number_format($stats['total_agents']) }}</div>
                        <i class="fas fa-user-tie stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Mairies by Region -->
            <div class="col-lg-12">
                <div class="chart-container">
                    <h5 class="card-title fw-bold mb-4">Mairies par Région</h5>
                    <div style="height: 300px;">
                        <canvas id="regionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Recent Mairies Table -->
            <div class="col-lg-12">
                <div class="table-container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title fw-bold mb-0">Mairies Récemment Inscrites</h5>
                        <a href="{{ route('superadmin.mairies.index') }}" class="btn btn-sm btn-outline-primary">Voir
                            tout</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Nom de la Mairie</th>
                                    <th>Région</th>
                                    <th>Contact</th>
                                    <th>Date d'inscription</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentMairies as $mairie)
                                    <tr>
                                        <td>
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-building text-muted"></i>
                                            </div>
                                        </td>
                                        <td class="fw-bold">{{ $mairie->name }}</td>
                                        <td>{{ $mairie->region }}</td>
                                        <td>
                                            <small class="d-block">{{ $mairie->email }}</small>
                                            <small class="text-muted">{{ $mairie->telephone1 }}</small>
                                        </td>
                                        <td>{{ $mairie->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <span
                                                class="badge badge-soft-{{ $mairie->status == 'active' ? 'success' : 'warning' }}">
                                                {{ ucfirst($mairie->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Aucune mairie enregistrée.
                                        </td>
                                    </tr>
                                @endforelse
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
        // Configuration de Region Chart
        const regionCtx = document.getElementById('regionChart').getContext('2d');
        const regionData = @json($mairiesByRegion);

        new Chart(regionCtx, {
            type: 'doughnut',
            data: {
                labels: regionData.map(d => d.region),
                datasets: [{
                    data: regionData.map(d => d.total),
                    backgroundColor: [
                        '#667eea', '#764ba2', '#2af598', '#009efd', '#f6d365', '#fda085', '#ff0844'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
@endpush
