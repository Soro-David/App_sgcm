@php
    $user = Auth::guard('agent')->user();
    $roleLabel = 'Agent';
    $roleSubtitle = 'Système Gestion des taxes';
    $roleColor = '#219c62'; // Vert par défaut pour les agents
    $roleIcon = 'fas fa-user-tie';

    if ($user) {
        $roleLabel = 'Agent ' . ucfirst($user->type);
        if ($user->type === 'recouvrement') {
            $roleSubtitle = 'Collecte & Recouvrement';
            $roleColor = '#f39c12'; // Orange
            $roleIcon = 'fas fa-hand-holding-usd';
        } elseif ($user->type === 'recensement') {
            $roleSubtitle = 'Recensement Municipal';
            $roleColor = '#3498db'; // Bleu
            $roleIcon = 'fas fa-clipboard-list';
        } elseif ($user->type === 'caissier') {
            $roleSubtitle = 'Encaissements & Caisse';
            $roleColor = '#2ecc71'; // Vert
            $roleIcon = 'fas fa-cash-register';
        }
    }
@endphp

<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row" id="agent-navbar">
    <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
            <a class="navbar-brand brand-logo d-flex flex-column align-items-start" href="{{ route('agent.dashboard') }}"
                style="line-height: 1; text-decoration: none;">
                <span class="fw-bold text-white text-uppercase"
                    style="font-size: 2.2rem; letter-spacing: 4px; font-family: 'Outfit', sans-serif;">ANATH</span>
            </a>
            <a class="navbar-brand brand-logo-mini d-none d-lg-block" href="{{ route('agent.dashboard') }}">
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
                            <h6 class="preview-subject fw-normal">Session Active</h6>
                            <p class="fw-light small-text mb-0 text-muted">
                                Vous êtes connecté
                            </p>
                        </div>
                    </a>
                </div>
            </li>
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                    <div class="profile-img-container" style="position: relative;">
                        <img src="{{ Auth::user()->photo ?? asset('images/default_avatar.jpg') }}" alt="Profile"
                            class="img-xs rounded-circle"
                            style="border: 2px solid {{ $roleColor }}; padding: 2px;" />
                        <span class="status-indicator"
                            style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background: #2ecc71; border-radius: 50%; border: 2px solid #fff;"></span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown shadow-sm"
                    aria-labelledby="profileDropdown" style="border-radius: 12px; border: none; padding: 10px;">
                    <a class="dropdown-item py-2" href="{{ route('agent.profile') }}">
                        <i class="typcn typcn-user text-primary me-2"></i>
                        Mon Profil
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item py-2" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="typcn typcn-power text-danger me-2"></i>
                        Déconnexion
                    </a>
                </div>
            </li>
            <form id="logout-form" action="{{ route('logout.agent') }}" method="GET" style="display: none;">
                @csrf
            </form>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="typcn typcn-th-menu"></span>
        </button>
    </div>
</nav>
