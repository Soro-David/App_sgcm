<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="d-flex flex-column justify-content-between h-100">
    <ul class="nav flex-column">

        {{-- Tableau de bord --}}
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="typcn typcn-device-desktop menu-icon"></i>
                <span class="menu-title">Tableau de bord</span>
            </a>
        </li>

        {{-- Gestion des Mairies --}}
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" 
               data-bs-toggle="collapse" 
               href="#menu-mairies" 
               aria-expanded="false" 
               aria-controls="menu-mairies">
                <div>
                    <i class="typcn typcn-group menu-icon"></i>
                    <span class="menu-title">Gestion des Mairies</span>
                </div>
                <i class="menu-arrow fas fa-angle-down rotate-icon"></i>
            </a>
            <div class="collapse" id="menu-mairies">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.mairies.index') }}">Liste des mairies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Ajouter un personnel</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Gestion des Taxes --}}
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" 
               data-bs-toggle="collapse" 
               href="#menu-taxes" 
               aria-expanded="false" 
               aria-controls="menu-taxes">
                <div>
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Gestion des Taxes</span>
                </div>
                <i class="menu-arrow fas fa-angle-down rotate-icon"></i>
            </a>
            <div class="collapse" id="menu-taxes">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.taxes.index') }}">Liste des taxes</a>
                    </li>
                </ul>
            </div>
        </li>

    </ul>

    <!-- Bouton Déconnexion -->
    <div class="mt-5 mb-3 px-3">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger w-100">
          <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
        </button>
      </form>
    </div>
  </div>
</nav>
