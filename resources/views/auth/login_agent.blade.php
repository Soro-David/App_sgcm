<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Agent | SGTC</title>

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
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <span class="brand-name">ESPACE AGENT</span>
                </div>

                <div class="left-content">
                    <h1>Gérez vos activités en toute simplicité.</h1>
                    <p>Accédez à votre tableau de bord pour suivre vos encaissements, gérer les contribuables et rester
                        efficace.</p>
                </div>

                <div class="left-footer">
                    <p>© 2026 E-Messe. Tous droits réservés.</p>
                </div>
            </div>

            <!-- Part Droite -->
            <div class="login-right">
                <div class="form-header">
                    <h2>Bon retour !</h2>
                    <p>Veuillez entrer vos informations d'agent.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2" style="font-size: 14px; border-radius: 10px;">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ $errors->first('email', 'Email ou mot de passe invalide.') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.agent') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email ou Nom d'utilisateur</label>
                        <div class="input-group-custom">
                            <i class="far fa-user"></i>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="votre@email.com" required value="{{ old('email') }}">
                        </div>
                    </div>

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

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" class="form-check-input">
                            <span>Se souvenir de moi</span>
                        </label>
                        <a href="{{ route('password.request', ['guard' => 'agent']) }}" class="forgot-password">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    <button type="submit" class="btn-login">
                        Se connecter
                    </button>

                    <div class="divider">
                        <span>OU</span>
                    </div>

                    <div class="signup-text">
                        Besoin d'aide ? <a href="#">Contactez le support</a>
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
