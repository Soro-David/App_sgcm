<div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('agent.contribuable.ajouter_contribuable') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="icon-box">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <div>
                            <h5 class="modal-title" id="addTypeModalLabel">Ajouter un type de contribuable</h5>
                            <p class="mb-0 small">Définir une nouvelle catégorie</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="labelle" class="form-label">Nom du type</label>
                        <input type="text" class="form-control" id="libelle" name="libelle" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</div>
