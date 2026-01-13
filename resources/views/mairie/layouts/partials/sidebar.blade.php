@php
    // Je récupère l'agent connecté, comme vous le faites dans la sidebar.
$agent = Auth::guard('mairie')->user();
@endphp

{{-- Votre code Sidebar (CORRIGÉ) --}}

<nav class="sidebar sidebar-offcanvas " id="sidebar">
    <div class="d-flex flex-column justify-content-between h-10">
        <ul class="nav flex-column">
            <!-- Tableau de bord -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('mairie.dashboard.index') }}">
                    <i class="fas fa-home menu-icon me-2"></i>
                    <span class="menu-title">Tableau de bord</span>
                </a>
            </li>

            {{-- Pour les admins de la mairie --}}
            @if ($agent && $agent->role === 'admin')
                {{-- <li class="nav-item nav-category">Gestion personnel</li> --}}

                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                        href="#menu-personnel" aria-expanded="false" aria-controls="menu-personnel">
                        <div>
                            <i class="fas fa-user-cog menu-icon"></i>
                            <span class="menu-title">Gestion personnel</span>
                        </div>
                        <i class="menu-arrow fas fa-angle-down rotate-icon"></i>
                    </a>
                    <div class="collapse" id="menu-personnel">
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

                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                        href="#menu-agents" aria-expanded="false" aria-controls="menu-agents">
                        <div>
                            <i class="fas fa-users menu-icon"></i>
                            <span class="menu-title">Gestion agents</span>
                        </div>
                        <i class="menu-arrow fas fa-angle-down rotate-icon"></i>
                    </a>
                    <div class="collapse" id="menu-agents">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.agents.add_agent') }}">Ajouter</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.agents.list_agent') }}">Liste</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            {{-- Pour le rôle "financé" --}}
            @if ($agent && $agent->role === 'financié')
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                        href="#menu-agents-finance" aria-expanded="false" aria-controls="menu-agents-finance">
                        <div>
                            <i class="fas fa-users menu-icon"></i>
                            <span class="menu-title">Gestion agents</span>
                        </div>
                        <i class="menu-arrow fas fa-angle-down rotate-icon"></i>
                    </a>
                    <div class="collapse" id="menu-agents-finance">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.agents.add_agent') }}">Ajouter</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.agents.list_agent') }}">Liste</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.agents.programme_agent') }}">Programmer un
                                    agent</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('mairie.taxe.index') }}">
                        <i class="fas fa-file-invoice-dollar menu-icon"></i>
                        <span class="menu-title">Gestion des taxes</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('mairie.secteurs.index') }}">
                        <i class="fas fa-layer-group menu-icon"></i>
                        <span class="menu-title">Secteurs</span>
                    </a>
                </li>   

                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                        href="#menu-paiement" aria-expanded="false" aria-controls="menu-paiement">
                        <div>
                            <i class="fas fa-coins menu-icon"></i>
                            <span class="menu-title">Paiement</span>
                        </div>
                        <i class="menu-arrow fas fa-angle-down rotate-icon"></i>
                    </a>
                    <div class="collapse" id="menu-secteurs">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.encaissement.index') }}">Liste des
                                    encaissements</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.paiement.index') }}">Liste de
                                    payements</a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- <li class="nav-item nav-category">Gestion des taxes</li> --}}

                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                        href="#menu-versements" aria-expanded="false" aria-controls="menu-versements">
                        <div>
                            <i class="fas fa-receipt menu-icon"></i>
                            <span class="menu-title">Gestion Versements</span>
                        </div>
                        <i class="menu-arrow fas fa-angle-down rotate-icon"></i>
                    </a>
                    <div class="collapse" id="menu-versements">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.versements.create') }}">Faire
                                    versement</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.versements.index') }}">Historique
                                    versements</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.recette.index') }}">Journal de recette</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.depense.index') }}">Journal de dépense</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            {{-- Pour les caissiers --}}
            @if ($agent && $agent->role === 'caisse')
                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="fas fa-user-plus menu-icon"></i>
                        <span class="menu-title">Ajouter un contribuable</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="fas fa-list menu-icon"></i>
                        <span class="menu-title">Liste des contribuable</span>
                    </a>
                </li>
            @endif

            <!-- Liste des Commerçants -->
            {{-- <li class="nav-item nav-category">Contribuable</li> --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('mairie.commerce.index') }}">
                    <i class="fas fa-store menu-icon"></i>
                    <span class="menu-title">Liste des contribuables</span>
                </a>
            </li>
        </ul>

        <!-- Déconnexion -->
        <div class="mt-auto mb-3 px-3">
            {{-- Utilisation de la même logique de formulaire de déconnexion que  le header --}}
            <form id="logout-form-sidebar" method="POST" action="{{ route('mairie.logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const clockElement = document.getElementById('real-time-clock');

        if (clockElement) {

            function updateClock() {
                const now = new Date();

                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                };

                let formattedTime = now.toLocaleString('fr-FR', options);

                formattedTime = formattedTime.charAt(0).toUpperCase() + formattedTime.slice(1);

                clockElement.textContent = formattedTime;
            }

            updateClock();

            setInterval(updateClock, 1000);
        }
    });
</script>
