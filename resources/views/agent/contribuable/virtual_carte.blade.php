@extends('agent.layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center py-5">
        <div class="virtual-card-wrapper">
            <!-- Carte Virtuelle -->
            <div class="virtual-card shadow-lg" id="printableArea">
                <!-- Bandeaux Décoratifs -->
                <div class="card-header-band top"></div>

                <div class="card-body p-0 position-relative">
                    <!-- Watermark Background -->
                    <div class="watermark">
                        <i class="fas fa-university"></i>
                    </div>

                    <div class="row g-0 h-100">
                        <!-- Section Gauche : Photo & ID -->
                        <div
                            class="col-4 left-section d-flex flex-column align-items-center justify-content-center text-white text-center p-3">
                            <div class="photo-container mb-3">
                                <img src="{{ $commercant->photo_profil ? Storage::url($commercant->photo_profil) : asset('images/default_avatar.jpg') }}"
                                    alt="Photo de profil" class="profile-photo">
                            </div>
                            <h6 class="mb-1 text-uppercase fw-bold text-white small-id-label">ID Commerce</h6>
                            <h4 class="mb-0 fw-bolder text-warning tracking-wide">{{ $commercant->num_commerce }}</h4>

                            <div class="mt-4 w-100">
                                <div class="qr-box bg-white p-2 rounded shadow-sm d-inline-block">
                                    @if ($commercant->qr_code_path)
                                        <img src="{{ Storage::url($commercant->qr_code_path) }}" alt="QR Code"
                                            class="img-fluid" style="width: 80px; height: 80px;">
                                    @else
                                        <span class="text-danger small">QR N/A</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Section Droite : Informations -->
                        <div class="col-8 right-section p-4 position-relative">
                            <!-- En-tête Carte -->
                            <div class="text-center mb-4 border-bottom pb-2">
                                <h5 class="text-uppercase fw-bold text-primary mb-1 letter-spacing-1">Carte de Contribuable
                                </h5>
                                <p class="mb-0 text-muted text-uppercase small fw-bold">
                                    {{ $commercant->mairie->nom ?? 'MAIRIE' }}
                                </p>
                            </div>

                            <!-- Détails -->
                            <div class="info-grid">
                                <div class="info-item mb-3">
                                    <label class="text-secondary text-uppercase x-small fw-bold mb-0">Nom & Prénoms</label>
                                    <div class="fw-bold fs-5 text-dark">{{ $commercant->nom }}</div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="info-item">
                                            <label
                                                class="text-secondary text-uppercase x-small fw-bold mb-0">Téléphone</label>
                                            <div class="fw-bold text-dark">{{ $commercant->telephone ?? '-' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="info-item">
                                            <label class="text-secondary text-uppercase x-small fw-bold mb-0">Secteur
                                                d'activité</label>
                                            <div class="fw-bold text-dark text-truncate">
                                                {{ $commercant->secteur->nom ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="info-item">
                                        <label class="text-secondary text-uppercase x-small fw-bold mb-0">Type de
                                            Contribuable</label>
                                        <div class="fw-bold text-dark">{{ $commercant->typeContribuable->nom ?? '-' }}</div>
                                    </div>
                                </div>

                                <div class="mb-1">
                                    <div class="info-item">
                                        <label class="text-secondary text-uppercase x-small fw-bold mb-0">Localisation /
                                            Adresse</label>
                                        <div class="fw-bold text-dark small">{{ $commercant->adresse ?? 'Non définie' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Décoratif Droite -->
                            <div class="bottom-branding text-end mt-3">
                                <span class="badge bg-light text-secondary border">Valide</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-header-band bottom"></div>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-center mt-4 no-print gap-3">
                <a href="{{ route('agent.contribuable.index') }}" class="btn btn-secondary px-4 rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                </a>
                <button onclick="window.print()" class="btn btn-primary px-4 rounded-pill">
                    <i class="fas fa-print me-2"></i> Imprimer la carte
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Variables */
        :root {
            --card-width: 600px;
            /* Format standard approximatif */
            --card-height: 350px;
            --brand-color: #0d6efd;
            --secondary-color: #2c3e50;
            --accent-color: #f39c12;
        }

        /* Styles Généraux */
        .virtual-card-wrapper {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        .virtual-card {
            width: var(--card-width);
            height: var(--card-height);
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
            position: relative;
        }

        /* Bandes Décoratives */
        .card-header-band {
            height: 8px;
            width: 100%;
            background: linear-gradient(90deg, var(--brand-color) 0%, #6610f2 50%, var(--accent-color) 100%);
        }

        /* Section Gauche */
        .left-section {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #1a252f 100%);
            position: relative;
            z-index: 2;
        }

        .photo-container {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            background: #fff;
        }

        .profile-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .small-id-label {
            font-size: 0.7rem;
            letter-spacing: 1px;
            opacity: 0.8;
        }

        .tracking-wide {
            letter-spacing: 2px;
        }

        /* Section Droite */
        .right-section {
            background: #ffffff;
            z-index: 2;
        }

        .x-small {
            font-size: 0.7rem;
        }

        .letter-spacing-1 {
            letter-spacing: 1px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 60%;
            transform: translate(-50%, -50%);
            font-size: 15rem;
            color: rgba(0, 0, 0, 0.03);
            z-index: 1;
            pointer-events: none;
        }

        .info-item {
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 2px;
        }

        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }

            .virtual-card-wrapper * {
                visibility: visible;
            }

            .no-print {
                display: none !important;
            }

            .virtual-card {
                position: absolute;
                left: 0;
                top: 0;
                z-index: 9999;
                box-shadow: none !important;
                border: 1px solid #ddd;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .container {
                width: auto;
                max-width: none;
                padding: 0;
                margin: 0;
            }
        }
    </style>
@endsection
