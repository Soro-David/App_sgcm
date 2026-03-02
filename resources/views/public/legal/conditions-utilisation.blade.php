@extends('public.layouts.app')

@section('title', "Conditions d'utilisation - SGTC")

@section('styles')
    <style>
        .legal-hero {
            background: linear-gradient(135deg, var(--primary-green) 0%, #0d6e3e 100%);
            padding: 160px 0 80px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .legal-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
        }

        .legal-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 350px;
            height: 350px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
        }

        .legal-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }

        .legal-hero h1 {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .legal-hero p {
            opacity: 0.85;
            font-size: 1rem;
            position: relative;
            z-index: 2;
        }

        .legal-meta {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 30px;
            position: relative;
            z-index: 2;
        }

        .legal-meta-item {
            text-align: center;
        }

        .legal-meta-item .label {
            font-size: 0.75rem;
            opacity: 0.7;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .legal-meta-item .value {
            font-size: 0.95rem;
            font-weight: 700;
        }

        /* Layout */
        .legal-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 50px;
            padding: 80px 0;
            align-items: start;
        }

        /* Sidebar navigation */
        .legal-sidebar {
            position: sticky;
            top: 110px;
        }

        .legal-nav {
            background: #f8fdf9;
            border: 1px solid #e0f0ea;
            border-radius: 16px;
            padding: 25px;
        }

        .legal-nav h4 {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #999;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .legal-nav ul {
            list-style: none;
        }

        .legal-nav ul li {
            margin-bottom: 5px;
        }

        .legal-nav ul li a {
            display: block;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.88rem;
            color: #555;
            font-weight: 500;
            transition: all 0.25s;
        }

        .legal-nav ul li a:hover {
            background: var(--primary-green);
            color: white;
            padding-left: 16px;
        }

        /* Content */
        .legal-content {
            max-width: 800px;
        }

        .legal-section {
            margin-bottom: 55px;
            scroll-margin-top: 120px;
        }

        .legal-section-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: var(--primary-green);
            color: white;
            border-radius: 50%;
            font-size: 0.85rem;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .legal-section h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #111;
            margin-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 12px;
        }

        .legal-section p {
            color: #555;
            line-height: 1.8;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }

        .legal-section ul {
            list-style: none;
            margin: 12px 0;
        }

        .legal-section ul li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #555;
            font-size: 0.95rem;
            margin-bottom: 10px;
            line-height: 1.7;
        }

        .legal-section ul li::before {
            content: '';
            width: 7px;
            height: 7px;
            background: var(--secondary-orange);
            border-radius: 50%;
            margin-top: 8px;
            flex-shrink: 0;
        }

        .legal-highlight-box {
            background: linear-gradient(135deg, #f0faf4, #e8f7f0);
            border-left: 4px solid var(--primary-green);
            border-radius: 0 12px 12px 0;
            padding: 20px 25px;
            margin: 20px 0;
        }

        .legal-highlight-box p {
            margin: 0;
            color: #2a7a4e;
            font-weight: 500;
        }

        .legal-warning-box {
            background: #fff8f0;
            border-left: 4px solid var(--secondary-orange);
            border-radius: 0 12px 12px 0;
            padding: 20px 25px;
            margin: 20px 0;
        }

        .legal-warning-box p {
            margin: 0;
            color: #a05a00;
            font-weight: 500;
        }

        /* Other pages links */
        .legal-other-pages {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 60px;
            padding-top: 40px;
            border-top: 1px solid #eee;
        }

        .legal-other-card {
            background: #f9f9f9;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s;
            border: 1px solid #eee;
        }

        .legal-other-card:hover {
            background: var(--primary-green);
            color: white;
            border-color: var(--primary-green);
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(26, 154, 90, 0.2);
        }

        .legal-other-card i {
            font-size: 1.5rem;
            color: var(--primary-green);
            transition: color 0.3s;
        }

        .legal-other-card:hover i {
            color: white;
        }

        .legal-other-card span {
            font-weight: 700;
            font-size: 0.9rem;
        }

        @media (max-width: 900px) {
            .legal-layout {
                grid-template-columns: 1fr;
            }

            .legal-sidebar {
                position: static;
            }

            .legal-hero h1 {
                font-size: 2rem;
            }

            .legal-meta {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
@endsection

@section('content')

    {{-- Hero --}}
    <section class="legal-hero">
        <div class="container">
            <div class="legal-hero-badge">
                <i class="fas fa-file-contract"></i>
                Document légal
            </div>
            <h1>Conditions d'utilisation</h1>
            <p>Veuillez lire attentivement ces conditions avant d'utiliser la plateforme SGTC.</p>
            <div class="legal-meta">
                <div class="legal-meta-item">
                    <div class="label">Date d'entrée en vigueur</div>
                    <div class="value">1er Janvier 2025</div>
                </div>
                <div class="legal-meta-item">
                    <div class="label">Dernière mise à jour</div>
                    <div class="value">27 Février 2026</div>
                </div>
                <div class="legal-meta-item">
                    <div class="label">Version</div>
                    <div class="value">v2.1</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Layout principal --}}
    <div class="container">
        <div class="legal-layout">

            {{-- Sidebar --}}
            <aside class="legal-sidebar">
                <nav class="legal-nav">
                    <h4>Sommaire</h4>
                    <ul>
                        <li><a href="#acceptation">1. Acceptation</a></li>
                        <li><a href="#description">2. Description du service</a></li>
                        <li><a href="#acces">3. Accès à la plateforme</a></li>
                        <li><a href="#obligations">4. Obligations des utilisateurs</a></li>
                        <li><a href="#propriete">5. Propriété intellectuelle</a></li>
                        <li><a href="#responsabilite">6. Responsabilité</a></li>
                        <li><a href="#donnees">7. Données personnelles</a></li>
                        <li><a href="#resiliation">8. Résiliation</a></li>
                        <li><a href="#modification">9. Modification des CGU</a></li>
                        <li><a href="#droit">10. Droit applicable</a></li>
                    </ul>
                </nav>
            </aside>

            {{-- Contenu --}}
            <div class="legal-content">

                <div class="legal-section" id="acceptation">
                    <div class="legal-section-num">1</div>
                    <h2>Acceptation des conditions</h2>
                    <p>En accédant à la plateforme SGTC (Système de Gestion des Taxes Communales), vous acceptez pleinement
                        et sans réserve les présentes Conditions Générales d'Utilisation (CGU). Si vous n'acceptez pas ces
                        conditions, vous devez cesser immédiatement l'utilisation de la plateforme.</p>
                    <div class="legal-highlight-box">
                        <p><i class="fas fa-info-circle" style="margin-right:8px;"></i>Ces conditions constituent un accord
                            juridiquement contraignant entre vous et KKS Technologies, éditeur de la plateforme SGTC.</p>
                    </div>
                </div>

                <div class="legal-section" id="description">
                    <div class="legal-section-num">2</div>
                    <h2>Description du service</h2>
                    <p>SGTC est une plateforme numérique de gestion des taxes communales destinée aux collectivités
                        territoriales, aux contribuables et aux agents de recouvrement. La plateforme offre notamment :</p>
                    <ul>
                        <li>La gestion et le suivi des taxes communales</li>
                        <li>Le traitement sécurisé des paiements électroniques</li>
                        <li>La génération de rapports financiers et statistiques</li>
                        <li>La gestion des agents de terrain et leurs encaissements</li>
                        <li>L'émission de reçus et attestations numériques</li>
                    </ul>
                </div>

                <div class="legal-section" id="acces">
                    <div class="legal-section-num">3</div>
                    <h2>Accès à la plateforme</h2>
                    <p>L'accès à la plateforme est reservé aux utilisateurs dûment enregistrés et autorisés. Trois types
                        d'espaces sont disponibles :</p>
                    <ul>
                        <li><strong>Espace Mairie :</strong> réservé aux administrateurs municipaux accrédités</li>
                        <li><strong>Espace Contribuable :</strong> accessible aux commerçants et entreprises enregistrés
                        </li>
                        <li><strong>Espace Agent :</strong> réservé aux agents de recouvrement mandatés</li>
                    </ul>
                    <p>Chaque utilisateur est responsable de la confidentialité de ses identifiants de connexion. Tout accès
                        non autorisé doit être signalé immédiatement à notre équipe support.</p>
                </div>

                <div class="legal-section" id="obligations">
                    <div class="legal-section-num">4</div>
                    <h2>Obligations des utilisateurs</h2>
                    <p>En utilisant la plateforme SGTC, vous vous engagez à :</p>
                    <ul>
                        <li>Fournir des informations exactes, complètes et à jour lors de votre inscription</li>
                        <li>Ne pas partager vos identifiants de connexion avec des tiers</li>
                        <li>Utiliser la plateforme exclusivement à des fins légales et conformes à sa destination</li>
                        <li>Ne pas tenter de contourner les mesures de sécurité de la plateforme</li>
                        <li>Respecter la confidentialité des données des autres utilisateurs</li>
                        <li>Signaler toute anomalie ou violation de sécurité dont vous auriez connaissance</li>
                    </ul>
                    <div class="legal-warning-box">
                        <p><i class="fas fa-exclamation-triangle" style="margin-right:8px;"></i>Tout manquement à ces
                            obligations pourra entraîner la suspension ou la suppression de votre compte, sans préjudice de
                            poursuites judiciaires éventuelles.</p>
                    </div>
                </div>

                <div class="legal-section" id="propriete">
                    <div class="legal-section-num">5</div>
                    <h2>Propriété intellectuelle</h2>
                    <p>La plateforme SGTC, son code source, son interface, ses marques, logos et contenus sont la propriété
                        exclusive de KKS Technologies. Toute reproduction, distribution ou utilisation non autorisée est
                        strictement interdite.</p>
                </div>

                <div class="legal-section" id="responsabilite">
                    <div class="legal-section-num">6</div>
                    <h2>Limitation de responsabilité</h2>
                    <p>KKS Technologies s'engage à assurer la disponibilité et la sécurité de la plateforme dans la limite
                        du possible. Cependant, notre responsabilité ne pourra être engagée en cas de :</p>
                    <ul>
                        <li>Interruption du service pour maintenance planifiée ou urgence technique</li>
                        <li>Perte de données résultant d'une utilisation non conforme de la plateforme</li>
                        <li>Dommages indirects résultant de l'utilisation ou de l'impossibilité d'utiliser la plateforme
                        </li>
                    </ul>
                </div>

                <div class="legal-section" id="donnees">
                    <div class="legal-section-num">7</div>
                    <h2>Données personnelles</h2>
                    <p>Le traitement de vos données personnelles est régi par notre <a
                            href="{{ route('politique-confidentialite') }}"
                            style="color: var(--primary-green); font-weight: 600;">Politique de Confidentialité</a>. En
                        utilisant la plateforme, vous consentez au traitement de vos données conformément à cette politique.
                    </p>
                </div>

                <div class="legal-section" id="resiliation">
                    <div class="legal-section-num">8</div>
                    <h2>Résiliation</h2>
                    <p>KKS Technologies se réserve le droit de suspendre ou résilier votre accès à la plateforme à tout
                        moment, en cas de violation des présentes conditions ou pour des raisons légitimes, avec
                        notification préalable dans la mesure du possible.</p>
                </div>

                <div class="legal-section" id="modification">
                    <div class="legal-section-num">9</div>
                    <h2>Modification des CGU</h2>
                    <p>KKS Technologies se réserve le droit de modifier les présentes conditions à tout moment. Les
                        utilisateurs seront informés par email de toute modification significative. L'utilisation continue
                        de la plateforme après notification vaut acceptation des nouvelles conditions.</p>
                </div>

                <div class="legal-section" id="droit">
                    <div class="legal-section-num">10</div>
                    <h2>Droit applicable et juridiction</h2>
                    <p>Les présentes CGU sont soumises au droit ivoirien. Tout litige relatif à leur interprétation ou
                        exécution sera soumis aux tribunaux compétents d'Abidjan, Côte d'Ivoire, sauf disposition légale
                        contraire.</p>
                    <p>Pour toute question concernant ces conditions, contactez-nous à : <strong>legal@sgtc.ci</strong></p>
                </div>

                {{-- Liens vers les autres pages légales --}}
                <div class="legal-other-pages">
                    <a href="{{ route('politique-confidentialite') }}" class="legal-other-card">
                        <i class="fas fa-shield-alt"></i>
                        <span>Politique de confidentialité</span>
                    </a>
                    <a href="{{ route('mentions-legales') }}" class="legal-other-card">
                        <i class="fas fa-balance-scale"></i>
                        <span>Mentions légales</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

@endsection
