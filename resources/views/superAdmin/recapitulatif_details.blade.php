@extends('superAdmin.layouts.app')

@section('title', 'Détails Mairie - ' . $mairie->name)

@push('css')
    <style>
        .recap-container {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
        }

        .mairie-header-details {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
        }

        .section-title {
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #718096;
            margin: 1.5rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-custom {
            font-size: 0.85rem;
        }

        .table-custom thead th {
            background-color: #f7fafc;
            color: #4a5568;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .badge-agent {
            background-color: #ebf4ff;
            color: #3182ce;
            font-weight: 600;
        }

        .badge-role {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .log-item {
            padding: 0.75rem;
            border-left: 3px solid #cbd5e0;
            margin-bottom: 0.5rem;
            background: white;
            border-radius: 0 4px 4px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .log-item.event-login {
            border-left-color: #4299e1;
        }

        .log-item.event-create {
            border-left-color: #48bb78;
        }

        .log-item.event-update {
            border-left-color: #ecc94b;
        }

        .log-item.event-delete {
            border-left-color: #f56565;
        }

        .log-time {
            font-size: 0.75rem;
            color: #a0aec0;
        }

        .log-user {
            font-weight: 600;
            color: #4a5568;
        }

        .nav-pills-custom .nav-link {
            color: #718096;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            margin-right: 0.5rem;
        }

        .nav-pills-custom .nav-link.active {
            background-color: #4c51bf;
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Détails des Activités</h3>
                    <p class="text-muted mb-0">Consultez les informations détaillées pour cette commune.</p>
                </div>
                <a href="{{ route('superadmin.recapitulatif') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>

        <div class="mairie-header-details">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-4"
                style="width: 60px; height: 60px; overflow: hidden; border: 1px solid #eee;">
                @if ($mairie->logo)
                    <img src="{{ asset('storage/' . $mairie->logo) }}" alt="Logo" class="rounded-circle"
                        style="width: 55px; height: 55px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                        style="width: 100%; height: 100%; font-size: 1.5rem;">
                        <i class="fas fa-city"></i>
                    </div>
                @endif
            </div>
            <div>
                <h4 class="mb-1 text-dark fw-bold">{{ $mairie->name }}</h4>
                <div class="text-muted" style="font-size: 0.95rem;">
                    <i class="fas fa-map-marker-alt me-1"></i>{{ $mairie->region }} - {{ $mairie->commune }}
                </div>
            </div>
        </div>

        <div class="recap-container">
            <div class="bg-white border p-4 rounded" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);">
                <!-- Onglets internes -->
                <ul class="nav nav-pills nav-pills-custom mb-4" id="pills-tab-{{ $mairie->id }}" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="pills-contrib-tab-{{ $mairie->id }}" data-bs-toggle="pill"
                            data-bs-target="#pills-contrib-{{ $mairie->id }}" type="button"><i
                                class="fas fa-users me-2"></i>Contribuables</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="pills-staff-tab-{{ $mairie->id }}" data-bs-toggle="pill"
                            data-bs-target="#pills-staff-{{ $mairie->id }}" type="button"><i
                                class="fas fa-user-tie me-2"></i>Agent Mairie</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="pills-fin-tab-{{ $mairie->id }}" data-bs-toggle="pill"
                            data-bs-target="#pills-fin-{{ $mairie->id }}" type="button"><i
                                class="fas fa-money-check-alt me-2"></i>Agents de la régie</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="pills-logs-tab-{{ $mairie->id }}" data-bs-toggle="pill"
                            data-bs-target="#pills-logs-{{ $mairie->id }}" type="button"><i
                                class="fas fa-history me-2"></i>Audit / Logs</button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent-{{ $mairie->id }}">
                    <!-- Onglet Contribuables -->
                    <div class="tab-pane fade show active" id="pills-contrib-{{ $mairie->id }}">
                        <div class="table-responsive">
                            <table class="table table-hover table-custom">
                                <thead>
                                    <tr>
                                        <th>Contribuable</th>
                                        <th>Contact</th>
                                        <th>Agent Recenseur</th>
                                        <th>Date Ajout</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mairie->contribuables as $contrib)
                                        <tr>
                                            <td class="fw-bold">{{ $contrib->nom }}</td>
                                            <td>{{ $contrib->telephone }}</td>
                                            <td>
                                                @if ($contrib->agent)
                                                    <span class="badge badge-agent">
                                                        <i class="fas fa-user-tie me-1"></i>
                                                        {{ $contrib->agent->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Inconnu</span>
                                                @endif
                                            </td>
                                            <td>{{ $contrib->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">Aucun contribuable
                                                trouvé.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Onglet Personnel -->
                    <div class="tab-pane fade" id="pills-staff-{{ $mairie->id }}">
                        <div class="table-responsive">
                            <table class="table table-hover table-custom">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Rôle</th>
                                        <th>Email</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mairie->personnel as $staff)
                                        <tr>
                                            <td class="fw-bold">{{ $staff->name }}</td>
                                            <td><span
                                                    class="badge bg-info text-white badge-role">{{ ucfirst($staff->type) }}</span>
                                            </td>
                                            <td>{{ $staff->email }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $staff->status == 'active' ? 'success' : 'warning' }} badge-role">{{ ucfirst($staff->status) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">Aucun personnel
                                                supplémentaire.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Onglet Agents Financiers -->
                    <div class="tab-pane fade" id="pills-fin-{{ $mairie->id }}">
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <div class="section-title mt-0 text-primary"><i class="fas fa-briefcase me-2"></i>Financiers
                                </div>
                                <div class="table-responsive mb-4">
                                    <table class="table table-hover table-custom">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Contact</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($mairie->agents_financiers as $fin)
                                                <tr>
                                                    <td class="fw-bold">{{ $fin->name }}</td>
                                                    <td>{{ $fin->email }}</td>
                                                    <td>{{ $fin->telephone1 }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-3 text-muted">Aucun régisseur
                                                        trouvé.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="section-title mt-0 text-success"><i class="fas fa-coins me-2"></i>Agents
                                    de la régie</div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-custom">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Rôle</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($mairie->ag_finances as $agf)
                                                <tr>
                                                    <td class="fw-bold">{{ $agf->name }}</td>
                                                    <td>{{ $agf->email }}</td>
                                                    <td>{{ $agf->role }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-3 text-muted">Aucun agent
                                                        finance trouvé.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Onglet Logs -->
                    <div class="tab-pane fade" id="pills-logs-{{ $mairie->id }}">
                        <div class="logs-wrapper bg-light rounded p-3" style="max-height: 500px; overflow-y: auto;">
                            @forelse($mairie->logs as $log)
                                <div class="log-item event-{{ strtolower($log->event) }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="log-user">
                                                @if ($log->user)
                                                    {{ $log->user->name }}
                                                    <small
                                                        class="text-muted">({{ class_basename($log->user_type) }})</small>
                                                @else
                                                    Utilisateur inconnu
                                                @endif
                                            </span>
                                            <span class="mx-2 text-muted">•</span>
                                            <span class="log-event badge bg-secondary">{{ $log->event }}</span>
                                        </div>
                                        <div class="log-time text-end">
                                            <div>{{ $log->created_at->format('d/m/Y H:i') }}</div>
                                            <div style="font-size: 0.65rem;">{{ $log->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-sm text-dark">
                                        {{ $log->description ?? 'Aucune description additionnelle.' }}</div>
                                    @if ($log->ip_address)
                                        <div class="mt-2"
                                            style="font-size: 0.7rem; color: #a0aec0; border-top: 1px dashed #e2e8f0; padding-top: 5px;">
                                            <i class="fas fa-globe me-1"></i>IP: {{ $log->ip_address }} | <i
                                                class="fas fa-laptop me-1"></i>{{ Str::limit($log->user_agent, 50) }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-history fa-3x mb-3 text-light"></i>
                                    <p class="fs-5">Aucun journal d'activité disponible.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
