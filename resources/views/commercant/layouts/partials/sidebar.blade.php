<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="d-flex flex-column justify-content-between h-100">

        <ul class="nav flex-column">

            <!-- Tableau de bord -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('commercant.dashboard') }}">
                    <i class="typcn typcn-device-desktop menu-icon"></i>
                    <span class="menu-title">Tableau de bord</span>
                </a>
            </li>

            <!-- Mes Taxes -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('commercant.payement.create') }}">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Effecturer un payement</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('commercant.payement.index') }}">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Liste des payements</span>
                </a>
            </li>

        </ul>

        <!-- Déconnexion -->
        <div class="mt-5 mb-3 px-3">
            <form method="POST" action="{{ route('logout.commercant') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>


