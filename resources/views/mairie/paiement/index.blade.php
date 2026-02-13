@extends('mairie.layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-3">Liste des paiements des commerçants</h1>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="paiementsTable" class="table table-bordered table-hover" style="width:100%"
                        data-ajax-url="{{ route('mairie.paiement.get_list') }}" data-lang-url="{{ asset('js/fr-FR.json') }}"
                        data-details-url="{{ route('mairie.paiement.get_details', ['num_commerce' => ':id']) }}">
                        <thead class="table-light">
                            <tr>
                                <th>Commerçant</th>
                                <th>Nombre de paiements</th>
                                <th>Total payé</th>
                                <th>Dernier paiement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <!-- Modal Détails -->
                <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="detailsModalLabel"><i class="fas fa-list"></i> Historique des
                                    paiements</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped" id="detailsTable">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Taxe</th>
                                                <th>Période</th>
                                                <th>Montant</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detailsBody">
                                            <!-- Rempli en JS -->
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
    <script src="{{ asset('assets/js/mairie_paiements.js') }}"></script>
@endpush
