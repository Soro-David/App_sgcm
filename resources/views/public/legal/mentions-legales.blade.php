@extends('public.layouts.app')

@section('title', 'Mentions légales - SGTC')

@section('styles')
    <style>
        .legal-hero {
            background: linear-gradient(135deg, #4a1d6e 0%, #2d0d47 100%);
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
        }

        .legal-hero h1 {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 15px;
        }

        .legal-hero p {
            opacity: 0.85;
            font-size: 1rem;
        }

        .legal-meta {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 30px;
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

        .legal-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 50px;
            padding: 80px 0;
            align-items: start;
        }

        .legal-sidebar {
            position: sticky;
            top: 110px;
        }

        .legal-nav {
            background: #faf5ff;
            border: 1px solid #e5d5ff;
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
            background: #4a1d6e;
            color: white;
            padding-left: 16px;
        }

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
            background: #4a1d6e;
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

        .info-card-legal {
            background: white;
            border: 1px solid #eee;
            border-radius: 16px;
            padding: 25px 30px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        }

        .info-card-legal h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #4a1d6e;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card-legal h3 i {
            font-size: 1.1rem;
        }

        .info-card-legal p,
        .info-card-legal address {
            color: #555;
            font-size: 0.9rem;
            line-height: 1.8;
            font-style: normal;
            margin: 0;
        }

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
            background: #4a1d6e;
            color: white;
            border-color: #4a1d6e;
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(74, 29, 110, 0.2);
        }

        .legal-other-card i {
            font-size: 1.5rem;
            color: #4a1d6e;
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

    <section class="legal-hero">
        <div class="container">
            <div class="legal-hero-badge">
                <i class="fas fa-balance-scale"></i>
                Informations légales
            </div>
            <h1>Mentions légales</h1>
            <p>Informations légales et réglementaires relatives à la plateforme SGTC.</p>
            <div class="legal-meta">
                <div class="legal-meta-item">
                    <div class="label">Éditeur</div>
                    <div class="value">KKS Technologies</div>
                </div>
                <div class="legal-meta-item">
                    <div class="label">Siège social</div>
                    <div class="value">Abidjan, Côte d'Ivoire</div>
                </div>
                <div class="legal-meta-item">
                    <div class="label">Mise à jour</div>
                    <div class="value">27 Février 2026</div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="legal-layout">

            <aside class="legal-sidebar">
                <nav class="legal-nav">
                    <h4>Sommaire</h4>
                    <ul>
                        <li><a href="#editeur">1. Éditeur du site</a></li>
                        <li><a href="#hebergement">2. Hébergement</a></li>
                        <li><a href="#directeur">3. Directeur de publication</a></li>
                        <li><a href="#propriete-intellectuelle">4. Propriété intellectuelle</a></li>
                        <li><a href="#responsabilite">5. Responsabilité</a></li>
                        <li><a href="#conformite">6. Conformité réglementaire</a></li>
                        <li><a href="#litiges">7. Litiges</a></li>
                        <li><a href="#contact-legal">8. Contact</a></li>
                    </ul>
                </nav>
            </aside>

            <div class="legal-content">

                <div class="legal-section" id="editeur">
                    <div class="legal-section-num">1</div>
                    <h2>Éditeur du site</h2>
                    <div class="info-card-legal">
                        <h3><i class="fas fa-building"></i> Informations sur la société</h3>
                        <address>
                            <strong>KKS Technologies SARL</strong><br>
                            Cocody Angré, 7e Tranche<br>
                            Abidjan, Côte d'Ivoire<br><br>
                            <strong>Registre du Commerce :</strong> CI-ABJ-2022-B-XXXXX<br>
                            <strong>Numéro contribuable :</strong> XXXXXXXXXX<br>
                            <strong>Capital social :</strong> 5 000 000 FCFA<br><br>
                            <strong>Email :</strong> contact@kks-technologies.ci<br>
                            <strong>Téléphone :</strong> +225 27 22 00 00 00
                        </address>
                    </div>
                </div>

                <div class="legal-section" id="hebergement">
                    <div class="legal-section-num">2</div>
                    <h2>Hébergement</h2>
                    <div class="info-card-legal">
                        <h3><i class="fas fa-server"></i> Informations sur l'hébergeur</h3>
                        <p>
                            La plateforme SGTC est hébergée sur des serveurs sécurisés situés en Afrique de l'Ouest,
                            conformément aux exigences de souveraineté des données des collectivités territoriales
                            ivoiriennes.<br><br>
                            <strong>Infrastructure :</strong> Serveurs dédiés avec redondance<br>
                            <strong>Disponibilité garantie :</strong> 99,5% (SLA)<br>
                            <strong>Sauvegarde :</strong> Quotidienne, chiffrée, géo-redondante<br>
                            <strong>Certification :</strong> ISO 27001 en cours
                        </p>
                    </div>
                </div>

                <div class="legal-section" id="directeur">
                    <div class="legal-section-num">3</div>
                    <h2>Directeur de la publication</h2>
                    <div class="info-card-legal">
                        <h3><i class="fas fa-user-tie"></i> Responsable légal</h3>
                        <p>
                            Le directeur de la publication de la plateforme SGTC est le représentant légal de KKS
                            Technologies.<br><br>
                            Pour contacter la direction : <strong>direction@kks-technologies.ci</strong>
                        </p>
                    </div>
                </div>

                <div class="legal-section" id="propriete-intellectuelle">
                    <div class="legal-section-num">4</div>
                    <h2>Propriété intellectuelle</h2>
                    <p>L'ensemble des éléments constituant la plateforme SGTC (code source, interface graphique, marque,
                        logo, contenus textuels et visuels) est protégé par le droit de la propriété intellectuelle et
                        appartient exclusivement à KKS Technologies.</p>
                    <ul>
                        <li>Toute reproduction partielle ou totale sans autorisation est interdite</li>
                        <li>L'utilisation de la marque SGTC nécessite une autorisation écrite préalable</li>
                        <li>Les données générées par les collectivités restent leur propriété exclusive</li>
                        <li>Le code source est protégé par droits d'auteur — Dépôt OAPI enregistré</li>
                    </ul>
                </div>

                <div class="legal-section" id="responsabilite">
                    <div class="legal-section-num">5</div>
                    <h2>Limitation de responsabilité</h2>
                    <p>KKS Technologies met tout en œuvre pour assurer la disponibilité et la fiabilité de la plateforme.
                        Cependant, notre responsabilité est limitée dans les cas suivants :</p>
                    <ul>
                        <li>Indisponibilité temporaire pour maintenance planifiée (avec préavis de 48h)</li>
                        <li>Force majeure (pannes réseau, catastrophes naturelles, cyberattaques)</li>
                        <li>Utilisation inappropriée de la plateforme par l'utilisateur</li>
                        <li>Erreurs résultant de données incorrectes fournies par l'utilisateur</li>
                    </ul>
                </div>

                <div class="legal-section" id="conformite">
                    <div class="legal-section-num">6</div>
                    <h2>Conformité réglementaire</h2>
                    <p>La plateforme SGTC est développée et opérée dans le respect des textes législatifs et réglementaires
                        ivoiriens applicables :</p>
                    <ul>
                        <li>Loi n°2013-450 du 19 juin 2013 relative à la protection des données à caractère personnel</li>
                        <li>Code Général des Collectivités Territoriales de Côte d'Ivoire</li>
                        <li>Code Général des Impôts de Côte d'Ivoire</li>
                        <li>Réglementation de l'ARTCI relative aux services électroniques</li>
                        <li>Instructions de la BCEAO relatives aux paiements électroniques</li>
                    </ul>
                </div>

                <div class="legal-section" id="litiges">
                    <div class="legal-section-num">7</div>
                    <h2>Règlement des litiges</h2>
                    <p>En cas de litige survenant dans le cadre de l'utilisation de la plateforme SGTC :</p>
                    <ul>
                        <li>Une solution amiable sera recherchée en priorité</li>
                        <li>À défaut, le litige sera soumis aux tribunaux compétents d'Abidjan</li>
                        <li>Le droit ivoirien est seul applicable</li>
                    </ul>
                    <p>Pour soumettre une réclamation : <strong>reclamations@sgtc.ci</strong></p>
                </div>

                <div class="legal-section" id="contact-legal">
                    <div class="legal-section-num">8</div>
                    <h2>Contact juridique</h2>
                    <div class="info-card-legal">
                        <h3><i class="fas fa-envelope"></i> Service juridique</h3>
                        <address>
                            <strong>KKS Technologies — Service Juridique</strong><br>
                            Cocody Angré, 7e Tranche, Abidjan<br>
                            Côte d'Ivoire<br><br>
                            <strong>Email :</strong> legal@sgtc.ci<br>
                            <strong>Téléphone :</strong> +225 27 22 00 00 00<br>
                            <strong>Horaires :</strong> Lundi – Vendredi, 8h00 – 17h00 GMT
                        </address>
                    </div>
                </div>

                <div class="legal-other-pages">
                    <a href="{{ route('conditions-utilisation') }}" class="legal-other-card">
                        <i class="fas fa-file-contract"></i>
                        <span>Conditions d'utilisation</span>
                    </a>
                    <a href="{{ route('politique-confidentialite') }}" class="legal-other-card">
                        <i class="fas fa-shield-alt"></i>
                        <span>Politique de confidentialité</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

@endsection
