    <div class="modal fade" id="addMairieModal" tabindex="-1" aria-labelledby="addMairieModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('superadmin.mairies.store') }}">
                    @csrf
                    <div class="modal-header">
                        <div class="d-flex align-items-center">
                            <div class="icon-box">
                                <i class="fas fa-landmark"></i>
                            </div>
                            <div>
                                <h5 class="modal-title" id="addMairieModalLabel">Ajouter une mairie</h5>
                                <p class="mb-0 small">Enregistrer une nouvelle collectivité locale</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nom de mairie</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    value="{{ old('name') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Adresse e-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="region" class="form-label">Région</label>
                                <select class="form-select" id="region" name="region"
                                    data-url="{{ route('superadmin.mairies.get_communes', ['region' => 'PLACEHOLDER']) }}"
                                    required>
                                    <option value="" selected disabled>-- Sélectionnez une région --</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->region }}">{{ $region->region }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="commune" class="form-label">Commune</label>
                                <select class="form-select" id="commune" name="commune" required>
                                    <option value="" selected disabled>-- Sélectionnez d'abord une région --
                                    </option>
                                </select>
                            </div>
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
