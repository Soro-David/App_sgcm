@extends('mairie.layouts.app')

@section('title', 'Tableau de Bord')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6 grid-margin stretch-card flex-column">
                <h5 class="mb-2 text-titlecase mb-4">Statistiques</h5>
                <div class="row">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <p class="mb-0 text-muted">Agents Connectés</p>
                                    <p class="mb-0 text-muted text-success">En ligne</p>
                                </div>
                                <h4>{{ $onlineAgentsCount }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <p class="mb-0 text-muted">Contribuables Connectés</p>
                                    <p class="mb-0 text-muted text-primary">En ligne</p>
                                </div>
                                <h4>{{ $onlineContribuablesCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <p class="mb-0 text-muted">Montant Payé</p>
                                </div>
                                <h4>{{ number_format($montantPaye, 0, ',', ' ') }} FCFA</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <p class="mb-0 text-muted">Reste à Verser (Agents)</p>
                                </div>
                                <h4 class="text-danger">{{ number_format($montantNonPaye, 0, ',', ' ') }} FCFA</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 grid-margin stretch-card flex-column">
                <h5 class="mb-2 text-titlecase mb-4">Statut des Utilisateurs Connectés</h5>
                <div class="row h-100">
                    <div class="col-md-12 stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Rôle</th>
                                                <th>Heure de Connexion</th>
                                                <th>Heure de Déconnexion</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>

                                        <tbody id="users-status-table-body"
                                            data-url="{{ route('mairie.dashboard.users_status') }}">
                                            {{-- Le contenu sera généré par JavaScript --}}
                                            <tr>
                                                <td colspan="5" class="text-center">Chargement des données...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection


@push('js')
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/mairie_user_connect_dash.js') }}"></script>
@endpush
