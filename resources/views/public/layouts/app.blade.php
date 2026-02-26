<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SGTC - Système de Gestion des Taxes Communales')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/public_style.css') }}">
    @yield('styles')
</head>

<body>
    @include('public.layouts.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('public.layouts.partials.footer')

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#mobile-menu-btn').click(function() {
                $('#nav-content').toggleClass('active');
                $(this).find('i').toggleClass('fa-bars fa-times');
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
