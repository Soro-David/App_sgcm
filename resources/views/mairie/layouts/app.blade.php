{{-- resources/views/superAdmin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    @include('mairie.layouts.partials.head')
    {{-- Override final : supprime le padding-left du container Bootstrap (CDN chargé en dernier) --}}
    <style>
        .page-body-wrapper,
        div.page-body-wrapper,
        .container-fluid.page-body-wrapper {
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        @include('mairie.layouts.partials.navbar')

        <div class="container-fluid page-body-wrapper">
            @include('mairie.layouts.partials.sidebar')


            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                @include('mairie.layouts.partials.footer')
            </div>
        </div>
    </div>

    @include('mairie.layouts.partials.scripts')

</body>

</html>
