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
            @if ($agent && $agent->type === 'recensement')
                {{-- <li class="nav-item">
            <a class="nav-link" href="{{ route('agent.contribuable.index') }}">
              <i class="fa-solid fa-users"></i>
              <span class="menu-title">Liste des commerçants</span>
            </a>
          </li> --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('agent.contribuable.create') }}">
                        <i class="fa-solid fa-id-badge menu-icon"></i>
                        <span class="menu-title">Ajouter contribuable</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('agent.contribuable.index') }}">
                        <i class="fa-solid fa-list menu-icon"></i>
                        <span class="menu-title">Liste contribuable</span>
                    </a>
                </li>
            @endif

            {{-- Pour les agents de type "recouvrement" --}}
            @if ($agent && $agent->type === 'recouvrement')
                {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('agent.encaissement.create') }}">
                        <i class="typcn typcn-group-outline menu-icon"></i>
                        <span class="menu-title">Encaissement</span>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('agent.encaissement.index') }}">
                        <i class="fa-solid fa-list menu-icon"></i>
                        <span class="menu-title"> Liste contribuable</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2"
                        href="{{ route('agent.encaissement.history') }}">
                        <i class="fa-solid fa-list-check menu-icon"></i>
                        <span class="menu-title">Mes encaissements</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="{{ route('agent.profile') }}">
                        <i class="fa-solid fa-business-time menu-icon"></i>
                        <span class="menu-title">Mon compte</span>
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
