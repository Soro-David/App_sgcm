<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

{{-- CORRECTION : Le token CSRF doit être ici, une seule fois. --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'Gestion Foncière de Mairie')</title>

{{-- Styles CSS --}}
<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/typicons/typicons.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/style_mairie.css') }}">
<link rel="stylesheet" href="{{ asset('css/style_page.css') }}">
<link rel="stylesheet" href="{{ asset('css/modals/mairie.css') }}">
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

{{-- CSS des librairies externes --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

{{-- Emplacement pour des CSS spécifiques à une page --}}
@stack('css')
