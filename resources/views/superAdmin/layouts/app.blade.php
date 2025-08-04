{{-- resources/views/superAdmin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  {{-- CORRECTION : Le token CSRF doit être ici, une seule fois. --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <title>@yield('title', 'Gestion Foncière de Mairie')</title>

  {{-- Styles CSS --}}
  <link rel="stylesheet" href="{{ asset('assets/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style_superadmin.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style_page.css') }}">
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />

  {{-- CSS des librairies externes --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

  {{-- Emplacement pour des CSS spécifiques à une page --}}
  @stack('css')
</head>
<body>
  <div class="container-scroller">
    @include('superAdmin.layouts.partials.navbar')

    <div class="container-fluid page-body-wrapper">
      @include('superAdmin.layouts.partials.sidebar')
      
      <div class="main-panel">
        <div class="content-wrapper">
          {{-- Le contenu de la page sera injecté ici --}}
          @yield('content')
        </div>

        @include('superAdmin.layouts.partials.footer')
      </div>
    </div>
  </div>

  {{-- CORRECTION : Charger les librairies JS de base ici, à la fin du body --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  {{-- Scripts de base de votre template --}}
  <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('assets/js/template.js') }}"></script>
  <script src="{{ asset('assets/js/settings.js') }}"></script>
  <script src="{{ asset('assets/js/todolist.js') }}"></script>

  {{-- CORRECTION : Emplacement pour les scripts JS spécifiques à chaque page --}}
  @stack('js')

</body>
</html>