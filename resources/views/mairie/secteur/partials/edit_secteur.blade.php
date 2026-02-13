<div class="modal fade" id="editSecteurModal" tabindex="-1" aria-labelledby="editSecteurModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center">
                    <div class="icon-box bg-primary text-white p-2 rounded me-2">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h5 class="modal-title" id="editSecteurModalLabel">Modifier le Secteur</h5>
                        <p class="mb-0 small">Mettre à jour les informations du secteur</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editSecteurForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_secteur_id" name="id">
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="edit_nom" class="form-label">Nom du secteur</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control" id="edit_nom" name="nom"
                                    placeholder="Ex: Plateau, Cocody Sud..." required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="edit_code" class="form-label">Code Secteur</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                <input type="text" class="form-control" id="edit_code" name="code" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
