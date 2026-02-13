<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser l'inscription - Commerçant</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_auth.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h4 class="mb-0"><i class="fas fa-store me-2"></i>Finaliser l'inscription</h4>
                        <p class="mb-0 mt-2 small text-white-50">Activation de votre compte commerçant</p>
                    </div>
                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('commercant.complete-registration.store') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">

                            <!-- Email Display -->
                            <div class="mb-4">
                                <label class="form-label text-muted fw-bold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i
                                            class="fas fa-envelope text-muted"></i></span>
                                    <input type="text" class="form-control" value="{{ $email }}" disabled>
                                </div>
                            </div>

                            <!-- OTP Code -->
                            <div class="mb-4">
                                <label for="otp_code" class="form-label text-muted fw-bold">Code OTP</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-key text-muted"></i></span>
                                    <input id="otp_code" type="text"
                                        class="form-control @error('otp_code') is-invalid @enderror" name="otp_code"
                                        placeholder="Entrez le code reçu par mail" required autofocus>
                                </div>
                                <div class="form-text">Ce code a été envoyé sur votre adresse e-mail.</div>
                                @error('otp_code')
                                    <span class="text-danger small mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label text-muted fw-bold">Nouveau Mot de Passe</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i
                                            class="fas fa-lock text-muted"></i></span>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="text-danger small mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label text-muted fw-bold">Confirmer le
                                    Mot de Passe</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i
                                            class="fas fa-lock text-muted"></i></span>
                                    <input id="password_confirmation" type="password" class="form-control"
                                        name="password_confirmation" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                    Activer mon compte <i class="fas fa-check-circle ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-3 text-muted small">
                    &copy; {{ date('Y') }} - Plateforme de Gestion
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
