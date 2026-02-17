<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGTC - Plateforme Intégrée de Gestion Municipale</title>

    <!-- Meta Tags SEO -->
    <meta name="description"
        content="SGCM est une plateforme moderne de gestion des commerçants, des taxes et des collectes municipales pour une administration simplifiée et transparente.">
    <meta name="keywords" content="gestion municipale, taxes, commerçants, collectes, SGCM, mairie">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

    <style>
        /* Small inline style for initial load flash */
        [v-cloak] {
            display: none;
        }
    </style>
</head>

<body>
    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <nav>
        <div class="logo">
            <i class="fas fa-city me-2"></i> SGTC
        </div>
        {{-- <div>
            <a href="#portals" class="btn btn-primary">Accéder aux Portails</a>
        </div> --}}
    </nav>

    <div class="container text-center">
        <header>
            <h1>
                <span>Bienvenue sur SGTC</span>
                L'Administration Municipale<br>Réinventée
            </h1>
            <p class="hero-text">
                Une plateforme unifiée pour la gestion des commerçants, le suivi des recettes et l'optimisation des
                collectes territoriales. Simple, sécurisée et performante.
            </p>
        </header>

        <div id="portals" class="grid">
            <!-- Portail Mairie -->
            <div class="card mairie">
                <div class="icon">
                    <i class="fas fa-building-columns"></i>
                </div>
                <h2>Espace Mairie</h2>
                <p>Gestion administrative, suivi des taxes, programmation des agents et supervision des finances
                    communales.</p>
                <a href="{{ route('login.mairie') }}" class="btn">
                    Se connecter <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Portail Commerçants -->
            <div class="card commercant">
                <div class="icon">
                    <i class="fas fa-shop"></i>
                </div>
                <h2>Espace Commerçant</h2>
                <p>Accédez à votre compte, consultez vos taxes dues, effectuez vos paiements en toute sécurité et gérez
                    vos informations.</p>
                <a href="{{ route('login.commercant') }}" class="btn">
                    Se connecter <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Portail Agent -->
            <div class="card agent">
                <div class="icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2>Espace Agent</h2>
                <p>Recensement des contribuables, encaissement sur le terrain et suivi des activités quotidiennes de
                    collecte.</p>
                <a href="{{ route('login.agent') }}" class="btn">
                    Se connecter <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Portail SuperAdmin -->
            <div class="card admin">
                <div class="icon">
                    <i class="fas fa-user-gear"></i>
                </div>
                <h2>Super Admin</h2>
                <p>Administration globale du système, gestion des instances municipales et configuration des paramètres
                    avancés.</p>
                <a href="{{ route('login') }}" class="btn">
                    Administration <i class="fas fa-lock"></i>
                </a>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} SGCM - Système de Gestion des Commerçants et Collectes. Tous droits réservés.</p>
        <p class="mt-2" style="font-size: 0.8rem; opacity: 0.7;">Solution technologique pour une gouvernance locale
            transparente.</p>
    </footer>

    <!-- Subtle reveal animation script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s cubic-bezier(0.23, 1, 0.32, 1)';

                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 200 * index);
            });
        });
    </script>
</body>

</html>
