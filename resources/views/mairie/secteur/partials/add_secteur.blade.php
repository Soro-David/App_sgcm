

<div class="modal fade" id="addMairieModal" tabindex="-1" aria-labelledby="addMairieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST"  action="{{ route('mairie.secteurs.store') }}">
                @csrf
                 <div class="modal-header">
                    <h5 class="modal-title" id="addMairieModalLabel">Ajouter un secteur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="code_secteur">Code Secteur</label>
                        <input type="text" class="form-control @error('code_secteur') is-invalid @enderror"
                               id="code_secteur" name="code_secteur" readonly required>
                        @error('code_secteur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nom">Nom du secteur</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror"
                               id="nom" name="nom" value="{{ old('nom') }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                     <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
