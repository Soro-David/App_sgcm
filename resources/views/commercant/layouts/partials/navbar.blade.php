<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex align-items-center justify-content-center"
        style="background-color: #1000f7 !important; border-bottom: 1px solid rgba(255,255,255,0.1);">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100 px-3">
            <a class="navbar-brand d-flex justify-content-center align-items-center brand-logo"
                href="{{ route('commercant.dashboard') }}">
                <img src="{{ asset('assets/images/logo_navbar.png') }}" alt="Logo SGTC"
                    style="max-height: 180px !important;">
            </a>
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <i class="fas fa-bars text-white"></i>
            </button>
        </div>
    </div>

    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end" style="margin-left: 0px;">
        <h4 class="fw-bold mb-0 me-auto ms-3" style="color: #1000f7;">CONTRIBUABLE : {{ strtoupper(Auth::user()->nom) }}
        </h4>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown me-0">
                <a class="nav me-2 count-indicator d-flex align-items-center justify-content-center"
                    id="notificationDropdown" href="#" data-bs-toggle="dropdown"
                    style="text-decoration: none !important; border: none !important; outline: none !important; box-shadow: none !important;">
                    <i class="fas fa-bell" style="color: #1000f7; font-size: 1.8rem;"></i>
                    <span class="count" style="background: #e8030fff; border: none !important;"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                    aria-labelledby="notificationDropdown">
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
            <li class="nav-item nav-profile dropdown">
                <a class="nav me-2" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                    <img src="{{ Auth::user()->photo_profil ? asset('storage/' . Auth::user()->photo_profil) : asset('images/default_avatar.jpg') }}"
                        alt="Profile" class="img-xs"
                        style="max-height: 50px; height: 60px; width: 60px; object-fit: contain; padding: 2px; border: 1px solid #eee; border-radius: 8px;" />
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="{{ route('commercant.profile.show') }}">
                        <i class="typcn typcn-cog-outline text-primary"></i>
                        Profil
                    </a>
                    <a class="dropdown-item" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="typcn typcn-eject text-primary"></i>
                        Déconnexion
                    </a>
                </div>
            </li>
            <form id="logout-form" action="{{ route('logout.commercant') }}" method="GET" style="display: none;">
                @csrf
            </form>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="typcn typcn-th-menu"></span>
        </button>
    </div>
</nav>
