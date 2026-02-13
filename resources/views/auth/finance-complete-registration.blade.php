<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser Inscription Finance</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">

    <!-- Notre CSS personnalisé (doit venir APRES Bootstrap) -->
    <link rel="stylesheet" href="{{ asset('css/style_auth.css') }}">


    <!-- Font Awesome pour les icônes (optionnel mais recommandé pour le bouton) -->
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Finaliser l'inscription de l'Agent Finance / Caissier</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('mairie.complete-registration.store') }}">
                            @csrf

                            <!-- Champ email caché mais présent -->
                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="mb-3">
                                <label for="email_display" class="form-label">Adresse e-mail</label>
                                <input id="email_display" type="email" class="form-control"
                                    value="{{ $email }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="otp_code" class="form-label">Code OTP (reçu par e-mail)</label>
                                <input id="otp_code" type="text"
                                    class="form-control @error('otp_code') is-invalid @enderror" name="otp_code"
                                    required autofocus>
                                @error('otp_code')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Nouveau mot de passe</label>
                                <div class="input-group">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback"
                                        role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">Confirmer le mot de passe</label>
                                <div class="input-group">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="password-confirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Activer mon compte
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
