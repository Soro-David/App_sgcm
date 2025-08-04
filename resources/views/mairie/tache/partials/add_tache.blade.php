<!-- Modal d'ajout de taxe municipale -->
<div class="modal fade" id="addMairieModal" tabindex="-1" aria-labelledby="addMairieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">
            
            <div class="modal-header bg-primary text-white rounded-top">
                <h5 class="modal-title" id="addMairieModalLabel">🎯 Attribuer des taxes à un agent</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <form action="{{ route('superadmin.taxes.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="row g-4">

                        <!-- Sélection multiple de taxes -->
                        <div class="col-md-6">
                            <label for="taxes" class="form-label fw-semibold">Taxes <span class="text-danger">*</span></label>
                            <select name="taxes[]" id="taxes" class="form-select select2 shadow-sm border-primary" multiple required>
                                @foreach($taxes as $taxe)
                                    <option value="{{ $taxe->id }}">{{ $taxe->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sélection d'un agent -->
                        <div class="col-md-6">
                            <label for="agent_id" class="form-label fw-semibold">Agent <span class="text-danger">*</span></label>
                            <select name="agent_id" id="agent_id" class="form-select select2 shadow-sm border-primary" required>
                                <option value="">-- Choisir un agent --</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nom du secteur -->
                        <div class="col-md-6">
                            <label for="nom_secteur" class="form-label fw-semibold">Nom du secteur <span class="text-danger">*</span></label>
                            <div class="input-group shadow-sm">
                                <input type="text" class="form-control border-primary" name="nom_secteur" id="nom_secteur" placeholder="Ex: Cocody Nord" required>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalAddSecteur" title="Ajouter un secteur">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <small class="text-muted">Nom du secteur concerné ou ajoutez-en un nouveau.</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light px-4 py-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">✅ Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>






{{-- ADD Secteur --}}
<div class="modal fade" id="modalAddSecteur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un secteur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="nom">Nom du secteur</label>
                    <input type="text" class="form-control" name="nom" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</div>

