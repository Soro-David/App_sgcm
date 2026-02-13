<!-- Modal de modification de taxe municipale -->
<div class="modal fade" id="editTaxeModal" tabindex="-1" aria-labelledby="editTaxeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center">
                    <div class="icon-box">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h5 class="modal-title" id="editTaxeModalLabel">Modifier la Taxe</h5>
                        <p class="mb-0 small text-white-50">Modifiez les informations de la taxe</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTaxeForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_taxe_id" name="taxe_id">
                <div class="modal-body">
                    {{-- Nom --}}
                    <div class="mb-3">
                        <label for="edit_nom" class="form-label">Nom de la taxe *</label>
                        <input type="text" name="nom" id="edit_nom" class="form-control"
                            placeholder="Ex: Taxe sur les marchés" required>
                    </div>

                    <div class="row">
                        {{-- Montant --}}
                        <div class="col-md-6 mb-3">
                            <label for="edit_montant" class="form-label">Montant (FCFA) *</label>
                            <input type="number" step="0.01" name="montant" id="edit_montant" class="form-control"
                                placeholder="0.00" required>
                        </div>

                        {{-- Fréquence --}}
                        <div class="col-md-6 mb-3">
                            <label for="edit_frequence" class="form-label">Fréquence *</label>
                            <select name="frequence" id="edit_frequence" class="form-select" required>
                                <option value="">-- Sélectionnez --</option>
                                <option value="jour">Journalier</option>
                                <option value="mois">Mensuel</option>
                                <option value="an">Annuel</option>
                            </select>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"
                            placeholder="Description de la taxe..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>
