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
                $roleColor2 = '#e74c3c';
                $roleIcon = 'fas fa-user-cog';
            } elseif ($user->role === 'financiers') {
                $roleLabel = 'Admin de la régie';
                $roleSubtitle = 'Gestion des Finances';
                $roleColor = '#f39c12'; // Orange/Jaune
                $roleColor2 = '#e74c3c';
                $roleIcon = 'fas fa-file-invoice-dollar';
            } elseif ($user->role === 'caisié') {
                $roleLabel = 'Caissier';
                $roleSubtitle = 'Régie des Recettes';
                $roleColor = '#27ae60'; // Vert
                $roleColor2 = '#e74c3c';
                $roleIcon = 'fas fa-cash-register';
            }
        } elseif ($user instanceof \App\Models\Finance) {
            if ($user->role === 'admin') {
                $roleLabel = 'Admin de la régie';
                $roleSubtitle = 'Direction Financière';
                $roleColor = '#f39c12';
                $roleColor2 = '#e74c3c';
                $roleIcon = 'fas fa-chart-line';
            } elseif ($user->role === 'finance') {
                $roleLabel = 'Agent de la régie';
                $roleSubtitle = 'Trésorerie & Comptabilité';
                $roleColor = '#2980b9'; // Bleu
                $roleColor2 = '#e74c3c';
                $roleIcon = 'fas fa-user-check';
            } elseif ($user->role === 'caissier') {
                $roleLabel = 'Caissier';
                $roleSubtitle = 'Service de Caisse';
                $roleColor = '#27ae60';
                $roleColor2 = '#e74c3c';
                $roleIcon = 'fas fa-money-bill-wave';
            }
        } elseif ($user instanceof \App\Models\Financier) {
            $roleLabel = 'Responsable de la régie';
            $roleSubtitle = 'Direction Financière';
            $roleColor = '#f39c12';
            $roleColor2 = '#e74c3c';
            $roleIcon = 'fas fa-chart-line';
        } elseif ($user instanceof \App\Models\Agent) {
            $roleLabel = 'Agent ' . ucfirst($user->type);
            $roleSubtitle = 'Opérations de Terrain';
            $roleColor = '#8e44ad'; // Violet
            $roleColor2 = '#e74c3c';
            $roleIcon = 'fas fa-walking';
        }
    }

    // Logo Mairie
    $mairieLogo = null;
    if ($user) {
        if ($user instanceof \App\Models\Mairie) {
            $mairieLogo = $user->logo;
        } elseif (isset($user->mairie_ref)) {
            $mairieRecord = \App\Models\Mairie::where('mairie_ref', $user->mairie_ref)->first();
            $mairieLogo = $mairieRecord ? $mairieRecord->logo : null;
        }
    }
@endphp

<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row" id="main-navbar">
    <div class="navbar-brand-wrapper d-flex align-items-center justify-content-center"
        style="border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100 px-3">
            <a class="navbar-brand d-flex justify-content-center align-items-center brand-logo"
                href="{{ route('mairie.dashboard.index') }}">
                <img src="{{ asset('assets/images/logo_navbar.png') }}" alt="Logo SGTC"
                    style="max-height: 180px !important;">
            </a>
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <i class="fas fa-bars text-white"></i>
            </button>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end"
        style="margin-left: 0px; box-shadow: 0 2px 15px rgba(0,0,0,0.1);">

        <div class="d-flex flex-column ms-3 me-auto">
            <h4 class="fw-bold mb-0" style="color: #2c3e50; font-size: 1.1rem;">{{ strtoupper(Auth::user()->name) }}
            </h4>
            <span class="badge rounded-pill d-flex align-items-center"
                style="background-color: {{ $roleColor }}; font-size: 0.7rem; width: fit-content; margin-top: 4px; padding: 4px 10px;">
                <i class="{{ $roleIcon }} me-1" style="font-size: 0.8rem;"></i> {{ $roleLabel }}
            </span>
        </div>
        <ul class="navbar-nav navbar-nav-right">

            <li class="nav-item dropdown me-2">
                <a class="nav count-indicator d-flex align-items-center justify-content-center"
                    id="notificationDropdown" href="#" data-bs-toggle="dropdown"
                    style="text-decoration: none !important; border: none !important; outline: none !important; box-shadow: none !important;">
                    <i class="fas fa-bell" style="color: {{ $roleColor }}; font-size: 1.8rem;"></i>
                    <span class="count" style="background: {{ $roleColor2 }}; border: none !important;"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list shadow-lg"
                    aria-labelledby="notificationDropdown" style="border-radius: 12px; border: none; width: 300px;">
                    <div class="dropdown-header d-flex align-items-center justify-content-between py-3">
                        <p class="mb-0 fw-bold" style="color: #2c3e50;">Notifications</p>
                        <span class="badge bg-soft-danger text-danger" style="font-size: 0.7rem;">Nouveau</span>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item py-3">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-success"
                                style="border-radius: 8px; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                        </div>
                        <div class="preview-item-content ms-2">
                            <h6 class="preview-subject fw-normal mb-1" style="font-size: 0.9rem;">Système opérationnel
                            </h6>
                            <p class="fw-light small-text mb-0 text-muted" style="font-size: 0.75rem;">
                                Tout fonctionne correctement
                            </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-center py-2" style="font-size: 0.8rem; color: {{ $roleColor }};">
                        Voir toutes les notifications
                    </a>
                </div>
            </li>

            <li class="nav-item nav-profile dropdown">
                <a class="nav d-flex align-items-center" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                    @if ($mairieLogo)
                        <div class="mairie-logo-nav" style="display: flex; align-items: center;">
                            <img src="{{ asset('storage/' . $mairieLogo) }}" alt="Logo Mairie"
                                style="max-height: 50px; height: 60px; width: 60px; object-fit: contain; padding: 2px; border: 1px solid #eee; border-radius: 8px;">
                        </div>
                    @else
                        <div class="profile-img-container" style="position: relative;">
                            <img src="{{ $user->photo_profil ? asset('storage/' . $user->photo_profil) : asset('images/default_avatar.jpg') }}"
                                alt="Profile" class="img-xs rounded-circle"
                                style="border: 2px solid {{ $roleColor }}; padding: 2px; width: 40px; height: 40px;" />
                            <span class="availability-status online"
                                style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background: #27ae60; border: 2px solid white; border-radius: 50%;"></span>
                        </div>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown shadow-lg"
                    aria-labelledby="profileDropdown"
                    style="border-radius: 12px; border: none; padding: 10px; min-width: 200px;">
                    <div class="dropdown-item-text border-bottom mb-2 pb-2">
                        <p class="fw-bold mb-0">{{ Auth::user()->name }}</p>
                        <p class="text-muted mb-0 small">{{ $roleLabel }}</p>
                    </div>
                    <a class="dropdown-item py-2" href="{{ route('mairie.profile.show') }}">
                        <i class="fas fa-user-circle text-primary me-2"></i>
                        Mon Profil
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item py-2" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-power-off text-danger me-2"></i>
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
