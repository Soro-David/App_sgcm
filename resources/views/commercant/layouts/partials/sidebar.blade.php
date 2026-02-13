<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="d-flex flex-column justify-content-between h-100">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('commercant.dashboard') }}">
                    <i class="typcn typcn-device-desktop menu-icon"></i>
                    <span class="menu-title">Tableau de bord</span>
                </a>
            </li>

            <!-- Mes Taxes -->
            <li class="nav-item {{ Request::routeIs('commercant.virtual_card') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('commercant.virtual_card') }}">
                    <i class="fa-solid fa-id-card menu-icon"></i>
                    <span class="menu-title">Ma carte virtuelle</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('commercant.payement.create') }}">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Effectuer paiement</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('commercant.payement.index') }}">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Mes paiements</span>
                </a>
            </li>

            <!-- Recharge -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('commercant.recharge.index') }}">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Mon Compte</span>
                </a>
            </li>

            <!-- Déconnexion -->
            <div class="mt-5 mb-3 px-3">
                <form method="POST" action="{{ route('logout.commercant') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100" style="margin-top: auto !important;">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </button>
                </form>
            </div>
        </ul>
    </div>
</nav>
