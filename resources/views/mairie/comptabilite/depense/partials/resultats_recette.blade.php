@if($paiements->isEmpty())
    <div class="alert alert-warning text-center">
        Aucun résultat trouvé pour les filtres sélectionnés.
    </div>
@else
    {{-- Section des totaux et indicateurs --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Résumé de la recherche</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-light p-3 text-center">
                        <h6 class="text-primary">Total des Taxes à percevoir</h6>
                        <h4 class="font-weight-bold">{{ number_format($totalTaxesCollectees, 0, ',', ' ') }} FCFA</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section des Agents --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Synthèse par Agent</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Agent</th>
                            <th>Total Encaissé (sur cette sélection)</th>
                            <th>Montant à Verser</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agentsData as $agent)
                        <tr>
                            <td>{{ $agent->nom }}</td>
                            <td>{{ number_format($agent->total_encaisse, 0, ',', ' ') }} FCFA</td>
                            <td class="{{ $agent->doit_verser > 0 ? 'text-danger font-weight-bold' : '' }}">
                                {{ number_format($agent->doit_verser, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Aucun encaissement par un agent pour cette sélection.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Section de la liste détaillée des paiements --}}
    <form action="{{ route('mairie.recette.recette_effectuee') }}" method="POST">
        @csrf
        {{-- On passe les filtres actuels pour pouvoir revenir sur la même recherche après l'action --}}
        <input type="hidden" name="taxe_id" value="{{ request('taxe_id') }}">
        <input type="hidden" name="secteur_id" value="{{ request('secteur_id') }}">

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="m-0 font-weight-bold text-primary">Détail des Paiements</h5>
                <div>
                    <a href="{{ route('mairie.recette.export_excel') }}?{{ http_build_query(request()->query()) }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-file-excel"></i> Exporter en Excel
                    </a>
                    <a href="{{ route('mairie.recette.export_pdf') }}?{{ http_build_query(request()->query()) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                        <i class="fas fa-file-pdf"></i> Télécharger en PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                 <div class="mb-3">
                    <button type="submit" class="btn btn-success">✅ Marquer la sélection comme effectuée</button>
                 </div>
                <div class="table-responsive">
                    {{-- AJOUT DE L'ID POUR DATATABLE --}}
                    <table class="table table-bordered table-striped" id="recettes_datatable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select_all_paiements"></th>
                                <th>Commerçant</th>
                                <th>Taxe</th>
                                <th>Période</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Agent Encaisseur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paiements as $paiement)
                            <tr>
                                <td><input type="checkbox" name="paiement_ids[]" value="{{ $paiement->id }}" class="paiement-checkbox"></td>
                                <td>{{ optional($paiement->commercant)->nom ?? 'N/A' }} ({{ optional($paiement->commercant)->num_commerce }})</td>
                                <td>{{ $paiement->taxe->nom }}</td>
                                <td>{{ \Carbon\Carbon::parse($paiement->periode)->format('d/m/Y') }}</td>
                                <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    @if($paiement->statut_encaissement == 'Encaissé')
                                        <span class="badge bg-success text-white">{{ $paiement->statut_encaissement }}</span>
                                    @else
                                        <span class="badge bg-info text-white">{{ $paiement->statut_encaissement }}</span>
                                    @endif
                                </td>
                                 <td>{{ $paiement->agent_encaisseur }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
@endif