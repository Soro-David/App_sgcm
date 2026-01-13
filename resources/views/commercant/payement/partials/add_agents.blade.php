<div class="modal fade" id="addMairieModal" tabindex="-1" aria-labelledby="addMairieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('mairie.agents.store') }}">
                @csrf
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="icon-box">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h5 class="modal-title" id="addMairieModalLabel">Ajouter un agent</h5>
                            <p class="mb-0 small">Nouveau personnel de service</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Nom de l'agent</label>
                            <input type="text" class="form-control" id="name" name="name" required
                                value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="type_agent" class="form-label">Type d'agent</label>
                            <select name="type_agent" id="type_agent" class="form-select" required>
                                <option value="" disabled selected>-- Sélectionnez un type d'agent --</option>
                                <option value="recouvrement">Recouvrement</option>
                                <option value="agent de mairie">Agent de mairie</option>
                                {{-- <option value="administratif">Administratif</option> --}}
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                value="{{ old('email') }}">
                        </div>
                        <input type="hidden" name="mairie_ref" value="{{ $mairieRef }}">

                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
