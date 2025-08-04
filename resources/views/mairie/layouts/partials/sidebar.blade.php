<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="d-flex flex-column justify-content-between h-100">

        <ul class="nav flex-column">

            <!-- Tableau de bord -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('mairie.dashboard') }}">
                    <i class="typcn typcn-device-desktop menu-icon"></i>
                    <span class="menu-title">Tableau de bord</span>
                </a>
            </li>

            <!-- Mes Taxes -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('mairie.taches.list_tache') }}">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Mes Taxes</span>
                </a>
            </li>
            <!-- Mes Taxes -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('mairie.secteurs.index') }}">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Mes Secteurs</span>
                </a>
            </li>
             <!-- Les Encaisement -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('mairie.taches.list_tache') }}">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Encaissement</span>
                </a>
            </li>

              <!-- Programmer un agent -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('mairie.agents.programme_agent') }}">
                    <i class="typcn typcn-group-outline menu-icon"></i>
                    <span class="menu-title">Programmer un agent</span>
                </a>
            </li>

             <!-- Gestion des Agents -->
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#collapseAgents" role="button" aria-expanded="false" aria-controls="collapseAgents">
                    <div>
                        <i class="typcn typcn-user-add-outline menu-icon"></i>
                        <span class="menu-title">Gestion des Agents</span>
                    </div>
                    <i class="fas fa-angle-down rotate-icon menu-arrow"></i>
                </a>
                <div class="collapse" id="collapseAgents">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mairie.agents.create') }}">Ajouter</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mairie.agents.index') }}">Liste</a>
                        </li>
                    </ul>
                </div>
            </li>
            <!-- Versement -->
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#collapseVersement" role="button" aria-expanded="false" aria-controls="collapseVersement">
                    <div>
                        <i class="typcn typcn-credit-card menu-icon"></i>
                        <span class="menu-title">Versement</span>
                    </div>
                    <i class="fas fa-angle-down rotate-icon menu-arrow"></i>
                </a>
                <div class="collapse" id="collapseVersement">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mairie.versements.create') }}">Faire un versement</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mairie.versements.index') }}">Historique</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Liste des Commerçants -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('mairie.commerce.index') }}">
                    <i class="typcn typcn-briefcase menu-icon"></i>
                    <span class="menu-title">Liste des Commerçants</span>
                </a>
            </li>
        </ul>

        <!-- Déconnexion -->
        <div class="mt-5 mb-3 px-3">
            <form method="POST" action="{{ route('mairie.logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>


