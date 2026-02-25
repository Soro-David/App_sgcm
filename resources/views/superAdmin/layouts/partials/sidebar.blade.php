<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="d-flex flex-column justify-content-between h-100">
        <ul class="nav flex-column">

            {{-- Tableau de bord --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.dashboard') }}">
                    <i class="typcn typcn-device-desktop menu-icon"></i>
                    <span class="menu-title">Tableau de bord</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.mairies.index') }}">
                    <i class="typcn typcn-group menu-icon"></i>
                    <span class="menu-title">Gestion des Mairies</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.bilan') }}">
                    <i class="typcn typcn-chart-line menu-icon"></i>
                    <span class="menu-title">Bilan</span>
                </a>
            </li>

            {{-- <li class="nav-item">
                <a class="nav-link" href="{{ route('superadmin.profile.show') }}">
                    <i class="fa-solid fa-user-circle menu-icon"></i>
                    <span class="menu-title">Mon profil</span>
                </a>
            </li> --}}

        </ul>

        <!-- Bouton Déconnexion -->
        <div class="mt-auto mb-3 px-2 logout-container">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center logout-btn">
                    <i class="fas fa-sign-out-alt logout-icon"></i>
                    <span class="logout-text ms-2">Déconnexion</span>
                </button>
            </form>
        </div>
    </div>
</nav>
