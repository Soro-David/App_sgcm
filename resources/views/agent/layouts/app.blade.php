{{-- resources/views/agent/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    @include('agent.layouts.partials.head')
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
        @include('agent.layouts.partials.navbar')

        <div class="container-fluid page-body-wrapper">
            @include('agent.layouts.partials.sidebar')

            <div class="main-panel">
                <div class="content-wrapper">
                    {{-- Le contenu de la page sera injecté ici --}}
                    @yield('content')
                </div>

                @include('agent.layouts.partials.footer')
            </div>
        </div>
    </div>

    @include('agent.layouts.partials.scripts')
</body>

</html>
