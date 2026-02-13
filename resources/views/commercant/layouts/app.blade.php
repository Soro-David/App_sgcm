<!DOCTYPE html>
<html lang="fr">

<head>
    @include('commercant.layouts.partials.head')
</head>

<body>
    <div class="container-scroller">
        @include('commercant.layouts.partials.navbar')

        <div class="container-fluid page-body-wrapper mt-3">
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
