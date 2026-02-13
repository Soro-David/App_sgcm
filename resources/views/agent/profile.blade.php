@extends('agent.layouts.app')

@section('title', 'Mon Compte - Agent de Recouvrement')

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="font-weight-bold text-dark">Mon Compte</h3>
                <p class="text-muted">Consultez vos statistiques personnelles et vos versements.</p>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card card-statistics shadow-sm border-0"
                    style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white; border-radius: 15px;">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center border-right-md">
                                <p class="mb-1 opacity-75">Total Encaissé</p>
                                <h2 class="font-weight-bold mb-0">{{ number_format($totalEncaisse, 0, ',', ' ') }}
                                    <small>FCFA</small></h2>
                            </div>
                            <div class="col-md-4 text-center border-right-md">
                                <p class="mb-1 opacity-75">Montant Versé</p>
                                <h2 class="font-weight-bold mb-0">{{ number_format($totalVerse, 0, ',', ' ') }}
                                    <small>FCFA</small></h2>
                            </div>
                            <div class="col-md-4 text-center">
                                <p class="mb-1 opacity-75">Dette (Reste à verser)</p>
                                <h2 class="font-weight-bold mb-0 text-warning">
                                    {{ number_format($detteActuelle, 0, ',', ' ') }} <small>FCFA</small></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Menu -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <a href="{{ route('agent.encaissement.index') }}" class="text-decoration-none">
                    <div class="card action-card shadow-sm border-0 h-100"
                        style="border-radius: 12px; transition: transform 0.2s;">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-box bg-primary-light p-3 rounded-circle mr-3">
                                <i class="fas fa-money-bill-wave fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 font-weight-bold">Faire un encaissement</h5>
                                <small class="text-muted">Enregistrer un nouveau paiement de taxe</small>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('agent.contribuable.index') }}" class="text-decoration-none">
                    <div class="card action-card shadow-sm border-0 h-100"
                        style="border-radius: 12px; transition: transform 0.2s;">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-box bg-success-light p-3 rounded-circle mr-3">
                                <i class="fas fa-users fa-2x text-success"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 font-weight-bold">Liste de contribuable</h5>
                                <small class="text-muted">Gérer et consulter vos contribuables</small>
                            </div>
                            <i class="fas fa-chevron-right ml-auto text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Last 10 Versements -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 font-weight-bold"><i class="fas fa-history mr-2 text-info"></i> 10 Derniers
                            Versements</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">Date</th>
                                        <th class="border-0 text-right">Montant Perçu</th>
                                        <th class="border-0 text-right">Montant Versé</th>
                                        <th class="border-0 text-right">Reste</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lastVersements as $versement)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar-alt text-muted mr-2"></i>
                                                    {{ $versement->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($versement->montant_percu, 0, ',', ' ') }} FCFA</td>
                                            <td class="text-right text-success font-weight-bold">
                                                {{ number_format($versement->montant_verse, 0, ',', ' ') }} FCFA</td>
                                            <td class="text-right text-danger">
                                                {{ number_format($versement->reste, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">Aucun versement
                                                enregistré pour le moment.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-primary-light {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .bg-success-light {
            background-color: rgba(34, 197, 94, 0.1);
        }

        .action-card:hover {
            transform: translateY(-5px);
            background-color: #f8f9fa;
        }

        @media (min-width: 768px) {
            .border-right-md {
                border-right: 1px solid rgba(255, 255, 255, 0.2);
            }
        }
    </style>
@endsection
