@extends('mairie.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-10 grid-margin stretch-card mx-auto">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h4 class="card-title mb-0 font-weight-bold">Effectuer un Encaissement</h4>
                        <a href="{{ route('mairie.caisse.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6 border-right">
                                <h5 class="text-primary mb-3">Informations Contribuable</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><strong>Nom :</strong> {{ $commercant->nom }}</li>
                                    <li class="mb-2"><strong>Numéro Commerce :</strong> <span id="num_commerce_display"
                                            class="badge bg-light text-dark fs-6">{{ $commercant->num_commerce }}</span>
                                    </li>
                                    <li class="mb-2"><strong>Téléphone :</strong> {{ $commercant->telephone }}</li>
                                    <li class="mb-2"><strong>Email :</strong> {{ $commercant->email ?? 'N/A' }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary mb-3">Informations Commerce</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><strong>Secteur :</strong>
                                        {{ $commercant->secteur ? $commercant->secteur->nom : 'N/A' }}</li>
                                    <li class="mb-2"><strong>Commune :</strong> {{ $commercant->commune }}</li>
                                </ul>
                            </div>
                        </div>

                        <form id="paymentForm">
                            @csrf
                            <input type="hidden" name="num_commerce" value="{{ $commercant->num_commerce }}">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="taxe_id" class="form-label font-weight-bold">Type de Taxe <span
                                            class="text-danger">*</span></label>
                                    <select name="taxe_id" id="taxe_id" class="form-select form-select-lg" required>
                                        <option value="" disabled selected>-- Sélectionnez une taxe --</option>
                                        @foreach ($taxesCommercant as $taxe)
                                            <option value="{{ $taxe->id }}">
                                                {{ $taxe->nom }} ({{ number_format($taxe->montant, 0, ',', ' ') }} FCFA
                                                / {{ $taxe->frequence }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="montant_par_periode" class="form-label font-weight-bold">Montant par
                                        période (FCFA)</label>
                                    <input type="text" id="montant_par_periode" class="form-control form-control-lg"
                                        readonly style="font-weight: bold; background: #f8f9fa; color: #333;">
                                </div>
                            </div>

                            <!-- Section des détails de paiement -->
                            <div id="paymentDetails" style="display: none;" class="mt-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre_periodes" class="form-label font-weight-bold">Nombre de périodes
                                            à payer <span class="text-danger">*</span></label>
                                        <input type="number" name="nombre_periodes" id="nombre_periodes"
                                            class="form-control form-control-lg" min="1" value="1" required>
                                        <small id="unpaid_periods_info" class="form-text text-muted font-italic"></small>
                                    </div>
                                </div>

                                <div class="alert alert-info d-flex justify-content-between align-items-center mt-3">
                                    <h4 class="mb-0">Montant Total à encaisser :</h4>
                                    <h3 class="mb-0 font-weight-bold text-dark"><span id="total_amount_display">0</span>
                                        FCFA</h3>
                                </div>

                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg py-3">
                                        <i class="fas fa-check-circle me-2"></i> Confirmer et Enregistrer l'Encaissement
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const taxeSelect = document.getElementById('taxe_id');
            const agentSelect = document.getElementById('agent_id');
            const paymentDetailsDiv = document.getElementById('paymentDetails');

            // Initialisation de Select2
            if (typeof $ !== 'undefined' && $.fn.select2) {
                // $(taxeSelect).select2({
                //     width: '100%',
                //     dropdownParent: $(taxeSelect).parent()
                // });
                $(agentSelect).select2({
                    width: '100%',
                    dropdownParent: $(agentSelect).parent()
                });

                $(taxeSelect).on('change', function() {
                    const event = new Event('change');
                    taxeSelect.dispatchEvent(event);
                });
            }
            const montantParPeriodeInput = document.getElementById('montant_par_periode');
            const nombrePeriodesInput = document.getElementById('nombre_periodes');
            const unpaidInfo = document.getElementById('unpaid_periods_info');
            const totalAmountDisplay = document.getElementById('total_amount_display');
            const paymentForm = document.getElementById('paymentForm');

            let montantUnitaire = 0;

            taxeSelect.addEventListener('change', function() {
                const taxeId = this.value;
                if (!taxeId) {
                    paymentDetailsDiv.style.display = 'none';
                    return;
                }

                montantParPeriodeInput.value = 'Chargement...';
                unpaidInfo.textContent = '';
                paymentDetailsDiv.style.display = 'block';

                const url =
                    "{{ route('mairie.caisse.get_taxe_details', ['commercantId' => $commercant->id, 'taxeId' => '__TAXE_ID__']) }}"
                    .replace('__TAXE_ID__', taxeId);

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            montantUnitaire = parseFloat(data.montant);
                            montantParPeriodeInput.value = montantUnitaire.toLocaleString('fr-FR');
                            nombrePeriodesInput.value = 1;
                            unpaidInfo.innerHTML =
                                `<span class="text-info font-weight-bold">${data.unpaid_count} période(s) impayée(s)</span>. Vous pouvez encaisser pour cette durée ou plus.`;
                            updateTotalAmount();
                        } else {
                            Swal.fire('Info', 'Impossible de récupérer les détails de la taxe.',
                                'info');
                            paymentDetailsDiv.style.display = 'none';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Erreur', 'Un problème de communication est survenu.', 'error');
                    });
            });

            function updateTotalAmount() {
                const n = parseInt(nombrePeriodesInput.value) || 0;
                const total = n * montantUnitaire;
                totalAmountDisplay.textContent = total.toLocaleString('fr-FR');
            }

            nombrePeriodesInput.addEventListener('input', updateTotalAmount);

            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Confirmer l\'encaissement ?',
                    text: `Vous allez encaisser un montant de ${totalAmountDisplay.textContent} FCFA.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, valider !',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData(this);
                        const storeUrl =
                            "{{ route('mairie.caisse.store_encaissement', $commercant->id) }}";

                        fetch(storeUrl, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Succès !', data.message, 'success').then(() => {
                                        window.location.href =
                                            "{{ route('mairie.caisse.index') }}";
                                    });
                                } else {
                                    Swal.fire('Erreur', data.message, 'error');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire('Erreur', 'Erreur lors de l\'enregistrement.',
                                    'error');
                            });
                    }
                });
            });
        });
    </script>
@endpush
