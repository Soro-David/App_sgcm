<header class="navbar-wrapper">
    <div class="navbar-container">
        <div class="logo-wrapper">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logo_principale.png') }}" alt="SGTC Logo">
            </a>
        </div>

        <div class="menu-toggle" id="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </div>
 
        <div class="nav-content" id="nav-content">
            <nav class="nav-links">
                <a href="#hero">Accueil</a>
                {{-- <a href="#connexion">Connexions</a> --}}
                <a href="#a-propos">À propos</a>
                <a href="#caracteristiques">Caractéristiques</a>
                <a href="#partenaires">Partenaires</a>
                <a href="#contact">Contacts</a>
            </nav>

            <div class="nav-actions">
                <a href="#connexion" class="btn btn-primary">Se connecter</a>
                {{-- <a href="#" class="btn btn-secondary"
                    style="background-color: var(--secondary-orange); color: white;">Parcourir</a> --}}
            </div>
        </div>
    </div>
</header>
