@extends('mairie.layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Section du formulaire d'enregistrement des dépenses --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary text-center">Journal de dépense</h4>
        </div>

        <div class="card-body p-4">
            {{-- Zone pour afficher les messages de succès ou d'erreur via JS --}}
            <div id="form-messages"></div>

            <form id="depenseForm" method="POST" action="{{ route('mairie.depense.store') }}" enctype="multipart/form-data">
                @csrf
                {{-- Les champs du formulaire restent inchangés --}}
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <label for="motif" class="form-label">Motif*</label>
                        <input type="text" class="form-control border border-1 rounded" id="motif" name="motif" required>
                    </div>
                    <div class="col-md-3 mb-4">
                        <label for="montant" class="form-label">Montant (FCFA)*</label>
                        <input type="number" step="1" class="form-control border border-1 rounded" id="montant" name="montant" required>
                    </div>
                    <div class="col-md-3 mb-4">
                        <label for="date_depense" class="form-label">Date de dépense*</label>
                        <input type="date" class="form-control border border-1 rounded" id="date_depense" name="date_depense" required>
                    </div>
                    <div class="col-md-3 mb-4">
                        <label for="reference" class="form-label">Référence (facultatif)</label>
                        <input type="text" class="form-control border border-1 rounded" id="reference" name="reference">
                    </div>
                </div>
                <div class="row">                   
                    <div class="col-md-4 mb-4">
                        <label for="description" class="form-label">Description*</label>
                        <textarea class="form-control border border-1 rounded" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="col-md-3 mb-4">
                        <label for="mode_paiement" class="form-label">Mode de paiement*</label>
                        <select class="form-control border border-1 rounded" id="mode_paiement" name="mode_paiement" required>
                            <option value="">-- Choisir --</option>
                            <option value="cash">Cash</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="cheque">Chèque</option>
                            <option value="virement">Virement bancaire</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="piece_jointe" class="form-label">Pièce jointe (facture, reçu...)</label>
                        <input type="file" class="form-control border border-1 rounded" id="piece_jointe" name="piece_jointe" accept="image/*,application/pdf">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" id="save_button" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <i class="typcn typcn-input-checked"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

   {{-- Section de la liste des dépenses --}}
    <div class="card shadow-sm mt-5">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Historique des Dépenses</h6>
        </div>
        <div class="card-body">
            {{-- NOUVEAU : Zone pour afficher les messages de succès/erreur de la session --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" id="depensesTable" width="100%" cellspacing="0" 
                       data-ajax-url="{{ route('mairie.depense.list') }}"
                       data-lang-url="{{ asset('js/fr-FR.json') }}">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Motif</th>
                            <th>Montant</th>
                            <th>Description</th>
                            <th>Mode de Paiement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
    {{-- Ce fichier contiendra le code JavaScript corrigé ci-dessous --}}
    <script src="{{ asset('assets/js/mairie_journal_depense.js') }}"></script>
@endpush