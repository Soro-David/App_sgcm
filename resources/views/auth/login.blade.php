<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">

    <!-- Notre CSS personnalisé (doit venir APRES Bootstrap) -->
    <link rel="stylesheet" href="{{ asset('css/style_auth.css') }}">


    <!-- Font Awesome pour les icônes (optionnel mais recommandé pour le bouton) -->
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

</head>

<style>
    body {
        font-size: 1.1rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        background: url("{{ asset('assets/images/login/login4.jpg') }}") no-repeat center center fixed;
        background-size: cover;
        /* adapte l’image à toute la page */
    }
</style>

<body>

    <div class="card shadow-sm auth-card">
        <div class="card-body p-4 p-md-5">
            <h2 class="card-title text-center mb-4">Connexion</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first('email', 'Email ou mot de passe invalide.') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control form-control-lg" id="email" name="email" required
                        value="{{ old('email') }}">
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-lg" id="password" name="password"
                            required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="text-end mt-2">
                        <a href="{{ route('password.request', ['guard' => 'web']) }}" class="text-muted small">Mot de
                            passe oublié ?</a>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                    </button>
                </div>
                <div class="text-center mt-4 fw-light">
                    Vous n'avez pas de compte ? <a href="{{ route('register') }}" class="text-primary">Créer un
                        compte</a>
                </div>

            </form>
        </div>
    </div>

    <!-- Bootstrap JS (Optionnel, mais bon à avoir) -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function(e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
