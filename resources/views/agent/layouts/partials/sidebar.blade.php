@php
    $agent = Auth::guard('agent')->user();
    $mairieLogo = null;
    if ($agent && isset($agent->mairie_ref)) {
        $mairieRecord = \App\Models\Mairie::where('mairie_ref', $agent->mairie_ref)->first();
        $mairieLogo = $mairieRecord ? $mairieRecord->logo : null;
    }
@endphp

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="d-flex flex-column justify-content-between h-100">
        <ul class="nav flex-column">
            <!-- Logo mobile/tablette sur le sidebar -->
            <li class="nav-item d-lg-none sidebar-logo-container bg-agent">
                <img src="{{ asset('assets/images/logo_navbar.png') }}" alt="Logo SGTC">
            </li>
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
                        <i class="fa-solid fa-id-card-clip menu-icon"></i>
                        <span class="menu-title">Mon compte</span>
                    </a>
                </li>
            @endif
        </ul>

        <!-- Zone bas de Sidebar : Logo Mairie + Déconnexion -->
        <div class="mt-auto mb-3 px-2 text-center w-100">
            @if ($mairieLogo)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $mairieLogo) }}" alt="Logo Mairie"
                        style="max-height: 120px; width: auto; object-fit: contain;">
                </div>
            @endif

            <!-- Bouton Déconnexion en bas -->
            {{-- <div class="logout-container">
                <form method="POST" action="{{ route('agent.logout') }}">
                    @csrf
                    <button type="submit"
                        class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center logout-btn">
                        <i class="fas fa-sign-out-alt logout-icon"></i>
                        <span class="logout-text ms-2">Déconnexion</span>
                    </button>
                </form>
            </div> --}}
        </div>
    </div>
</nav>
