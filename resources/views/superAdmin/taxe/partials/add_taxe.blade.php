<!-- Modal d'ajout de taxe municipale -->
<div class="modal fade" id="addMairieModal" tabindex="-1" aria-labelledby="addMairieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- En-tête du modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="addMairieModalLabel">Ajouter une taxe municipale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <!-- Formulaire -->
            <form action="{{ route('superadmin.taxes.store') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom de la taxe <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nom" id="nom" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="montant" class="form-label">Montant par défaut (optionnel)</label>
                        <input type="number" class="form-control" name="montant" id="montant" step="0.01">
                    </div>
                </div>

                {{-- @dd(Auth::user()) --}}
                <input type="hidden" name="mairie_ref" value="{{ Auth::user()->mairie_ref }}">
                <!-- Pied du modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
