@php
    $user =
        Auth::guard('agent')->user() ?:
        Auth::guard('financier')->user() ?:
        Auth::guard('finance')->user() ?:
        Auth::guard('mairie')->user();
    $roleLabel = 'Utilisateur';
    $roleSubtitle = 'Système Gestion des taxes';
    $roleColor = '#ff7b0f'; // Orange par défaut
    $roleIcon = 'fas fa-user-shield';

    if ($user) {
        if ($user instanceof \App\Models\Mairie) {
            if ($user->role === 'admin') {
                $roleLabel = 'Admin Mairie';
                $roleSubtitle = 'Administration Communale';
                $roleColor = '#e74c3c'; // Rouge
                $roleIcon = 'fas fa-user-cog';
            } elseif ($user->role === 'financiers') {
                $roleLabel = 'Admin Financier';
                $roleSubtitle = 'Gestion des Finances';
                $roleColor = '#f39c12'; // Orange/Jaune
                $roleIcon = 'fas fa-file-invoice-dollar';
            } elseif ($user->role === 'caisié') {
                $roleLabel = 'Caissier';
                $roleSubtitle = 'Régie des Recettes';
                $roleColor = '#27ae60'; // Vert
                $roleIcon = 'fas fa-cash-register';
            }
        } elseif ($user instanceof \App\Models\Finance) {
            if ($user->role === 'admin') {
                $roleLabel = 'Admin Financier';
                $roleSubtitle = 'Direction Financière';
                $roleColor = '#f39c12';
                $roleIcon = 'fas fa-chart-line';
            } elseif ($user->role === 'finance') {
                $roleLabel = 'Agent Financier';
                $roleSubtitle = 'Trésorerie & Comptabilité';
                $roleColor = '#2980b9'; // Bleu
                $roleIcon = 'fas fa-user-check';
            } elseif ($user->role === 'caissier') {
                $roleLabel = 'Caissier';
                $roleSubtitle = 'Service de Caisse';
                $roleColor = '#27ae60';
                $roleIcon = 'fas fa-money-bill-wave';
            }
        } elseif ($user instanceof \App\Models\Financier) {
            $roleLabel = 'Responsable financier';
            $roleSubtitle = 'Direction Financière';
            $roleColor = '#f39c12';
            $roleIcon = 'fas fa-chart-line';
        } elseif ($user instanceof \App\Models\Agent) {
            $roleLabel = 'Agent ' . ucfirst($user->type);
            $roleSubtitle = 'Opérations de Terrain';
            $roleColor = '#8e44ad'; // Violet
            $roleIcon = 'fas fa-walking';
        }
    }
@endphp

<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row" id="main-navbar">
    <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-center align-items-center w-100">

            <a class="navbar-brand d-flex flex-column align-items-center text-center"
                href="{{ route('mairie.dashboard.index') }}" style="line-height: 1; text-decoration: none;">

                <span class="fw-bold text-white text-uppercase"
                    style="font-size: 2.9rem; letter-spacing: 1px; font-family: 'Outfit', sans-serif;">
                    SGTC
                </span>

                <small class="text-white" style="font-size: 0.7rem; letter-spacing: -0.5px;">
                    Système de gestion des taxes communales
                </small>

            </a>

        </div>

    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end"
        style="margin-left: 0px; box-shadow: 0 2px 15px rgba(0,0,0,0.1);">
        <div class="d-flex flex-column ms-3 me-auto">
            <h4 class="fw-bold mb-0" style="color: #2c3e50; font-size: 1.1rem;">{{ Auth::user()->name }}</h4>
            <span class="badge rounded-pill d-flex align-items-center"
                style="background-color: {{ $roleColor }}; font-size: 0.7rem; width: fit-content; margin-top: 4px; padding: 4px 10px;">
                <i class="{{ $roleIcon }} me-1" style="font-size: 0.8rem;"></i> {{ $roleLabel }}
            </span>
        </div>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown me-0">
                <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center"
                    id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="fas fa-bell" style="color: {{ $roleColor }}; font-size: 1.4rem;"></i>
                    <span class="count" style="background: {{ $roleColor }};"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list shadow-sm"
                    aria-labelledby="notificationDropdown" style="border-radius: 12px; border: none;">
                    <p class="mb-0 fw-bold float-start dropdown-header" style="color: #2c3e50;">Notifications</p>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-success" style="border-radius: 8px;">
                                <i class="typcn typcn-info mx-0"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject fw-normal">Système opérationnel</h6>
                            <p class="fw-light small-text mb-0 text-muted">
                                Tout fonctionne correctement
                            </p>
                        </div>
                    </a>
                </div>
            </li>
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                    <div class="profile-img-container" style="position: relative;">
                        <img src="{{ Auth::user()->photo ?? asset('images/default_avatar.jpg') }}" alt="Profile"
                            class="img-xs rounded-circle" />


                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown shadow-sm"
                    aria-labelledby="profileDropdown" style="border-radius: 12px; border: none; padding: 10px;">
                    <a class="dropdown-item py-2" href="#">
                        <i class="typcn typcn-user text-primary me-2"></i>
                        Mon Profil
                    </a>
                    <a class="dropdown-item py-2" href="#">
                        <i class="typcn typcn-cog-outline text-primary me-2"></i>
                        Paramètres
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item py-2" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="typcn typcn-power text-danger me-2"></i>
                        Déconnexion
                    </a>
                </div>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
                @csrf
            </form>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="typcn typcn-th-menu"></span>
        </button>
    </div>
</nav>
