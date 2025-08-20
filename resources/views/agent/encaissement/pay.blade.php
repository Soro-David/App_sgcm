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
            <p><strong>Numéro de Commerce :</strong> <span id="num_commerce_display">{{ $commercant->num_commerce }}</span></p>
            <hr>

            <div id="qr-reader" style="width: 100%; max-width: 400px; margin: auto;"></div>
            <div id="qr-reader-results"></div>

            <form id="paymentForm" action="{{ route('agent.encaissement.update', $commercant->id) }}" method="POST" class="mt-4" style="display: none;">
                @csrf
                @method('PUT')
                <input type="hidden" name="num_commerce" value="{{ $commercant->num_commerce }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="taxe_id" class="form-label">Type de Taxe <span class="text-danger">*</span></label>
                        <select name="taxe_id" id="taxe_id" class="form-select" required>
                             <option value="" disabled selected>-- Sélectionnez une taxe --</option>
                             @foreach($taxesAgent as $taxe)
                                 <option value="{{ $taxe->id }}">{{ $taxe->libelle }}</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="periode" class="form-label">Mois du Paiement <span class="text-danger">*</span></label>
                        <input type="month" id="periode" name="periode" class="form-control" value="{{ date('Y-m') }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="montant" class="form-label">Montant (FCFA) <span class="text-danger">*</span></label>
                    <input type="number" name="montant" id="montant" class="form-control" placeholder="Entrez le montant" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Confirmer et Enregistrer</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // Passer les données PHP nécessaires au script JS externe
    const config = {
        paymentFormAction: "{{ route('agent.encaissement.update', $commercant->id) }}",
        successRedirectUrl: "{{ route('agent.encaissement.index') }}",
        csrfToken: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('assets/js/agent_encaissement_pay.js') }}"></script>
@endpush