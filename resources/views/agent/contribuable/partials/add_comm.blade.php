    <div class="modal fade" id="addMairieModal" tabindex="-1" aria-labelledby="addMairieModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('agent.commerce.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCommerceModalLabel">Ajouter un commerçant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mt-2">
                            <div class="col-md-12">
                                <label for="nom" class="form-label">Nom du commerçant</label>
                                <input type="text" class="form-control" id="nom" name="nom" required value="{{ old('nom') }}">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Adresse e-mail</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone" value="{{ old('telephone') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" value="{{ old('adresse') }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="secteur_id" class="form-label">Secteur</label>
                                <select name="secteur_id" id="secteur_id" class="form-select" required>
                                    <option value="" disabled selected>-- Sélectionnez un secteur --</option>
                                    @foreach ($secteurs as $secteur)
                                        <option value="{{ $secteur->id }}">{{ $secteur->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-12">
                                <label for="taxe_ids" class="form-label">Taxe(s) applicable(s)</label>
                                <select name="taxe_ids[]" id="taxe_ids" class="form-select select2 w-100" multiple="multiple" required>
                                    @foreach ($taxes as $taxe)
                                        <option value="{{ $taxe->id }}">{{ $taxe->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-12">
                                <label for="num_commerce" class="form-label">Numéro Commerce</label>
                                <input type="text" class="form-control" id="num_commerce" name="num_commerce" value="{{ $num_commerce }}" readonly>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="agent_id" value="{{ $agent->id }}">
                    
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
