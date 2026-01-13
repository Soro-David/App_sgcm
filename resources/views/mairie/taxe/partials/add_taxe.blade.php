<!-- Modal d'ajout de taxe municipale -->
<div class="modal fade" id="addTaxeModal" tabindex="-1" aria-labelledby="addTaxeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center">
                    <div class="icon-box">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div>
                        <h5 class="modal-title" id="addTaxeModalLabel">Ajouter une Taxe</h5>
                        <p class="mb-0 small text-white-50">Définissez une nouvelle taxe municipale</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('mairie.taxe.store') }}">
                @csrf
                <div class="modal-body">
                    {{-- Nom --}}
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom de la taxe *</label>
                        <input type="text" name="nom" id="nom" class="form-control"
                            value="{{ old('nom') }}" placeholder="Ex: Taxe sur les marchés" required>
                    </div>

                    <div class="row">
                        {{-- Montant --}}
                        <div class="col-md-6 mb-3">
                            <label for="montant" class="form-label">Montant (FCFA) *</label>
                            <input type="number" step="0.01" name="montant" id="montant" class="form-control"
                                value="{{ old('montant') }}" placeholder="0.00" required>
                        </div>

                        {{-- Fréquence --}}
                        <div class="col-md-6 mb-3">
                            <label for="frequence" class="form-label">Fréquence *</label>
                            <select name="frequence" id="frequence" class="form-select" required>
                                <option value="">-- Sélectionnez --</option>
                                <option value="jour" {{ old('frequence') == 'jour' ? 'selected' : '' }}>Journalier
                                </option>
                                <option value="mois" {{ old('frequence') == 'mois' ? 'selected' : '' }}>Mensuel
                                </option>
                                <option value="an" {{ old('frequence') == 'an' ? 'selected' : '' }}>Annuel</option>
                            </select>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"
                            placeholder="Description de la taxe...">{{ old('description') }}</textarea>
                    </div>

                    {{-- Mairie ID (caché) --}}
                    <input type="hidden" name="mairie_ref" value="{{ Auth::guard('mairie')->user()->mairie_ref }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
