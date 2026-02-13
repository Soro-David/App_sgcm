        <!-- Modal Recharge -->
        <div class="modal fade" id="rechargeModal" tabindex="-1" aria-labelledby="rechargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="rechargeModalLabel"><i class="fas fa-wallet me-2"></i> Recharger mon
                            compte</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('commercant.recharge.store') }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label for="montant" class="form-label fw-bold">Montant à recharger (FCFA)</label>
                                <div class="input-group">
                                    <input type="number" name="montant" id="montant" class="form-control form-control-lg"
                                        placeholder="Ex: 5000" required min="100">
                                    <span class="input-group-text bg-light">FCFA</span>
                                </div>
                                <small class="text-muted">Le montant sera crédité instantanément sur votre solde.</small>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-4 pt-0">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-success px-4 fw-bold">Valider la recharge</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>