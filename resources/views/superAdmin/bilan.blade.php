@extends('superAdmin.layouts.app')

@section('title', 'Bilan par Mairie - Super Admin')

@push('css')
    <style>
        .bilan-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background-color: #f8f9fa;
            border-top: none;
            text-transform: uppercase;
            font-size: 0.8rem;
            font-weight: 700;
            color: #495057;
            padding: 1.2rem;
        }

        .table tbody td {
            padding: 1.2rem;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .mairie-name {
            font-weight: 700;
            color: #2d3748;
        }

        .stat-badge {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge-agents {
            background-color: #e0e7ff;
            color: #4338ca;
        }

        .badge-contribuables {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-recettes {
            background-color: #d1fae5;
            color: #065f46;
        }

        .search-bar {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 0.6rem 1rem;
            width: 300px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-8">
                <h3 class="fw-bold text-dark">Bilan Global par Mairie</h3>
                <p class="text-muted">Analyse détaillée des performances de chaque municipalité.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <input type="text" id="tableSearch" class="search-bar" placeholder="Rechercher une mairie...">
            </div>
        </div>

        <div class="bilan-container">
            <div class="table-responsive">
                <table class="table table-hover" id="bilanTable">
                    <thead>
                        <tr>
                            <th>Mairie</th>
                            <th>Région / Commune</th>
                            <th class="text-center">Agents</th>
                            <th class="text-center">Contribuables</th>
                            <th class="text-end">Recettes Totales</th>
                            <th class="text-center">Admin Financier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mairiesData as $mairie)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-building text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="mairie-name">{{ $mairie->municipality_name }}</div>
                                            <small class="text-muted">Réf: {{ $mairie->mairie_ref }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $mairie->region }}</div>
                                    <small class="text-muted">{{ $mairie->commune }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="stat-badge badge-agents">
                                        <i class="fas fa-user-tie me-1"></i> {{ number_format($mairie->agents_count) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="stat-badge badge-contribuables">
                                        <i class="fas fa-users me-1"></i> {{ number_format($mairie->contribuables_count) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="stat-badge badge-recettes">
                                        {{ number_format($mairie->total_recettes, 0, ',', ' ') }} <small>FCFA</small>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-secondary">{{ $mairie->admin_financier_name }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                                        <p>Aucune donnée disponible pour le moment.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.getElementById('tableSearch').addEventListener('keyup', function() {
            let searchValue = this.value.toLowerCase();
            let tableRows = document.querySelectorAll('#bilanTable tbody tr');

            tableRows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
@endpush
