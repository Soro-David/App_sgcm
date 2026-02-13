<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression Groupée - Cartes des Contribuables</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

        :root {
            --card-width: 85.6mm;
            --card-height: 53.98mm;
            --primary-gradient: linear-gradient(#ffdc82ff 10%, #FF8008 100%);
            --text-heading: #0a4f2e;
            --text-dark: #1f2937;
        }

        body {
            margin: 0;
            padding: 20px;
            font-family: 'Outfit', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .print-actions {
            margin-bottom: 20px;
            position: sticky;
            top: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
        }

        .btn-print {
            padding: 10px 20px;
            background: #FF8008;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* --- DESIGN DES CARTES --- */
        .taxpayer-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 40px;
            width: fit-content;
        }

        .card-print-container {
            width: var(--card-width);
            height: var(--card-height);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            background: white;
        }

        /* RECTO */
        .card-front {
            background-color: #FF8008 !important;
            background-image: linear-gradient(#ffdc82 10%, #FF8008 100%) !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color: var(--text-dark);
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 15px 20px;
            box-sizing: border-box;
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
            font-size: 1.1rem;
            font-weight: 700;
            margin: 2px 0;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-divider {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.5) !important;
            margin: 10px 0;
            border: 1px solid #fff;
            border-radius: 4px;
            position: relative;
            z-index: 10;
        }

        .card-body {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-grow: 1;
        }

        .photo-frame {
            width: 70px;
            height: 70px;
            border: 3px solid #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background: #fff;
            flex-shrink: 0;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 5px;
            text-align: left;
        }

        .info-label {
            color: var(--text-heading);
            font-size: 0.6rem;
            font-weight: 800;
            text-transform: uppercase;
            text-shadow: -1px -0.5px 0 #fff, -0.5px 0.5px 0 #fff, 0.5px 0.5px 0 #fff;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            padding: 2px 0;
        }

        .info-value {
            color: #000;
            font-size: 0.7rem;
            font-weight: 700;
            line-height: 1;
        }

        .card-footer {
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.4);
            padding-top: 5px;
            text-align: right;
            font-size: 0.5rem;
            font-weight: 600;
            color: var(--text-heading);
        }

        /* VERSO */
        .card-back {
            background-color: #fff;
            padding: 15px;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            height: 100%;
            border: 1px solid #018212ff;
            border-radius: 10px;
            overflow: hidden;
        }

        .back-title {
            color: #FF8008;
            font-size: 0.9rem;
            font-weight: 800;
            margin: 0;
            text-align: center;
            text-transform: uppercase;
        }

        .back-subtitle {
            font-size: 0.6rem;
            color: #6b7280;
            text-align: center;
            margin: 0;
        }

        .back-divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 8px 0;
        }

        .qr-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-img {
            width: 70px;
            height: 70px;
        }

        .disclaimer {
            font-size: 0.45rem;
            color: #6b7280;
            text-align: center;
            margin: 8px 0;
            background: #f9fafb;
            padding: 5px;
            border-radius: 4px;
        }

        .back-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
            font-size: 0.5rem;
            color: #9ca3af;
        }

        .taxpayer-info-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 10px;
            border-bottom: 2px solid #FF8008;
            padding-bottom: 5px;
            width: 100%;
            text-align: center;
        }

        /* --- PRINT SETTINGS --- */
        @media print {
            body {
                padding: 0;
                background: white !important;
            }

            .print-actions,
            .taxpayer-info-label {
                display: none !important;
            }

            .taxpayer-page {
                box-shadow: none !important;
                border: none !important;
                padding: 0;
                margin-bottom: 0;
                page-break-after: always;
                height: 100vh;
                display: flex;
                justify-content: center;
                gap: 20px;
            }

            .card-print-container {
                box-shadow: none !important;
                border: 0.5px solid #eee !important;
                margin-bottom: 0;
            }

            .card-front {
                background: #FF8008 !important;
                background-image: linear-gradient(#ffdc82 10%, #FF8008 100%) !important;
            }
        }
    </style>
</head>

<body>



    @foreach ($commercants as $commercant)
        <div class="taxpayer-page">
            <div class="taxpayer-info-label">
                Contribuable : {{ $commercant->nom }} ({{ $commercant->num_commerce }})
            </div>

            <!-- RECTO -->
            <div class="card-print-container">
                <div class="card-front">
                    <div class="bg-overlay"></div>
                    <div class="card-content-wrapper">
                        <div class="card-header-text text-center" style="text-align: center;">
                            <h1 class="card-title">CARTE DE CONTRIBUABLE</h1>
                            <h2 class="card-subtitle">{{ $commercant->nom }}</h2>
                        </div>
                        <hr class="card-divider">

                        <div class="card-body">
                            <div class="photo-frame">
                                <img src="{{ $commercant->photo_profil ? asset('storage/' . $commercant->photo_profil) : asset('images/default_avatar.jpg') }}"
                                    class="profile-img">
                            </div>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">ID commerce:</span>
                                    <span class="info-value">{{ $commercant->num_commerce }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">SECTEUR:</span>
                                    <span
                                        class="info-value">{{ $commercant->secteur->nom ?? 'Commerce Général' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">MAIRIE:</span>
                                    <span
                                        class="info-value">{{ $commercant->mairie->name ?? ($commercant->mairie->nom ?? 'N/A') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            VALIDE POUR L'ANNÉE {{ date('Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- VERSO -->
            <div class="card-print-container">
                <div class="card-back">
                    <h1 class="back-title">SCAN DE CONTRÔLE</h1>
                    <p class="back-subtitle">Identifiant unique du contribuable</p>

                    <div class="back-divider"></div>

                    <div class="qr-container">
                        @if ($commercant->qr_code_path)
                            <img src="{{ asset('storage/' . $commercant->qr_code_path) }}" alt="QR Code"
                                class="qr-img">
                        @else
                            <div style="font-size: 0.6rem; color: #9ca3af;">QR NON GÉNÉRÉ</div>
                        @endif
                    </div>

                    <div class="disclaimer">
                        Cette carte est la propriété de l'administration fiscale locale. Elle doit être présentée lors
                        de tout
                        contrôle.
                    </div>

                    <div class="back-footer">
                        <div class="footer-brand">ANATH - SYSTÈME SÉCURISÉ</div>
                        <div class="footer-version">REF: {{ substr($commercant->num_commerce, -6) }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="print-actions">
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Lancer l'impression
        </button>
        <button class="btn-print" style="background: #4b5563;" onclick="window.history.back()">
            Retour
        </button>
    </div>
    <script>
        window.onload = function() {
            // Optionnel : auto-print après un court délai pour laisser les images charger
            /*
            setTimeout(function() {
                window.print();
            }, 1000);
            */
        };
    </script>
</body>

</html>
