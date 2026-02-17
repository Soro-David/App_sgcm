<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié | SGTC</title>

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style_auth.css') }}">
</head>

@php
    $guard = request('guard', 'mairie');
    $brandName = match ($guard) {
        'mairie' => 'ESPACE MAIRIE',
        'agent' => 'ESPACE AGENT',
        'commercant' => 'ESPACE COMMERÇANT',
        default => 'SGTC',
    };
    $iconClass = match ($guard) {
        'mairie' => 'fas fa-city',
        'agent' => 'fas fa-user-shield',
        'commercant' => 'fas fa-store',
        default => 'fas fa-shield-alt',
    };
    $backRoute = match ($guard) {
        'mairie' => 'login.mairie',
        'agent' => 'login.agent',
        'commercant' => 'login.commercant',
        default => 'login',
    };
@endphp

<body>

    <a href="{{ route($backRoute) }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="login-wrapper">
        <div class="login-card">
            <!-- Part Gauche -->
            <div class="login-left">
                <div class="branding">
                    <div class="logo-box">
                        <i class="{{ $iconClass }}"></i>
                    </div>
                    <span class="brand-name">{{ $brandName }}</span>
                </div>

                <div class="left-content">
                    <h1>Mot de passe oublié ?</h1>
                    <p>Ne vous inquiétez pas, cela arrive. Entrez votre email pour recevoir un lien de réinitialisation.
                    </p>
                </div>

                <div class="left-footer">
                    <p>© 2026 SGTC. Tous droits réservés.</p>
                </div>
            </div>

            <!-- Part Droite -->
            <div class="login-right">
                <div class="form-header">
                    <h2>Réinitialisation</h2>
                    <p>Entrez l'adresse e-mail associée à votre compte.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success py-2" style="font-size: 14px; border-radius: 10px;">
                        <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger py-2" style="font-size: 14px; border-radius: 10px;">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <input type="hidden" name="guard" value="{{ $guard }}">

                    <div class="mb-4">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group-custom">
                            <i class="far fa-envelope"></i>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Ex: jean.dupont@email.com" required value="{{ old('email') }}">
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        Envoyer le lien
                    </button>

                    <div class="signup-text">
                        Vous vous en souvenez ? <a href="{{ route($backRoute) }}">Retour à la connexion</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
