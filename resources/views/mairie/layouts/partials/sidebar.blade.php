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
              <form id="logout-form" action="{{ route('logout.mairie') }}" method="GET" style="display: none;">
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
          <li class="nav-item nav-user-status dropdown">
              <p class="mb-0">Last login was 23 hours ago.</p>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-date dropdown">
            <a class="nav-link d-flex justify-content-center align-items-center" href="javascript:;">
              <h6 class="date mb-0">
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


{{-- Votre code Sidebar (INCHANGÉ) --}}
@php
    $agent = Auth::guard('mairie')->user();
    // dd($role);
@endphp
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
             {{-- Pour les admin de la mairie" --}}
    @if($agent && $agent->role === 'admin')
           <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#collapseAgents" role="button" aria-expanded="false" aria-controls="collapseAgents">
                    <div>
                        <i class="typcn typcn-user-add-outline menu-icon"></i>
                        <span class="menu-title">Gestion du personnel</span>
                    </div>
                    <i class="fas fa-angle-down rotate-icon menu-arrow"></i>
                </a>
                <div class="collapse" id="collapseAgents">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mairie.agents.create') }}">Ajouter </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mairie.agents.index') }}">Liste</a>
                        </li>
                    </ul>
                </div>
            </li>

      @endif


              {{-- Pour les admin de la mairie" --}}
    @if($agent && $agent->role === 'financié')
                  <!-- Mes secteurs -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('mairie.secteurs.index') }}">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Les Secteurs</span>
                </a>
            </li>
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
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#collapseAgents" role="button" aria-expanded="false" aria-controls="collapseAgents">
                    <div>
                        <i class="typcn typcn-user-add-outline menu-icon"></i>
                        <span class="menu-title">Gestion des taxes</span>
                    </div>
                    <i class="fas fa-angle-down rotate-icon menu-arrow"></i>
                </a>
                <div class="collapse" id="collapseAgents">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mairie.taxe.create') }}">Ajouter</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('mairie.taxe.index') }}">Liste</a>
                        </li>
                    </ul>
                </div>
            </li>
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

      @endif


              {{-- Pour les admin de la mairie" --}}
      @if($agent && $agent->role === 'caisiers')
          {{-- <li class="nav-item">
            <a class="nav-link" href="{{ route('agent.commerce.index') }}">
              <i class="fa-solid fa-users"></i>
              <span class="menu-title">Ajouter un commerçant</span>
            </a>
          </li> --}}
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