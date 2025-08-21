@php
    // Je récupère l'agent connecté, comme vous le faites dans la sidebar.
    $agent = Auth::guard('mairie')->user();
@endphp

<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand brand-logo" href="../index.html"><img src="../../../assets/images/logo_sgcm.png" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="../index.html"><img src="../../../assets/images/logo-mini.svg" alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="typcn typcn-th-menu"></span>
          </button>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav me-lg-2">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="../../../assets/images/faces/face5.jpg" alt="profile"/>
              <span class="nav-profile-name">
                  {{-- Utilisation de la variable $agent pour être cohérent --}}
                  {{ $agent->name ?? 'Mairie inconnue' }}
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="typcn typcn-cog-outline text-primary"></i>
                Settings
              </a>
              {{-- Le formulaire de déconnexion peut être simplifié et la méthode GET n'est pas sécurisée pour une déconnexion --}}
              <form id="logout-form" action="{{ route('logout.mairie') }}" method="POST" style="display: none;">
                  @csrf
              </form>
              <!-- Dans votre dropdown : -->
              <a class="dropdown-item" href="#"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="typcn typcn-eject text-primary"></i>
                  Déconnexion
              </a>
            </div>
          </li>
          {{-- <li class="nav-item nav-user-status dropdown">
              <p class="mb-0">Last login was 23 hours ago.</p>
          </li> --}}
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-date dropdown">
            <a class="nav-link d-flex justify-content-center align-items-center" href="javascript:;">
              <h6 class="date mb-0" id="real-time-clock">
                  {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY, HH:mm') }}
              </h6>
              <i class="typcn typcn-calendar"></i>
            </a>
          </li>
          {{-- Adaptation en fonction des rôles définis dans la sidebar --}}
          @if($agent && ($agent->role === 'admin' || $agent->role === 'financié'))
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="#" data-bs-toggle="dropdown">
              <i class="typcn typcn-mail mx-0"></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
              <p class="mb-0 fw-normal float-start dropdown-header">Messages</p>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <img src="../../../assets/images/faces/face4.jpg" alt="image" class="profile-pic">
                </div>
                <div class="preview-item-content flex-grow">
                  <h6 class="preview-subject ellipsis fw-normal">David Grey
                  </h6>
                  <p class="fw-light small-text text-muted mb-0">
                    The meeting is cancelled
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <img src="../../../assets/images/faces/face2.jpg" alt="image" class="profile-pic">
                </div>
                <div class="preview-item-content flex-grow">
                  <h6 class="preview-subject ellipsis fw-normal">Tim Cook
                  </h6>
                  <p class="fw-light small-text text-muted mb-0">
                    New product launch
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                    <img src="../../../assets/images/faces/face3.jpg" alt="image" class="profile-pic">
                </div>
                <div class="preview-item-content flex-grow">
                  <h6 class="preview-subject ellipsis fw-normal"> Johnson
                  </h6>
                  <p class="fw-light small-text text-muted mb-0">
                    Upcoming board meeting
                  </p>
                </div>
              </a>
            </div>
          </li>
          <li class="nav-item dropdown me-0">
            <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
              <i class="typcn typcn-bell mx-0"></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <p class="mb-0 fw-normal float-start dropdown-header">Notifications</p>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-success">
                    <i class="typcn typcn-info mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject fw-normal">Application Error</h6>
                  <p class="fw-light small-text mb-0 text-muted">
                    Just now
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-warning">
                    <i class="typcn typcn-cog-outline mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject fw-normal">Settings</h6>
                  <p class="fw-light small-text mb-0 text-muted">
                    Private message
                  </p>
                </div>
              </a>
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-info">
                    <i class="typcn typcn-user mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject fw-normal">New user registration</h6>
                  <p class="fw-light small-text mb-0 text-muted">
                    2 days ago
                  </p>
                </div>
              </a>
            </div>
          </li>
          @endif
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="typcn typcn-th-menu"></span>
        </button>
      </div>
</nav>


{{-- Votre code Sidebar (CORRIGÉ) --}}
@php
    $agent = Auth::guard('mairie')->user();
@endphp
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="d-flex flex-column justify-content-between h-100">
        <ul class="nav flex-column">
            <!-- Tableau de bord -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('mairie.dashboard.index') }}">
                    <i class="typcn typcn-device-desktop menu-icon"></i>
                    <span class="menu-title">Tableau de bord</span>
                </a>
            </li>

            {{-- Pour les admins de la mairie --}}
            @if($agent && $agent->role === 'admin')
                {{-- <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#collapsePersonnel" role="button" aria-expanded="false" aria-controls="collapsePersonnel">
                        <div>
                            <i class="typcn typcn-user-add-outline menu-icon"></i>
                            <span class="menu-title">Gestion du personnel</span>
                        </div>
                        <i class="fas fa-angle-down rotate-icon menu-arrow"></i>
                    </a>
                    <div class="collapse" id="collapsePersonnel">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.agents.create') }}">Ajouter</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.agents.index') }}">Liste</a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
                <li class="nav-item nav-category">Gestion personnel</li>

                <li class="nav-item {{ request()->routeIs('mairie.agents.create') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.agents.create') }}">
                        <i class="typcn typcn-document-add menu-icon"></i>
                        <span class="menu-title">Ajouter un personnel</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('mairie.agents.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.agents.index') }}">
                        <i class="typcn typcn-th-list-outline menu-icon"></i>
                        <span class="menu-title">Liste du personnel</span>
                    </a>
                </li>


                <li class="nav-item nav-category">Gestion des Agents</li>

                <li class="nav-item {{ request()->routeIs('mairie.agents.add_agent') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.agents.add_agent') }}">
                        <i class="typcn typcn-document-add menu-icon"></i>
                        <span class="menu-title">Ajouter un agent</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('mairie.agents.list_agent') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.agents.list_agent') }}">
                        <i class="typcn typcn-th-list-outline menu-icon"></i>
                        <span class="menu-title">Liste des agents</span>
                    </a>
                </li>
{{-- 
                <li class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#collapseAgentsSpecifiques" role="button" aria-expanded="false" aria-controls="collapseAgentsSpecifiques">
                        <div>
                            <i class="typcn typcn-user-add-outline menu-icon"></i>
                            <span class="menu-title">Gestion des Agents</span>
                        </div>
                        <i class="fas fa-angle-down rotate-icon menu-arrow"></i>
                    </a>
                    <div class="collapse" id="collapseAgentsSpecifiques">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.agents.add_agent') }}">Ajouter</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('mairie.agents.list_agent') }}">Liste</a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
            @endif

            {{-- Pour le rôle "financé" --}}
            @if($agent && $agent->role === 'financié')

                <li class="nav-item {{ request()->routeIs('mairie.secteurs.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.secteurs.index') }}">
                        <i class="typcn typcn-map menu-icon"></i>
                        <span class="menu-title">Les Secteurs</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('mairie.encaissement.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.encaissement.index') }}">
                        <i class="typcn typcn-archive menu-icon"></i>
                        <span class="menu-title">Liste des encaissements</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('mairie.paiement.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.paiement.index') }}">
                        <i class="typcn typcn-clipboard menu-icon"></i>
                        <span class="menu-title">Liste de payements</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('mairie.agents.programme_agent') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.agents.programme_agent') }}">
                        <i class="typcn typcn-group-outline menu-icon"></i>
                        <span class="menu-title">Programmer un agent</span>
                    </a>
                </li>

                <li class="nav-item nav-category">Gestion des taxes</li>

                <li class="nav-item {{ request()->routeIs('mairie.taxe.create') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.taxe.create') }}">
                        <i class="typcn typcn-plus-outline menu-icon"></i>
                        <span class="menu-title">Ajouter une taxe</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('mairie.taxe.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.taxe.index') }}">
                        <i class="typcn typcn-th-list-outline menu-icon"></i>
                        <span class="menu-title">Liste des taxes</span>
                    </a>
                </li>

                <li class="nav-item nav-category">Versements</li>

                <li class="nav-item {{ request()->routeIs('mairie.versements.create') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.versements.create') }}">
                        <i class="typcn typcn-credit-card menu-icon"></i>
                        <span class="menu-title">Faire un versement</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('mairie.versements.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.versements.index') }}">
                        <i class="typcn typcn-watch menu-icon"></i>
                        <span class="menu-title">Historique des versements</span>
                    </a>
                </li>

                <li class="nav-item nav-category">Gestion Comptable</li>

                <li class="nav-item {{ request()->routeIs('mairie.comptabilite.journal_recette') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.comptabilite.journal_recette') }}">
                        <i class="typcn typcn-document-add menu-icon"></i>
                        <span class="menu-title">Journal de recette</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('mairie.comptabilite.journal_depense') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('mairie.comptabilite.journal_depense') }}">
                        <i class="typcn typcn-document-delete menu-icon"></i>
                        <span class="menu-title">Journal de dépense</span>
                    </a>
                </li>

            @endif

            {{-- Pour les caissiers --}}
            @if($agent && $agent->role === 'caisse')
                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="typcn typcn-document-text menu-icon"></i>
                        <span class="menu-title">Ajouter un contribuable</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">
                        <i class="typcn typcn-document-text menu-icon"></i>
                        <span class="menu-title">Liste des contribuable</span>
                    </a>
                </li>
            @endif

            <!-- Liste des Commerçants -->
            <li class="nav-item nav-category">Contribuable</li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('mairie.commerce.index') }}">
                    <i class="typcn typcn-briefcase menu-icon"></i>
                    <span class="menu-title">Liste des contribuables</span>
                </a>
            </li>
        </ul>

        <!-- Déconnexion -->
        <div class="mt-auto mb-3 px-3">
            {{-- Utilisation de la même logique de formulaire de déconnexion que le header --}}
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
    document.addEventListener('DOMContentLoaded', function () {
    
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