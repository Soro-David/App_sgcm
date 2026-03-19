<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />

    <title>Accès Interdit | SGTC</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background-color: #f4f6f9;
            color: #001737;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .error-card {
            background: #ffffff;
            padding: 3rem;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
            text-align: center;
            max-width: 480px;
            width: 90%;
            border: 1px solid rgba(247, 127, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .error-card:hover {
            transform: translateY(-5px);
        }

        .icon-wrapper {
            background: #fff5e6;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            box-shadow: 0 10px 20px rgba(247, 127, 0, 0.1);
        }

        .icon-wrapper i {
            font-size: 3.5rem;
            color: #f77f00;
        }

        .error-code {
            font-size: 5rem;
            font-weight: 800;
            margin: 0;
            line-height: 1;
            color: #f77f00;
            opacity: 0.9;
        }

        .error-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 1rem 0 0.5rem;
            color: #001737;
        }

        .error-message {
            color: #6c7293;
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 2.5rem;
        }

        .btn-home {
            background: #f77f00;
            color: #ffffff;
            padding: 14px 40px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(247, 127, 0, 0.3);
            border: 2px solid #f77f00;
        }

        .btn-home:hover {
            background: #ffffff;
            color: #f77f00;
            box-shadow: 0 6px 20px rgba(247, 127, 0, 0.4);
            transform: translateY(-2px);
        }

        .btn-home i {
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <div class="error-card">
        <div class="icon-wrapper">
            <i class="fas fa-lock text-warning"></i>
        </div>
        <h1 class="error-code">403</h1>
        <h2 class="error-title">Accès interdit</h2>
        <p class="error-message">(rôle non autorisé)</p>
        
        <a href="{{ url('/') }}" class="btn-home">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>
</body>

</html>
