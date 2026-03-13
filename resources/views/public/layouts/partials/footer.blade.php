<footer class="footer">
    <div class="container">
        <div class="footer-content">

            {{-- Colonne 1 : Logo + Description + Réseaux --}}
            <div class="footer-info">
                <div class="footer-logo">
                    <img src="{{ asset('assets/images/logo_navbar.png') }}" alt="SGTC Logo"
                        style="filter: brightness(0) invert(1); height: 80px; width: auto;">
                </div>
                <p>
                    SGTC est une plateforme numérique dédiée à la modernisation de la collecte des taxes locales. Elle
                    permet aux collectivités territoriales d'optimiser leurs recettes, d'améliorer la transparence
                    financière et de simplifier la gestion administrative.
                </p>
                <div class="social-links">
                    <a href="#" class="social-link" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link" title="Dribbble"><i class="fab fa-dribbble"></i></a>
                    <a href="#" class="social-link" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            {{-- Colonne 2 : Navigation --}}
            <div class="footer-links">
                <h3>Navigation</h3>
                <ul>
                    <li><a href="{{ route('accueil') }}#hero">Accueil</a></li>
                    <li><a href="{{ route('accueil') }}#connexion">Connexions</a></li>
                    <li><a href="{{ route('accueil') }}#services">Services</a></li>
                    <li><a href="{{ route('accueil') }}#caracteristiques">Caractéristiques</a></li>
                    <li><a href="{{ route('accueil') }}#a-propos">À propos</a></li>
                    <li><a href="{{ route('accueil') }}#contact">Contacts</a></li>
                </ul>
            </div>

            {{-- Colonne 3 : Solutions --}}
            <div class="footer-links">
                <h3>Solutions</h3>
                <ul>
                    <li><a href="{{ route('accueil') }}#connexion">Gestion des contribuables</a></li>
                    <li><a href="{{ route('accueil') }}#connexion">Suivi des paiements</a></li>
                    <li><a href="{{ route('accueil') }}#connexion">Gestion des agents</a></li>
                    <li><a href="{{ route('accueil') }}#caracteristiques">Dashboard analytique</a></li>
                    <li><a href="{{ route('accueil') }}#caracteristiques">Rapports de la régie</a></li>
                    <li><a href="{{ route('accueil') }}#caracteristiques">Sécurité & protection des données</a></li>
                </ul>
            </div>

            {{-- Colonne 4 : Informations légales --}}
            <div class="footer-links">
                <h3>Informations légales</h3>
                <ul>
                    {{-- <li><a href="{{ route('conditions-utilisation') }}">Conditions d'utilisation</a></li>
                    <li><a href="{{ route('politique-confidentialite') }}">Politique de confidentialité</a></li>
                    <li><a href="{{ route('mentions-legales') }}">Mentions légales</a></li>
                    <li><a href="{{ route('politique-confidentialite') }}">Protection des données</a></li>
                    <li><a href="{{ route('mentions-legales') }}">Conformité réglementaire</a></li>
                    <li><a href="{{ route('contact') }}">FAQ</a></li> --}}
                </ul>
            </div>

        </div>

        {{-- Barre de copyright --}}
        {{-- <div class="footer-bottom">
            <div class="footer-bottom-inner">
                <p>&copy; {{ date('Y') }} SGTC — Système de Gestion des Taxes Communales. Tous droits réservés.</p>
                <div class="footer-bottom-links">
                    <a href="{{ route('conditions-utilisation') }}">Conditions</a>
                    <a href="{{ route('politique-confidentialite') }}">Confidentialité</a>
                    <a href="{{ route('mentions-legales') }}">Mentions légales</a>
                </div>
            </div>
        </div> --}}
    </div>
</footer>
