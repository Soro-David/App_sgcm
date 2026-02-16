<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">

    <!-- Notre CSS personnalisé -->
    <link rel="stylesheet" href="{{ asset('css/style_auth.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
</head>
@php
    $guard = request('guard', 'mairie');
    $bgImage =
        [
            'mairie' => 'login1.jpg',
            'agent' => 'login2.jpg',
            'commercant' => 'login3.jpg',
            'web' => 'login4.jpg',
        ][$guard] ?? 'login1.jpg';

    $backRoute =
        [
            'mairie' => 'login.mairie',
            'agent' => 'login.agent',
            'commercant' => 'login.commercant',
            'web' => 'login',
        ][$guard] ?? 'login.mairie';
@endphp
<style>
    body {
        font-size: 1.1rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        background: url("{{ asset('assets/images/login/' . $bgImage) }}") no-repeat center center fixed;
        background-size: cover;
    }
</style>

<body>

    <div class="card shadow-sm auth-card">
        <div class="card-body p-4 p-md-5">
            <h2 class="card-title text-center mb-4">Mot de passe oublié</h2>

            <p class="text-muted text-center mb-4">
                Entrez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.
            </p>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <input type="hidden" name="guard" value="{{ $guard }}">

                <div class="mb-4">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control form-control-lg" id="email" name="email" required
                        value="{{ old('email') }}" placeholder="votre@email.com">
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane me-2"></i> Envoyer le lien
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route($backRoute) }}" class="text-decoration-none text-primary">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la connexion
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
