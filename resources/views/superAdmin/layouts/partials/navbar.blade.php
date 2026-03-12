@php
    $user = Auth::user();
    $roleLabel = 'Super Admin';
    $roleSubtitle = 'Administration Globale';
    $roleColor = '#854fc2'; // Mauve/Violet SuperAdmin
    $roleColor2 = '#ff4757'; // Rouge vif pour les notifications
    $roleIcon = 'fas fa-shield-alt';
@endphp

<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex align-items-center justify-content-center"
        style="background-color: {{ $roleColor }} !important; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100 px-3">
            <a class="navbar-brand d-flex justify-content-center align-items-center brand-logo"
                href="{{ route('superadmin.dashboard') }}">
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
            <h4 class="fw-bold mb-0" style="color: {{ $roleColor }}; font-size: 1.1rem;">{{ $user->name }}</h4>
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
                    <div class="profile-img-container" style="display: flex; align-items: center;">
                        <img src="{{ $user->photo_profil ? asset('storage/' . $user->photo_profil) : asset('assets/images/logo_kks_technologies.png') }}" alt="Logo Mairie"
                            style="max-height: 50px; height: 60px; width: 60px; object-fit: contain; padding: 2px; border: 1px solid #eee; border-radius: 8px;">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown shadow-lg"
                    aria-labelledby="profileDropdown"
                    style="border-radius: 12px; border: none; padding: 10px; min-width: 200px;">
                    <div class="dropdown-item-text border-bottom mb-2 pb-2">
                        <p class="fw-bold mb-0">{{ $user->name }}</p>
                        <p class="text-muted mb-0 small">{{ $user->email }}</p>
                    </div>
                    <a class="dropdown-item py-2" href="{{ route('superadmin.profile.show') }}">
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
