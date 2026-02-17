<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser Inscription Mairie | SGTC</title>

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

<body>

    <a href="/" class="back-button">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="login-wrapper">
        <div class="login-card">
            <!-- Part Gauche -->
            <div class="login-left">
                <div class="branding">
                    <div class="logo-box">
                        <i class="fas fa-city"></i>
                    </div>
                    <span class="brand-name">ESPACE MAIRIE</span>
                </div>

                <div class="left-content">
                    <h1>Bienvenue à la Mairie.</h1>
                    <p>Veuillez activer votre compte administratif en validant votre code OTP et en choisissant un mot
                        de passe robuste.</p>
                </div>

                <div class="left-footer">
                    <p>© 2026 SGTC. Tous droits réservés.</p>
                </div>
            </div>

            <!-- Part Droite -->
            <div class="login-right">
                <div class="form-header">
                    <h2>Finalisation</h2>
                    <p>Activation de l'accès administratif.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2" style="font-size: 14px; border-radius: 10px;">
                        <i class="fas fa-exclamation-circle me-2"></i> Erreur lors de la finalisation.
                    </div>
                @endif

                <form method="POST" action="{{ route('mairie.complete-registration.store') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mb-3">
                        <label class="form-label">Email Professionnel</label>
                        <div class="input-group-custom">
                            <i class="far fa-envelope"></i>
                            <input type="email" class="form-control" value="{{ $email }}" disabled>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="otp_code" class="form-label">Code OTP</label>
                        <div class="input-group-custom">
                            <i class="fas fa-key"></i>
                            <input id="otp_code" type="text"
                                class="form-control @error('otp_code') is-invalid @enderror" name="otp_code"
                                placeholder="Code de sécurité" required autofocus>
                            @error('otp_code')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <div class="input-group-custom">
                                <i class="fas fa-lock"></i>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="........" required>
                                <button class="password-toggle toggle-password" type="button" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmation</label>
                            <div class="input-group-custom">
                                <i class="fas fa-lock"></i>
                                <input type="password" class="form-control" id="password-confirm"
                                    name="password_confirmation" placeholder="........" required>
                                <button class="password-toggle toggle-password" type="button"
                                    data-target="password-confirm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                    <button type="submit" class="btn-login">
                        Activer l'accès
                    </button>

                    <div class="signup-text">
                        Déjà activé ? <a href="{{ route('login.mairie') }}">Se connecter</a>
                    </div>
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
