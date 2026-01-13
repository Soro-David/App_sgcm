<div class="modal fade" id="addMairieModal" tabindex="-1" aria-labelledby="addMairieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center">
                    <div class="icon-box">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div>
                        <h5 class="modal-title" id="addMairieModalLabel">Ajouter un Secteur</h5>
                        <p class="mb-0 small">Nouveau secteur géographique pour la mairie</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('mairie.secteurs.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="nom" class="form-label">Nom du secteur</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                    id="nom" name="nom" value="{{ old('nom') }}"
                                    placeholder="Ex: Plateau, Cocody Sud..." required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="code_secteur" class="form-label">Code Secteur <small
                                    class="text-muted fw-normal">(Généré automatiquement)</small></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                <input type="text" class="form-control @error('code_secteur') is-invalid @enderror"
                                    id="code_secteur" name="code_secteur" readonly placeholder="Attente du nom..."
                                    required style="background-color: #f8f9fa;">
                                @error('code_secteur')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text mt-2">
                                <i class="fas fa-info-circle text-info me-1"></i> Le code est basé sur le nom du
                                secteur.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Ajouter le secteur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
