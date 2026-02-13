{{-- CORRECTION : Charger les librairies JS de base ici, à la fin du body --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('assets/js/datatable-config.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Scripts de base de votre template --}}
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

{{-- CORRECTION : Emplacement pour les scripts JS spécifiques à chaque page --}}
@stack('js')

<script>
    $(document).ready(function() {
        // Toggle sidebar on mobile click outside
        $(document).on('click touchstart', function(e) {
            if ($(window).width() < 992) {
                var sidebar = $('#sidebar');
                var toggler = $('[data-toggle="offcanvas"]');

                // Si le clic est en dehors du sidebar ET du bouton toggle
                if (!sidebar.is(e.target) && sidebar.has(e.target).length === 0 &&
                    !toggler.is(e.target) && toggler.has(e.target).length === 0) {
                    sidebar.removeClass('active');
                }
            }
        });

        // Fermer le sidebar après avoir cliqué sur un lien (optionnel mais recommandé sur mobile)
        $('.sidebar .nav-link').on('click', function() {
            if ($(window).width() < 992) {
                $('#sidebar').removeClass('active');
            }
        });
    });
</script>
