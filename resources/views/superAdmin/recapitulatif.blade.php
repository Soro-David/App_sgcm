@extends('superAdmin.layouts.app')

@section('title', 'Récapitulatif Global - Super Admin')

@push('css')
    <style>
        .recap-container {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
        }

        .mairie-card {
            border: none;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .mairie-header {
            background: white;
            padding: 1.2rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #edf2f7;
        }

        .mairie-header:hover {
            background: #fcfcfc;
        }

        .mairie-header h5 {
            margin: 0;
            font-weight: 700;
            color: #2d3748;
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
            <div class="col-12">
                <h3 class="fw-bold text-dark">Récapitulatif des Activités par Mairie</h3>
                <p class="text-muted">Consultez les contribuables, le personnel et les journaux d'audit de chaque commune.
                </p>
            </div>
        </div>

        <div class="recap-container">
            @forelse($mairies as $mairie)
                <a href="{{ route('superadmin.recapitulatif.details', $mairie->id) }}" class="text-decoration-none">
                    <div class="mairie-card">
                        <div class="mairie-header">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3"
                                    style="width: 45px; height: 45px; overflow: hidden; border: 1px solid #eee;">
                                    @if ($mairie->logo)
                                        <img src="{{ asset('storage/' . $mairie->logo) }}" alt="Logo"
                                            class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                            style="width: 100%; height: 100%;">
                                            <i class="fas fa-city"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="text-dark">{{ $mairie->name }}</h5>
                                    <small class="text-muted">{{ $mairie->region }} - {{ $mairie->commune }}</small>
                                </div>
                            </div>
                            <div class="text-primary">
                                Voir les détails <i class="fas fa-chevron-right ms-2"></i>
                            </div>
                        </div>
                    </div>
                </a>

            @empty
                <div class="alert alert-info">Aucune mairie trouvée.</div>
            @endforelse
        </div>
    </div>
@endsection
