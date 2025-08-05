@php
    $agent = Auth::guard('agent')->user();
@endphp

<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="d-flex flex-column justify-content-between h-100">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('agent.dashboard') }}">
          <i class="typcn typcn-device-desktop menu-icon"></i>
          <span class="menu-title">Tableau de bord</span>
        </a>
      </li>

      {{-- Pour les agents de type "agent de mairie" --}}
      @if($agent && $agent->type === 'recenssement')
          {{-- <li class="nav-item">
            <a class="nav-link" href="{{ route('agent.commerce.index') }}">
              <i class="fa-solid fa-users"></i>
              <span class="menu-title">Ajouter un commerçant</span>
            </a>
          </li> --}}
          <li class="nav-item">
                <a class="nav-link" href="{{ route('agent.commerce.create') }}">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Ajouter un contribuable</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('agent.commerce.index') }}">
                    <i class="typcn typcn-document-text menu-icon"></i>
                    <span class="menu-title">Liste des contribuable</span>
                </a>
            </li>
      @endif

      {{-- Pour les agents de type "recouvrement" --}}
      @if($agent && $agent->type === 'recouvrement')
            <li class="nav-item">
              <a class="nav-link" href="">
                <i class="typcn typcn-group-outline menu-icon"></i>
                <span class="menu-title">Recouvrement</span>
              </a>
            </li>
      @endif

      {{-- Pour les agents de type "admin" --}}
      @if($agent && $agent->type === 'admin')
      <li class="nav-item">
        <a class="nav-link" href="">
          <i class="typcn typcn-group-outline menu-icon"></i>
          <span class="menu-title">Gestion des agents</span>
        </a>
      </li>
      @endif
    </ul>

    <!-- Bouton Déconnexion en bas -->
    <div class="mt-5 mb-3 px-3">
      <form method="POST" action="{{ route('agent.logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger w-100">
          <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
        </button>
      </form>
    </div>
  </div>
</nav>
