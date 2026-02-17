<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | E-Messe</title>

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

    <style>
        .login-card {
            min-height: 700px;
        }
    </style>
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
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <span class="brand-name">REJOIGNEZ-NOUS</span>
                </div>

                <div class="left-content">
                    <h1>Créez votre compte en quelques secondes.</h1>
                    <p>Faites partie de notre communauté et commencez à gérer vos activités de manière moderne et
                        simplifiée.</p>
                </div>

                <div class="left-footer">
                    <p>© 2026 E-Messe. Tous droits réservés.</p>
                </div>
            </div>

            <!-- Part Droite -->
            <div class="login-right">
                <div class="form-header">
                    <h2>Inscription</h2>
                    <p>Veuillez remplir les informations ci-dessous.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2" style="font-size: 14px; border-radius: 10px;">
                        <i class="fas fa-exclamation-circle me-2"></i> Erreur lors de l'inscription.
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet</label>
                        <div class="input-group-custom">
                            <i class="far fa-user"></i>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Jean Dupont" required value="{{ old('name') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <div class="input-group-custom">
                            <i class="far fa-envelope"></i>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="votre@email.com" required value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
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
                        <div class="col-md-6 mb-3">
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
                    </div>

                    <div class="mb-4">
                        <label for="role" class="form-label">Rôle</label>
                        <select class="form-select" id="role" name="role" required
                            style="border-radius: 12px; padding: 12px; border: 1px solid #e0e0e0; background-color: #fcfcfc;">
                            <option value="superadmin" @if (old('role') == 'admin') selected @endif>Super Admin
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn-login">
                        S'inscrire
                    </button>

                    <div class="signup-text">
                        Vous avez déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
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
