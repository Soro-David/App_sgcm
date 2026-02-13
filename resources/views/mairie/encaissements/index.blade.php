@extends('mairie.layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/premium_dashboard.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-dark fw-bold">Liste des Encaissements</h1>
                <p class="text-muted">Consultez et filtrez les encaissements réalisés sur le terrain et en caisse.</p>
            </div>
        </div>

        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <form id="filterForm" class="row align-items-end">
                    <div class="col-md-4">
                        <label for="filter_user" class="form-label font-weight-bold">Filtrer par Agent / Caissier</label>
                        <select id="filter_user" class="form-select">
                            <option value="">Tous les encaissements</option>
                            @foreach ($agents as $agent)
                                <option value="agent_{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                            @foreach ($cashiers as $caisse)
                                <option value="caisse_{{ $caisse->id }}">{{ $caisse->name }}
                                    ({{ ucfirst($caisse->role) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" id="resetFilter" class="btn btn-secondary w-100">Réinitialiser</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="encaissementsTable" class="table table-bordered table-hover" style="width:100%"
                        data-ajax-url="{{ route('mairie.encaissement.get_grouped_list') }}"
                        data-details-url="{{ route('mairie.encaissement.get_details') }}"
                        data-lang-url="{{ asset('js/fr-FR.json') }}">

                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Nombre d'encaissements</th>
                                <th>Total perçu</th>
                                <th>Nom de l'Agent</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Détails -->
        <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailsModalLabel">Détails des encaissements</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <h6 id="modalAgentDate" class="mb-3 text-muted"></h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped" id="detailsTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Nom Commerçant</th>
                                        <th>Num Commerce</th>
                                        <th>Taxe</th>
                                        <th class="text-end">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Rempli par JS -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total Perçu :</th>
                                        <th class="text-end" id="modalTotal">0 FCFA</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/mairie_encaissements.js') }}"></script>
@endpush
