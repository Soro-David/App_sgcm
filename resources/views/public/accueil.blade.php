@extends('public.layouts.app')

@section('title', 'Accueil - SGTC')

@section('content')
    {{-- @include('public.layouts.partials.slider') --}}

@section('styles')
    <style>
        /* Sections Spécifiques */
        .bg-light-green {
            background-color: #daf5cd;
            /* Vert clair de la maquette */
        }

        .section-title-center {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title-center h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #000;
            position: relative;
            display: inline-block;
        }

        .section-title-center h2::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background-color: var(--secondary-orange);
            border-radius: 2px;
        }

        /* Layout Utilitaires */
        .section-white {
            background-color: #ffffff;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .about-layout {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            align-items: center;
            gap: 60px;
        }

        .about-img-col {
            min-width: 0;
        }

        .about-content-col {
            min-width: 0;
        }

        @media (max-width: 992px) {
            .cards-grid {
                grid-template-columns: 1fr;
                max-width: 420px;
                margin: 0 auto;
            }

            .about-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Cartes de Connexion */
        .connexion-card {
            border-radius: 20px;
            padding: 40px 30px;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .connexion-card:hover {
            transform: translateY(-10px);
        }

        .connexion-card.theme-white {
            background-color: #ffffff;
        }

        .connexion-card.theme-orange {
            background-color: var(--secondary-orange);
            color: #ffffff;
        }

        .connexion-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .theme-white .connexion-icon {
            background-color: rgba(255, 123, 15, 0.1);
            color: var(--secondary-orange);
        }

        .theme-orange .connexion-icon {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .connexion-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .connexion-card p {
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 30px;
            flex-grow: 1;
            opacity: 0.9;
        }

        .btn-connexion {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-weight: 700;
            text-align: center;
            transition: all 0.3s;
            border: none;
            display: inline-block;
        }

        .theme-white .btn-connexion {
            background-color: var(--secondary-orange);
            color: #ffffff;
        }

        .theme-white .btn-connexion:hover {
            background-color: #e66a00;
        }

        .theme-orange .btn-connexion {
            background-color: #ffffff;
            color: var(--secondary-orange);
        }

        .theme-orange .btn-connexion:hover {
            background-color: #f8f9fa;
        }

        /* À propos */
        .about-badge {
            display: inline-block;
            background-color: rgba(26, 154, 90, 0.15);
            color: var(--primary-green);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 20px;
            float: right;
        }

        .about-title {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.3;
            margin-bottom: 20px;
            clear: both;
        }

        .about-text {
            color: #666;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .stats-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .stat-box {
            text-align: center;
            padding: 5px;
            background-color: #f9f9f9;
            border-radius: 12px;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
        }

        .stat-number.orange {
            color: var(--secondary-orange);
        }

        .stat-number.green {
            color: var(--primary-green);
        }

        .stat-label {
            font-size: 0.8rem;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 5px;
        }

        .about-img-box {
            position: relative;
            height: 100%;
            min-height: 550px;
            border-radius: 20px;
            overflow: hidden;
        }

        .about-img-box img {
            width: 150%;
            height: 150%;
            object-fit: cover;
            position: absolute;
        }

        /* Caractéristiques */
        .feature-shadow-title {
            text-align: center;
            font-size: 4rem;
            font-weight: 900;
            color: #fff5ed;
            /* Orange très transparent */
            margin-bottom: -40px;
            letter-spacing: 5px;
            text-transform: uppercase;
        }

        .partenaires-shadow-title {
            text-align: center;
            font-size: 5rem;
            font-weight: 800;
            color: #e4f0e3ff;
            /* Orange très transparent */
            margin-bottom: -40px;
            letter-spacing: 5px;
            text-transform: uppercase;
        }

        .feature-main-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 900;
            position: relative;
            z-index: 2;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .feature-subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 60px;
        }

        .feature-tabs {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
        }

        .feature-tab {
            flex: 1;
            padding: 20px 24px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            font-weight: 600;
            border: 2px solid #eee;
            cursor: pointer;
            transition: all 0.35s ease;
            background: #fff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            border: 2px solid #219c62;

        }

        .feature-tab.active {
            border-color: var(--secondary-orange);
            background-color: #ff7b0f;
            box-shadow: 0 8px 24px rgba(230, 106, 0, 0.18);
            transform: translateY(-3px);
        }

        .feature-tab:hover:not(.active) {
            border-color: #ccc;
            background-color: #ff7b0f;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        /* Icône circulaire dans la card */
        .feature-card-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            flex-shrink: 0;
            background: linear-gradient(135deg, #1a9e5f, #2dcf7f);
        }

        .feature-card-icon:active {
            color: #ffffffff;
            /* background: #ffffffff; */
            background: linear-gradient(135deg, #ffffffff, #ffffffff);

        }

        .feature-card-icon:hover {
            color: #ffffffff;
            background: linear-gradient(135deg, #ffffffff, #ffffffff);

        }

        /* Texte dans la card */
        .feature-card-body {
            flex: 1;
        }

        .feature-card-title {
            font-size: 1.05rem;
            font-weight: 800;
            margin: 0 0 5px 0;
            color: #219c62;
        }

        .feature-card-text {
            font-size: 0.82rem;
            color: #219c62;
            margin: 0;
            line-height: 1.4;
        }

        .feature-tab.active .feature-card-text {
            color: #ffffffff;
        }

        .feature-tab:hover .feature-card-text {
            color: #ffffffff;
        }

        .feature-card-title:hover {
            color: #ffffffff;
        }

        .feature-tab.active .feature-card-title {
            color: #ffffffff;
        }

        .feature-tab:hover .feature-card-title {
            color: #ffffffff;
        }

        .feature-content h3 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .feature-icon-circle {
            width: 80px;
            height: 80px;
            padding: 10px;
            background-color: var(--secondary-orange);
            color: white;
            border-radius: 18%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        .feature-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
        }

        .feature-list-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .feature-list-item i {
            color: var(--primary-green);
            margin-top: 4px;
        }

        .feature-list-item p {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
        }

        .btn-savoir-plus {
            background-color: var(--primary-green);
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            transition: 0.3s;
        }

        .btn-savoir-plus:hover {
            background-color: #147a46;
            color: white;
        }

        /* Feature content visibility */
        .feature-content {
            display: none;
            animation: fadeInUp 0.4s ease forwards;
        }

        .feature-content.active {
            display: block;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Feature tab cursor */
        .feature-tab {
            cursor: pointer;
        }

        /* Partenaires */
        .partners-section {
            padding: 80px 0;
            background-color: #fafafa;
            overflow: hidden;
        }

        .partners-track-wrapper {
            overflow: hidden;
            position: relative;
            padding: 20px 0;
        }

        .partners-track-wrapper::before,
        .partners-track-wrapper::after {
            content: '';
            position: absolute;
            top: 0;
            width: 120px;
            height: 100%;
            z-index: 2;
        }

        .partners-track-wrapper::before {
            left: 0;
            background: linear-gradient(to right, #fafafa, transparent);
        }

        .partners-track-wrapper::after {
            right: 0;
            background: linear-gradient(to left, #fafafa, transparent);
        }

        .partners-track {
            display: flex;
            align-items: center;
            gap: 60px;
            animation: scrollLogos 25s linear infinite;
            width: max-content;
        }

        .partners-track:hover {
            animation-play-state: paused;
        }

        @keyframes scrollLogos {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .partner-logo-item {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px 25px;
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            transition: all 0.4s ease;
            cursor: pointer;
            flex-shrink: 0;
        }

        .partner-logo-item:hover {
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
        }

        .partner-logo {
            height: 55px;
            max-width: 140px;
            object-fit: contain;
            filter: grayscale(100%) brightness(0.7);
            opacity: 0.75;
            transition: all 0.4s ease;
        }

        .partner-logo-item:hover .partner-logo {
            filter: grayscale(0%) brightness(1);
            opacity: 1;
        }

        /* Contactez-nous */
        .contact-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.8)), url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 100px 0;
        }

        .contact-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .contact-subtitle {
            text-align: center;
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 60px;
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 30px;
        }

        .contact-icon-circle {
            width: 50px;
            height: 50px;
            background-color: var(--secondary-orange);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .contact-info-text h4 {
            margin: 0 0 5px 0;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .contact-info-text p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .contact-form-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glass-input {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 8px;
            padding: 12px 15px;
            width: 100%;
            margin-bottom: 20px;
        }

        .glass-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .glass-input:focus {
            outline: none;
            border-color: var(--secondary-orange);
            background: rgba(0, 0, 0, 0.2);
        }

        .btn-submit-contact {
            background-color: var(--secondary-orange);
            color: white;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-submit-contact:hover {
            background-color: #e66a00;
        }
    </style>
@endsection

@include('public.layouts.partials.slider')

<!-- Section Connexions -->
<section id="connexion" class="section-padding bg-light-green">
    <div class="container">
        <div class="section-title-center">
            <h2>Connectez-vous</h2>
        </div>

        <div class="cards-grid">
            <!-- Espace Mairie -->
            <div class="connexion-card theme-white">
                <div class="connexion-icon">
                    <i class="fas fa-landmark"></i>
                </div>
                <h3>Espace Mairie</h3>
                <p>Accédez à votre espace pour gérer les taxes, les acteurs et l'administration financière avec une vue
                    globale des opérations.</p>
                <a href="{{ route('login') }}" class="btn-connexion">Se connecter</a>
            </div>

            <!-- Espace Contribuable -->
            <div class="connexion-card theme-orange">
                <div class="connexion-icon">
                    <i class="fas fa-store"></i>
                </div>
                <h3>Espace Contribuable</h3>
                <p>Déclarez vos activités, suivez vos paiements de taxes et recevez vos attestations directement via
                    votre portail dédié.</p>
                <a href="#" class="btn-connexion">Se connecter</a>
            </div>

            <!-- Espace Agent -->
            <div class="connexion-card theme-white">
                <div class="connexion-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3>Espace Agent</h3>
                <p>Espace réservé aux agents percepteurs pour enregistrer les encaissements sur le terrain et suivre les
                    paiements.</p>
                <a href="#" class="btn-connexion">Se connecter</a>
            </div>
        </div>
    </div>
</section>

<!-- Section À propos -->
<section id="a-propos" class="section-padding section-white">
    <div class="container">
        <div class="about-layout">
            <!-- Image -->
            <div class="about-img-col">
                <div class="about-img-box">
                    <img src="{{ asset('assets/images/hero_slider_bg.png') }}" alt="Calculatrice et monnaie">
                </div>
            </div>

            <!-- Contenu -->
            <div class="about-content-col">
                <div class="about-badge">À propos du SGTC</div>
                <h2 class="about-title">Une gestion municipale moderne et transparente</h2>

                <p class="about-text">
                    Notre solution repense l'intégralité du circuit de recouvrement des recettes municipales. En alliant
                    technologies modernes et simplicité d'usage, le SGTC permet aux mairies d'optimiser leurs ressources
                    tout en offrant une transparence totale aux contribuables.
                </p>
                <p class="about-text">
                    Découvrez une gestion centralisée, des statistiques en temps réel et un gain de temps considérable
                    pour l'ensemble des acteurs de la commune.
                </p>

                <div class="stats-container">
                    <div class="stat-box">
                        <div class="stat-number orange">+250</div>
                        <div class="stat-label">Communes</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number green">18%</div>
                        <div class="stat-label">D'augmentation</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number green">24/7</div>
                        <div class="stat-label">Disponibilité</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number orange">12+</div>
                        <div class="stat-label">Moyens de paiement</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Section Caractéristiques -->
<section id="caracteristiques" class="section-padding" style="background-color: #ffffff;">
    <div class="container">
        <div class="feature-shadow-title">CARACTÉRISTIQUES</div>
        <h2 class="feature-main-title">CARACTÉRISTIQUES</h2>
        <p class="feature-subtitle">Les fonctionnalités clés qui font de notre plateforme l'outil indispensable de votre
            administration locale.</p>

        <!-- Tabs -->
        <div class="feature-tabs">
            <!-- Card Innovation -->
            <div class="feature-tab active" data-tab="innovation">
                <div class="feature-card-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <div class="feature-card-body">
                    <h3 class="feature-card-title">Innovation</h3>
                    <p class="feature-card-text">Solution moderne pour la
                        gestion des taxes.</p>
                </div>
            </div>

            <!-- Card Sécurité -->
            <div class="feature-tab" data-tab="securite">
                <div class="feature-card-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="feature-card-body">
                    <h3 class="feature-card-title">Sécurité</h3>
                    <p class="feature-card-text">Protection avancée et traçabilité complète des données.</p>
                </div>
            </div>

            <!-- Card Écologique -->
            <div class="feature-tab" data-tab="ecologique">
                <div class="feature-card-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <div class="feature-card-body">
                    <h3 class="feature-card-title">Écologique</h3>
                    <p class="feature-card-text">Supervision instantanés.</p>
                </div>
            </div>
        </div>
        <div class="row" style="display: flex; flex-wrap: wrap; gap: 50px;">
            <!-- Gauche : Menu et détails -->
            <div class="col-md-12" style="flex: 1.2; min-width: 300px;">

                <!-- Contenu Innovation -->
                <div class="feature-content active" id="fc-innovation">
                    <div class="feature-icon-circle"><i class="fas fa-rocket"></i></div>
                    <h3>Innovation Excellence</h3>
                    <p class="about-text text-muted">
                        SGTC introduit une approche innovante dans la gestion des taxes communales en remplaçant les
                        méthodes traditionnelles par une solution digitale intelligente. La plateforme utilise des
                        technologies modernes pour automatiser les processus, réduire les tâches manuelles et améliorer
                        l'efficacité globale des services de collecte.
                    </p>

                    <div class="feature-list">
                        <div class="feature-list-item">
                            <i class="fas fa-check-circle" style="color: #e66a00"></i>
                            <p>Digitalisation complète des processus.</p>
                        </div>
                        <div class="feature-list-item">
                            <i class="fas fa-check-circle"></i>
                            <p>Application mobile pour les agents de terrain.</p>
                        </div>
                        <div class="feature-list-item">
                            <i class="fas fa-check-circle"></i>
                            <p>Synchronisation en temps réel.</p>
                        </div>
                        <div class="feature-list-item">
                            <i class="fas fa-check-circle" style="color: #e66a00"></i>
                            <p>Automatisation des opérations administratives.</p>
                        </div>
                    </div>

                    <button class="btn-savoir-plus">
                        En savoir plus <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

                <!-- Contenu Sécurité -->
                <div class="feature-content" id="fc-securite">
                    <div class="feature-icon-circle">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Sécurité des données</h3>
                    <p class="about-text text-muted">
                        La sécurité est au cœur de SGTC. Chaque transaction, chaque accès et chaque donnée sont protégés
                        par des protocoles de chiffrement avancés. Notre architecture garantit la confidentialité des
                        informations
                        financières des communes et des contribuables, avec une traçabilité complète de toutes les
                        opérations.
                    </p>

                    <div class="feature-list">
                        <div class="feature-list-item">
                            <i class="fas fa-check-circle"></i>
                            <p>Chiffrement SSL/TLS de bout en bout.</p>
                        </div>
                        <div class="feature-list-item">
                            <i class="fas fa-check-circle"></i>
                            <p>Authentification à double facteur (2FA).</p>
                        </div>
                        <div class="feature-list-item">
                            <i class="fas fa-check-circle"></i>
                            <p>Journalisation et audit de toutes les actions.</p>
                        </div>
                        <div class="feature-list-item">
                            <i class="fas fa-check-circle"></i>
                            <p>Sauvegardes automatiques et plan de reprise.</p>
                        </div>
                    </div>

                    <button class="btn-savoir-plus">
                        En savoir plus <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

                <!-- Contenu Écologique -->
                <div class="feature-content" id="fc-ecologique">
                    <div class="feature-icon-circle">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Engagement Écologique</h3>
                    <p class="about-text text-muted">
                        SGTC s'inscrit dans une démarche de développement durable. En éliminant le papier et les
                        déplacements inutiles grâce à la digitalisation, nous réduisons l'empreinte carbone des
                        administrations communales. Chaque reçu numérique est un pas de plus vers une administration
                        respectueuse de l'environnement.
                    </p>

                    <div class="feature-list">
                        <div class="feature-list-item">
                            <i class="fas fa-check-circle"></i>
                            <p>Zéro papier grâce aux reçus et rapports numériques.</p>
                        </div>
                        <div class="feature-list-item">
                            <i class="fas fa-check-circle"></i>
                            <p>Réduction des déplacements des agents de terrain.</p>
                        </div>
                        <div class="feature-list-item">
                            <i class="fas fa-check-circle"></i>
                            <p>Serveurs hébergés sur infrastructures à faible énergie.</p>
                        </div>
                        <div class="feature-list-item">
                            <i class="fas fa-check-circle"></i>
                            <p>Contribution à une commune verte et connectée.</p>
                        </div>
                    </div>

                    <button class="btn-savoir-plus">
                        En savoir plus <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Droite : Image téléphone -->
            <div class="col-md-5" style="flex: 1; min-width: 300px;">
                <div
                    style="border-radius: 20px; overflow: hidden; box-shadow: 0 15px 30px rgba(0,0,0,0.1); height: 100%;">
                    <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&q=80"
                        style="width: 100%; height: 100%; object-fit: cover;" alt="Application Mobile">
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Feature Tabs Interactive System
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.feature-tab');
        const contents = document.querySelectorAll('.feature-content');

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                const target = tab.getAttribute('data-tab');

                // Retirer la classe active de tous les onglets
                tabs.forEach(function(t) {
                    t.classList.remove('active');
                });

                // Masquer tous les contenus
                contents.forEach(function(c) {
                    c.classList.remove('active');
                });

                // Activer l'onglet cliqué
                tab.classList.add('active');

                // Afficher le contenu correspondant
                const activeContent = document.getElementById('fc-' + target);
                if (activeContent) {
                    activeContent.classList.add('active');
                }
            });
        });
    });
</script>

<!-- Section Partenaires -->
<section id="partenaires" class="partners-section">
    <div class="container">
        <div class="partenaires-shadow-title" style="font-size: 2.5rem; margin-bottom: -15px;">PARTENAIRES</div>
        <h2 class="feature-main-title" style="font-size: 1.8rem; margin-bottom: 15px;">NOS PARTENAIRES</h2>
        <p style="text-align: center; color: #777; margin-bottom: 40px;">Nous travaillons avec les meilleurs
            partenaires pour vous offrir les meilleurs services</p>
    </div>

    <!-- Carousel auto-défilant -->
    <div class="partners-track-wrapper">
        <div class="partners-track" id="partnersTrack">
            <!-- Logos originaux -->
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/Ecobank_Logo.svg.png') }}" alt="Ecobank"
                    class="partner-logo">
            </div>
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/MTN_Group.jpg') }}" alt="MTN Group"
                    class="partner-logo">
            </div>
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/Moov_Africa_logo.png') }}" alt="Moov Africa"
                    class="partner-logo">
            </div>
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/Orange_Money-Logo.wine_-e1602853666315-0x0.png') }}"
                    alt="Orange Money" class="partner-logo">
            </div>
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/kkslogo.png') }}" alt="KKS"
                    class="partner-logo">
            </div>
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/logo yopougon.png') }}" alt="Mairie de Yopougon"
                    class="partner-logo">
            </div>
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/unnamed.png') }}" alt="Partenaire"
                    class="partner-logo">
            </div>
            <!-- Duplication pour l'effet loop infini -->
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/Ecobank_Logo.svg.png') }}" alt="Ecobank"
                    class="partner-logo">
            </div>
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/MTN_Group.jpg') }}" alt="MTN Group"
                    class="partner-logo">
            </div>
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/Moov_Africa_logo.png') }}" alt="Moov Africa"
                    class="partner-logo">
            </div>
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/Orange_Money-Logo.wine_-e1602853666315-0x0.png') }}"
                    alt="Orange Money" class="partner-logo">
            </div>
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/kkslogo.png') }}" alt="KKS"
                    class="partner-logo">
            </div>
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/logo yopougon.png') }}" alt="Mairie de Yopougon"
                    class="partner-logo">
            </div>
            <div class="partner-logo-item">
                <img src="{{ asset('assets/images/logo_partenaire/unnamed.png') }}" alt="Partenaire"
                    class="partner-logo">
            </div>
        </div>
    </div>
</section>

<!-- Section Contact -->
<section id="contact" class="contact-section">
    <div class="container">
        <h2 class="contact-title">CONTACTEZ-NOUS</h2>
        <hr class="custom-hr">
        <br>
        <p class="contact-subtitle">Une question ? Un besoin spécifique ? Notre équipe est à votre disposition pour
            vous accompagner.</p>

        <div class="row" style="display: flex; flex-wrap: wrap; gap: 50px;">
            <!-- Infos contact -->
            <div class="col-md-6" style="flex: 1; min-width: 300px;">
                <div class="contact-info-item">
                    <div class="contact-icon-circle"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="contact-info-text">
                        <h4>Adresse</h4>
                        <p>Cocody, Angré 7e Tranche, Abidjan</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-icon-circle"><i class="fas fa-phone-alt"></i></div>
                    <div class="contact-info-text">
                        <h4>Téléphone</h4>
                        <p>+225 27 22 00 00 00</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="contact-icon-circle"><i class="fas fa-envelope"></i></div>
                    <div class="contact-info-text">
                        <h4>Email</h4>
                        <p>contact@sgtc.ci</p>
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="col-md-6" style="flex: 1.5; min-width: 300px;">
                <form class="contact-form-glass">
                    <div style="display: flex; gap: 20px;">
                        <input type="text" placeholder="Votre nom complet" class="glass-input">
                        <input type="email" placeholder="Votre adresse email" class="glass-input">
                    </div>
                    <input type="text" placeholder="Sujet de votre demande" class="glass-input">
                    <textarea rows="5" placeholder="Votre message détaillé..." class="glass-input" style="resize: none;"></textarea>
                    <button type="button" class="btn-submit-contact">Envoyer le message</button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
