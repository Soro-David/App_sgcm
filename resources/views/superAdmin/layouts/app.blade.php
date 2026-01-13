{{-- resources/views/superAdmin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    @include('superAdmin.layouts.partials.head')
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

    @include('superAdmin.layouts.partials.scripts')
</body>

</html>
