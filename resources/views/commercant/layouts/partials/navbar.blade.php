<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper d-flex justify-content-center">

        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
            {{-- <a class="navbar-brand brand-logo d-flex flex-column align-items-start" href="" style="line-height: 1;">
                <span class="fw-bold text-white text-uppercase"
                    style="font-size: 2.5rem; letter-spacing: 5px;">SGCTC</span>
                <span class="text-white" style="font-size: 0.6rem; font-weight: 400;"> Système de gestion et de collecte des taxes communales</span>
            </a> --}}

            <a class="navbar-brand d-flex flex-column align-items-center text-center"
                href="" style="line-height: 1; text-decoration: none;">

                <span class="fw-bold text-white text-uppercase"
                    style="font-size: 3.5rem; letter-spacing: 2px; font-family: 'Outfit', sans-serif;">
                    SGTC
                </span>

                <small class="text-white" style="font-size: 0.7rem; letter-spacing: -0.5px;">
                    Système de gestion des taxes communales
                </small>

            </a>
            <a class="navbar-brand brand-logo-mini d-none d-lg-block" href="">
                <span class="fw-bold text-white text-uppercase" style="font-size: 1.5rem;"></span>
            </a>
        </div>
    </div>

    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end" style="margin-left: 0px;">
        <h4 class="fw-bold mb-0 me-auto ms-3" style="color: #1000f7;">CONTRIBUABLE : {{ Auth::user()->nom }}</h4>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown me-0">
                <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center"
                    id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="fas fa-bell" style="color: #1000f7; font-size: 1.5rem;"></i>
                    <span class="count"></span>
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
                <a class="nav-link" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                    <img src="{{ Auth::user()->photo ?? asset('images/default_avatar.jpg') }}" alt="Profile"
                        class="img-xs rounded-circle" style="margin-top: -11px;" />
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="#">
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
