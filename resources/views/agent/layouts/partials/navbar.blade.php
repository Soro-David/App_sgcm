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
            $roleColor2 = '#e74c3c';
            $roleIcon = 'fas fa-hand-holding-usd';
        } elseif ($user->type === 'recensement') {
            $roleSubtitle = 'Recensement Municipal';
            $roleColor = '#3498db'; // Bleu
            $roleColor2 = '#e74c3c';
            $roleIcon = 'fas fa-clipboard-list';
        } elseif ($user->type === 'caissier') {
            $roleSubtitle = 'Encaissements & Caisse';
            $roleColor = '#2ecc71'; // Vert
            $roleColor2 = '#e74c3c';
            $roleIcon = 'fas fa-cash-register';
        }
    }
@endphp

<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row" id="agent-navbar">

    <div class="navbar-brand-wrapper d-flex align-items-center justify-content-center"
        style="border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100 px-3">
            <a class="navbar-brand d-flex justify-content-center align-items-center brand-logo"
                href="{{ route('agent.dashboard') }}">
                <img src="{{ asset('assets/images/logo_navbar.png') }}" alt="Logo SGTC">
            </a>
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <i class="fas fa-bars text-white"></i>
            </button>
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
                    <span class="count" style="background: {{ $roleColor2 }};"></span>
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
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" data-toggle="dropdown"
                    id="profileDropdown">
                    <div class="profile-img-container" style="position: relative;">
                        <img src="{{ $user->photo_profil ? asset('storage/' . $user->photo_profil) : asset('images/default_avatar.jpg') }}"
                            alt="Profile" class="img-xs rounded-circle"
                            style="border: 2px solid {{ $roleColor }}; padding: 2px;" />
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-end navbar-dropdown shadow-sm"
                    aria-labelledby="profileDropdown" style="border-radius: 12px; border: none; padding: 10px;">
                    <a class="dropdown-item py-2" href="{{ route('agent.profile.show') }}">
                        <i class="typcn typcn-user text-primary me-2"></i>
                        Mon Profil
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item py-2" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form-agent').submit();">
                        <i class="typcn typcn-power text-danger me-2"></i>
                        Déconnexion
                    </a>
                </div>
            </li>
            <form id="logout-form-agent" action="{{ route('logout.agent') }}" method="GET" style="display: none;">
                @csrf
            </form>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="typcn typcn-th-menu"></span>
        </button>
    </div>
</nav>
