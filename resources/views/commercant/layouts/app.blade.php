<!DOCTYPE html>
<html lang="fr">

<head>
    @include('commercant.layouts.partials.head')
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
        @include('commercant.layouts.partials.navbar')

        <div class="container-fluid page-body-wrapper">
            @include('commercant.layouts.partials.sidebar')

            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>

                @include('commercant.layouts.partials.footer')
            </div>
        </div>
    </div>

    @include('commercant.layouts.partials.scripts')
</body>

</html>
