@extends('public.layouts.app')

@section('title', 'Politique de confidentialité - SGTC')

@section('styles')
    <style>
        .legal-hero {
            background: linear-gradient(135deg, #1e4d8c 0%, #0d3060 100%);
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
            background: #f0f4ff;
            border: 1px solid #d0dcf5;
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
            background: #1e4d8c;
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
            background: #1e4d8c;
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
            background: linear-gradient(135deg, #eef2ff, #e4ebff);
            border-left: 4px solid #1e4d8c;
            border-radius: 0 12px 12px 0;
            padding: 20px 25px;
            margin: 20px 0;
        }

        .legal-highlight-box p {
            margin: 0;
            color: #1e3a7a;
            font-weight: 500;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 12px;
            overflow: hidden;
        }

        .data-table thead {
            background: #1e4d8c;
            color: white;
        }

        .data-table th,
        .data-table td {
            padding: 12px 16px;
            text-align: left;
            font-size: 0.9rem;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f8faff;
        }

        .data-table tbody tr:hover {
            background: #e8efff;
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
            background: #1e4d8c;
            color: white;
            border-color: #1e4d8c;
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(30, 77, 140, 0.2);
        }

        .legal-other-card i {
            font-size: 1.5rem;
            color: #1e4d8c;
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
                <i class="fas fa-shield-alt"></i>
                Protection des données
            </div>
            <h1>Politique de confidentialité</h1>
            <p>Comment nous collectons, utilisons et protégeons vos données personnelles.</p>
            <div class="legal-meta">
                <div class="legal-meta-item">
                    <div class="label">Conforme à</div>
                    <div class="value">Loi ivoirienne n°2013-450</div>
                </div>
                <div class="legal-meta-item">
                    <div class="label">Dernière mise à jour</div>
                    <div class="value">27 Février 2026</div>
                </div>
                <div class="legal-meta-item">
                    <div class="label">Responsable du traitement</div>
                    <div class="value">KKS Technologies</div>
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
                        <li><a href="#collecte">1. Données collectées</a></li>
                        <li><a href="#finalites">2. Finalités du traitement</a></li>
                        <li><a href="#base-legale">3. Base légale</a></li>
                        <li><a href="#conservation">4. Conservation</a></li>
                        <li><a href="#partage">5. Partage des données</a></li>
                        <li><a href="#securite">6. Sécurité</a></li>
                        <li><a href="#droits">7. Vos droits</a></li>
                        <li><a href="#cookies">8. Cookies</a></li>
                        <li><a href="#contact-dpo">9. Contact DPO</a></li>
                    </ul>
                </nav>
            </aside>

            <div class="legal-content">

                <div class="legal-section" id="collecte">
                    <div class="legal-section-num">1</div>
                    <h2>Données personnelles collectées</h2>
                    <p>Dans le cadre de l'utilisation de la plateforme SGTC, nous collectons les catégories de données
                        suivantes :</p>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Catégorie</th>
                                <th>Données</th>
                                <th>Obligatoire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Identité</strong></td>
                                <td>Nom, prénom, numéro CNI</td>
                                <td>Oui</td>
                            </tr>
                            <tr>
                                <td><strong>Contact</strong></td>
                                <td>Email, téléphone, adresse</td>
                                <td>Oui</td>
                            </tr>
                            <tr>
                                <td><strong>Professionnel</strong></td>
                                <td>Nom commercial, secteur, RCCM</td>
                                <td>Oui</td>
                            </tr>
                            <tr>
                                <td><strong>Financier</strong></td>
                                <td>Historique de paiements, montants</td>
                                <td>Oui</td>
                            </tr>
                            <tr>
                                <td><strong>Navigation</strong></td>
                                <td>Logs de connexion, adresse IP</td>
                                <td>Automatique</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="legal-section" id="finalites">
                    <div class="legal-section-num">2</div>
                    <h2>Finalités du traitement</h2>
                    <p>Vos données sont traitées pour les finalités suivantes :</p>
                    <ul>
                        <li>Gestion et suivi du recouvrement des taxes communales</li>
                        <li>Émission des reçus et attestations officiels</li>
                        <li>Communication avec les contribuables (notifications, rappels)</li>
                        <li>Établissement de statistiques et rapports financiers anonymisés</li>
                        <li>Prévention de la fraude et sécurisation des transactions</li>
                        <li>Respect des obligations légales et comptables</li>
                    </ul>
                </div>

                <div class="legal-section" id="base-legale">
                    <div class="legal-section-num">3</div>
                    <h2>Base légale du traitement</h2>
                    <p>Le traitement de vos données repose sur les bases légales suivantes :</p>
                    <ul>
                        <li><strong>Obligation légale :</strong> traitement des taxes conformément au Code Général des
                            Impôts ivoirien</li>
                        <li><strong>Mission d'intérêt public :</strong> collecte des recettes municipales pour les
                            collectivités</li>
                        <li><strong>Consentement :</strong> pour les communications marketing et les cookies non essentiels
                        </li>
                        <li><strong>Intérêt légitime :</strong> sécurisation de la plateforme et prévention de la fraude
                        </li>
                    </ul>
                </div>

                <div class="legal-section" id="conservation">
                    <div class="legal-section-num">4</div>
                    <h2>Durée de conservation</h2>
                    <p>Vos données sont conservées conformément aux obligations légales ivoiriennes :</p>
                    <ul>
                        <li>Données comptables et fiscales : <strong>10 ans</strong> après la clôture de l'exercice</li>
                        <li>Données de compte utilisateur : <strong>3 ans</strong> après la dernière activité</li>
                        <li>Logs de connexion : <strong>12 mois</strong></li>
                        <li>Données de support : <strong>2 ans</strong></li>
                    </ul>
                    <div class="legal-highlight-box">
                        <p><i class="fas fa-info-circle" style="margin-right:8px;"></i>À l'expiration de ces délais, vos
                            données sont supprimées de manière sécurisée ou anonymisées.</p>
                    </div>
                </div>

                <div class="legal-section" id="partage">
                    <div class="legal-section-num">5</div>
                    <h2>Partage des données</h2>
                    <p>Vos données ne sont jamais vendues à des tiers. Elles peuvent être partagées avec :</p>
                    <ul>
                        <li><strong>Les mairies et collectivités :</strong> dans le cadre de leurs missions de recouvrement
                        </li>
                        <li><strong>Les prestataires de paiement :</strong> (MTN, Moov, Orange, Ecobank) pour le traitement
                            des transactions</li>
                        <li><strong>Les autorités judiciaires :</strong> sur demande légale uniquement</li>
                        <li><strong>Nos sous-traitants techniques :</strong> soumis aux mêmes obligations de confidentialité
                        </li>
                    </ul>
                </div>

                <div class="legal-section" id="securite">
                    <div class="legal-section-num">6</div>
                    <h2>Sécurité des données</h2>
                    <p>Nous mettons en œuvre des mesures techniques et organisationnelles rigoureuses pour protéger vos
                        données :</p>
                    <ul>
                        <li>Chiffrement SSL/TLS de toutes les communications</li>
                        <li>Hachage des mots de passe (bcrypt)</li>
                        <li>Authentification à double facteur disponible</li>
                        <li>Journalisation et surveillance des accès</li>
                        <li>Sauvegardes quotidiennes chiffrées</li>
                        <li>Tests de sécurité réguliers (pentests)</li>
                    </ul>
                </div>

                <div class="legal-section" id="droits">
                    <div class="legal-section-num">7</div>
                    <h2>Vos droits</h2>
                    <p>Conformément à la législation applicable, vous disposez des droits suivants sur vos données :</p>
                    <ul>
                        <li><strong>Droit d'accès :</strong> obtenir une copie de vos données personnelles</li>
                        <li><strong>Droit de rectification :</strong> corriger des données inexactes</li>
                        <li><strong>Droit à l'effacement :</strong> sous réserve des obligations légales de conservation
                        </li>
                        <li><strong>Droit à la portabilité :</strong> recevoir vos données dans un format lisible</li>
                        <li><strong>Droit d'opposition :</strong> vous opposer au traitement dans certains cas</li>
                    </ul>
                    <p>Pour exercer vos droits, contactez notre DPO à : <strong>dpo@sgtc.ci</strong></p>
                </div>

                <div class="legal-section" id="cookies">
                    <div class="legal-section-num">8</div>
                    <h2>Politique relative aux cookies</h2>
                    <p>La plateforme SGTC utilise des cookies essentiels au fonctionnement du service (session, sécurité
                        CSRF). Aucun cookie publicitaire n'est utilisé.</p>
                    <ul>
                        <li><strong>Cookies fonctionnels :</strong> session utilisateur, préférences de langue</li>
                        <li><strong>Cookies de sécurité :</strong> protection contre les attaques CSRF, authentification
                        </li>
                        <li><strong>Cookies analytiques :</strong> statistiques d'usage anonymisées (opt-in)</li>
                    </ul>
                </div>

                <div class="legal-section" id="contact-dpo">
                    <div class="legal-section-num">9</div>
                    <h2>Contact – Délégué à la Protection des Données</h2>
                    <p>Pour toute question relative à la protection de vos données personnelles :</p>
                    <ul>
                        <li><strong>Email :</strong> dpo@sgtc.ci</li>
                        <li><strong>Adresse :</strong> KKS Technologies, Cocody Angré 7e Tranche, Abidjan, Côte d'Ivoire
                        </li>
                        <li><strong>Téléphone :</strong> +225 27 22 00 00 00</li>
                    </ul>
                </div>

                <div class="legal-other-pages">
                    <a href="{{ route('conditions-utilisation') }}" class="legal-other-card">
                        <i class="fas fa-file-contract"></i>
                        <span>Conditions d'utilisation</span>
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
