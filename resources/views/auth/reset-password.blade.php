<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe | SGTC</title>

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
    $guard = $guard ?? 'mairie';
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
@endphp

<body>

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
                    <h1>Sécurisez votre compte.</h1>
                    <p>Définissez un nouveau mot de passe pour reprendre le contrôle de votre espace de gestion.</p>
                </div>

                <div class="left-footer">
                    <p>© 2026 SGTC. Tous droits réservés.</p>
                </div>
            </div>

            <!-- Part Droite -->
            <div class="login-right">
                <div class="form-header">
                    <h2>Nouveau mot de passe</h2>
                    <p>Veuillez entrer votre nouveau mot de passe ci-dessous.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2" style="font-size: 14px; border-radius: 10px;">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="guard" value="{{ $guard }}">

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="input-group-custom">
                            <i class="fas fa-lock"></i>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="........" required>
                            <button class="password-toggle toggle-password" type="button" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmation</label>
                        <div class="input-group-custom">
                            <i class="fas fa-lock"></i>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="........" required>
                            <button class="password-toggle toggle-password" type="button"
                                data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        Réinitialiser le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
</body>

</html>
