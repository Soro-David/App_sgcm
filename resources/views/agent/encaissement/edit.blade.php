@extends('agent.layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Effectuer un Encaissement</h3>
                <a href="{{ route('agent.encaissement.index') }}" class="btn btn-sm btn-secondary">Retour</a>
            </div>
            <div class="card-body">
                <h4>Contribuable : {{ $commercant->nom }}</h4>
                <p><strong>Numéro de Commerce :</strong> <span
                        id="num_commerce_display">{{ $commercant->num_commerce }}</span></p>
                <hr>

                {{-- Le lecteur QR code --}}
                <div id="qr-reader" style="width: 100%; max-width: 400px; margin: auto;"></div>
                <div id="qr-reader-results"></div>

                {{-- Le formulaire est maintenant sans action directe, gérée par JS --}}
                <form id="paymentForm" class="mt-4" style="display: none;">
                    @csrf
                    <input type="hidden" name="num_commerce" value="{{ $commercant->num_commerce }}">

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="taxe_id" class="form-label">Type de Taxe <span class="text-danger">*</span></label>
                            <select name="taxe_id" id="taxe_id" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionnez une taxe --</option>
                                @foreach ($taxesAgent as $taxe)
                                    {{-- Correction: Utiliser 'nom' comme dans le PayementController --}}
                                    <option value="{{ $taxe->id }}">{{ $taxe->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Section des détails de paiement, affichée dynamiquement -->
                    <div id="paymentDetails" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="montant_par_periode" class="form-label">Montant par période (FCFA)</label>
                                <input type="text" id="montant_par_periode" class="form-control" readonly
                                    style="font-weight: bold; background: #e9ecef;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nombre_periodes" class="form-label">Nombre de périodes à payer <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="nombre_periodes" id="nombre_periodes" class="form-control"
                                    min="1" required>
                                <small id="unpaid_periods_info" class="form-text text-muted"></small>
                            </div>
                        </div>
                        <div class="mb-3 bg-light p-3 rounded text-end">
                            <h4 class="mb-0">Montant Total : <span id="total_amount_display"
                                    class="fw-bold text-success">0</span> FCFA</h4>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">
                                Confirmer et Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        // Passer les données et URLs de PHP vers JavaScript
        window.config = {
            paymentFormAction: "{{ route('agent.encaissement.update', $commercant->id) }}",
            getTaxeDetailsUrl: "{{ route('agent.encaissement.get_taxe_details', ['commercantId' => $commercant->id, 'taxeId' => '__TAXE_ID__']) }}",
            successRedirectUrl: "{{ route('agent.encaissement.index') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    <script src="{{ asset('assets/js/agent_encaissement_pay.js') }}"></script>
@endpush
