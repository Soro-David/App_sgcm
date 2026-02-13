@extends('agent.layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center py-5 min-vh-100">
        <div class="virtual-card-wrapper">

            <!-- Zone de la Carte Flip -->
            <div class="virtual-card-container">
                <div class="flip-card" id="virtualCard">
                    <div class="flip-card-inner">

                        <!-- FACE RECTO (FRONT) -->
                        <div class="flip-card-front card-shadow">
                            <!-- Overlay décoratif -->
                            <div class="bg-overlay"></div>

                            <div class="card-content-wrapper">
                                <div class="card-header-text">
                                    <h1 class="card-title">CARTE DE CONTRIBUABLE</h1>
                                    <h2 class="card-subtitle text-truncate">{{ $commercant->nom }}</h2>
                                </div>

                                <hr class="card-divider">

                                <div class="card-body">
                                    <div class="profile-section">
                                        <div class="photo-frame">
                                            <img src="{{ $commercant->photo_profil ? asset('storage/' . $commercant->photo_profil) : asset('images/default_avatar.jpg') }}"
                                                alt="Photo Profil" class="profile-img">
                                        </div>
                                    </div>

                                    <div class="info-group-grid">
                                        <div class="info-item full-width">
                                            <span class="info-label">ID COMMERCE</span>
                                            <span class="info-value highlight">{{ $commercant->num_commerce }}</span>
                                        </div>

                                        <div class="info-item">
                                            <span class="info-label">SECTEUR D'ACTIVITÉ</span>
                                            <span
                                                class="info-value">{{ $commercant->secteur->nom ?? 'Commerce Général' }}</span>
                                        </div>

                                        <div class="info-item">
                                            <span class="info-label">COMMUNE / MAIRIE</span>
                                            <span
                                                class="info-value">{{ $commercant->mairie->name ?? ($commercant->mairie->nom ?? 'Non défini') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer-strip">
                                    <span>VALIDE POUR L'ANNÉE EN COURS</span>
                                </div>
                            </div>
                        </div>

                        <!-- FACE VERSO (BACK) -->
                        <div class="flip-card-back card-shadow">
                            <div class="card-content-wrapper back-wrapper">
                                <div class="back-header">
                                    <h1 class="back-title">SCAN DE CONTRÔLE</h1>
                                    <p class="back-subtitle">Identifiant unique du contribuable</p>
                                </div>

                                <div class="back-divider"></div>

                                <div class="qr-container">
                                    @if ($commercant->qr_code_path)
                                        <div class="qr-code-wrapper">
                                            <img src="{{ asset('storage/' . $commercant->qr_code_path) }}" alt="QR Code"
                                                class="qr-img">
                                        </div>
                                    @else
                                        <div class="qr-placeholder">
                                            <i class="fas fa-qrcode fa-3x mb-2"></i>
                                            <span>QR NON GÉNÉRÉ</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="disclaimer">
                                    <p>Cette carte est la propriété de l'administration fiscale locale. Elle doit être
                                        présentée lors de tout contrôle. En cas de perte, veuillez contacter votre mairie.
                                    </p>
                                </div>

                                <div class="back-footer">
                                    <div class="footer-brand">ANATH - SYSTÈME SÉCURISÉ</div>
                                    <div class="footer-version">REF: {{ substr($commercant->num_commerce, -6) }}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Boutons d'Action -->
            <div class="action-buttons no-print mt-5">
                <a href="{{ route('agent.contribuable.index') }}" class="btn-action secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>

                <a href="{{ route('agent.contribuable.export_virtual_card', $commercant->id) }}" class="btn-action primary"
                    style="background: linear-gradient(to right, #00b09b, #96c93d);">
                    <i class="fas fa-file-export"></i> Format Impression (Recto/Verso)
                </a>

                <div class="mt-3 text-muted small text-center d-none d-md-block">
                    <i class="fas fa-mouse-pointer me-1"></i> Cliquez sur la carte pour la retourner
                </div>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

        :root {
            --card-width: 500px;
            /* Légèrement réduit pour meilleure compatibilité */
            --card-height: 315px;
            /* Ratio standard ID-1 */
            --primary-gradient: linear-gradient(#ffdc82ff 10%, #FF8008 100%);
            /* Orange officiel */
            --text-heading: #0a4f2e;
            /* Vert foncé pour contraste */
            --text-dark: #1f2937;
            --border-accent: #ffffff;
            --shadow-soft: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: #f3f4f6;
            font-family: 'Outfit', sans-serif;
        }

        /* Container principal */
        .virtual-card-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .virtual-card-container {
            perspective: 1500px;
            width: var(--card-width);
            height: var(--card-height);
            position: relative;
            margin: 0 auto;
        }

        /* Animation Flip */
        .flip-card {
            width: 100%;
            height: 100%;
            cursor: pointer;
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
            position: relative;
        }

        .flip-card.flipped {
            transform: rotateY(180deg);
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transform-style: preserve-3d;
        }

        /* Styles communs aux deux faces */
        .flip-card-front,
        .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            border-radius: 16px;
            overflow: hidden;
            /* border: 1px solid #0a4f2e; */
        }

        .card-shadow {
            box-shadow: var(--shadow-soft);
        }

        /* --- DESIGN RECTO --- */
        .flip-card-front {
            background: var(--primary-gradient);
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            padding: 20px 24px;
        }

        .bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 100% 0%, rgba(255, 255, 255, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 0% 100%, rgba(255, 255, 255, 0.15) 0%, transparent 50%);
            z-index: 0;
            pointer-events: none;
        }

        .card-content-wrapper {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            color: var(--text-heading);
            font-size: 1.1rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-shadow:
                -2px -1px 0 #fff,
                -1px 1px 0 #fff,
                1px 1px 0 #fff;
        }

        .card-subtitle {
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            font-size: 1.5rem;
            font-weight: 700;
            margin: 2px 0 0 0;
        }

       .card-divider {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.5) !important;
            margin: 15px 0;
            border: 1px solid #fff;
            border-radius: 4px;
            position: relative;
            z-index: 10;
        }

        .card-body {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-grow: 1;
        }

        .photo-frame {
            width: 110px;
            height: 110px;
            border: 4px solid #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background: #fff;
            flex-shrink: 0;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-group-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            text-align: left;
            width: 100%;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            background: none;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .info-item.full-width {
            background: none;
        }

        .info-label {
            color: var(--text-heading);
            font-size: 1rem;
            font-weight: 800;
            text-transform: uppercase;
            opacity: 0.9;

            text-shadow:
                -2px -1px 0 #fff,
                -1px 1px 0 #fff,
                1px 1px 0 #fff;
        }


        .info-value {
            color: #000;
            font-size: 0.9rem;
            font-weight: 700;
            line-height: 1.2;
            /* display: block; */
        }

        .info-value.highlight {
            font-size: 1rem;
            letter-spacing: 0.5px;
        }

        .card-footer-strip {
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.4);
            padding-top: 8px;
            text-align: right;
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--text-heading);
            letter-spacing: 1px;
        }

        /* --- DESIGN VERSO --- */
        .flip-card-back {
            background-color: #fff;
            transform: rotateY(180deg);
            display: flex;
            flex-direction: column;
            padding: 20px;
            border: 1px solid #06b64cff;
            color: var(--text-dark);
            /* border-left: 5px solid #FF8008; */
            /* Bande orange décorative */
        }

        .back-title {
            color: #FF8008;
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
        }

        .back-subtitle {
            font-size: 0.8rem;
            color: #6b7280;
            margin: 0;
        }

        .back-divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 15px 0;
        }

        .qr-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-code-wrapper {
            padding: 8px;
            border: 2px dashed #FFC837;
            border-radius: 12px;
        }

        .qr-img {
            width: 100px;
            height: 100px;
            display: block;
        }

        .qr-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #d1d5db;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .disclaimer {
            font-size: 0.65rem;
            color: #6b7280;
            text-align: center;
            margin: 15px 0;
            line-height: 1.4;
            background: #f9fafb;
            padding: 8px;
            border-radius: 6px;
        }

        .back-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            font-size: 0.65rem;
            color: #9ca3af;
        }

        .footer-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* --- BOUTONS --- */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
            width: 100%;
            max-width: 300px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            gap: 10px;
        }

        .btn-action.primary {
            background: linear-gradient(to right, #FF8008, #FFC837);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 128, 8, 0.3);
        }

        .btn-action.secondary {
            background: #fff;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            color: inherit;
        }

        /* --- RESPONSIVITÉ --- */
        @media (max-width: 600px) {
            :root {
                --card-width: 340px;
                --card-height: 520px;
                /* Format vertical sur mobile si besoin, ou juste plus petit */
            }

            .virtual-card-container {
                /* On garde le ratio horizontal mais on scale down */
                transform: scale(0.65);
                margin: -50px 0;
                /* Compenser le vide créé par le scale */
            }

            .container {
                padding-left: 0;
                padding-right: 0;
                overflow-x: hidden;
            }
        }

        /* --- IMPRESSION --- */
        @media print {
            @page {
                margin: 1cm;
                size: A4;
            }

            body {
                background: none;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .container {
                display: block !important;
                padding: 0;
                margin: 0;
                height: auto !important;
                min-height: 0 !important;
            }

            .virtual-card-wrapper {
                display: block;
            }

            .virtual-card-container {
                perspective: none;
                width: 100%;
                height: auto;
                transform: none !important;
                margin: 0;
            }

            .flip-card {
                transform: none !important;
            }

            .flip-card-inner {
                transform: none !important;
            }

            .flip-card-front,
            .flip-card-back {
                position: relative;
                width: 85.6mm;
                /* Standard Carte de crédit */
                height: 53.98mm;
                margin: 20px auto;
                transform: none !important;
                border: 1px solid #ddd;
                page-break-inside: avoid;
                box-shadow: none;
                left: auto;
                top: auto;
            }

            /* Ajustements de taille pour l'impression */
            :root {
                --card-width: 85.6mm;
                --card-height: 53.98mm;
            }

            /* Cacher l'overlay décoratif pour économiser l'encre si souhaité,
                                           sinon laisser tel quel */
            .bg-overlay {
                opacity: 0.5;
            }

            .flip-card-back {
                border-left: none;
                /* Retirer la bande déco en print si gênant */
                border: 1px solid #ccc;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.getElementById('virtualCard');
            // Interaction au clic pour desktop
            card.addEventListener('click', function() {
                this.classList.toggle('flipped');
            });
        });
    </script>
@endsection
