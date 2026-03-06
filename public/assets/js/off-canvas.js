(function($) {
  'use strict';
  $(function() {

    /* -------------------------------------------------------
     * helper: is mobile viewport?
     * ------------------------------------------------------ */
    function isMobile() {
      return window.matchMedia('(max-width: 991px)').matches;
    }

    /* -------------------------------------------------------
     * helper: toggle sidebar + overlay class on body
     * ------------------------------------------------------ */
    function toggleSidebar() {
      var isOpen = $('.sidebar-offcanvas').hasClass('active');
      $('.sidebar-offcanvas').toggleClass('active');
      if (isMobile()) {
        $('body').toggleClass('sidebar-open', !isOpen);
      }
    }

    function closeSidebar() {
      $('.sidebar-offcanvas').removeClass('active');
      $('body').removeClass('sidebar-open');
    }

    /* -------------------------------------------------------
     * 1. Bouton hamburger existant (data-toggle="offcanvas")
     * ------------------------------------------------------ */
    $('[data-toggle="offcanvas"]').on('click', function () {
      toggleSidebar();
    });

    /* -------------------------------------------------------
     * 2. Logo SGTC (navbar-brand-wrapper) → toggle sidebar en mobile
     *    On intercepte le clic sur le wrapper entier pour ne pas
     *    avoir à modifier chaque navbar blade.
     * ------------------------------------------------------ */
    $(document).on('click', '.navbar-brand-wrapper', function (e) {
      if (!isMobile()) return; // desktop : comportement normal

      // Ne pas bloquer les clics sur le bouton minimize (data-toggle="minimize")
      if ($(e.target).closest('[data-toggle="minimize"]').length) return;

      // Empêcher la navigation du lien logo en mobile
      e.preventDefault();
      e.stopPropagation();

      toggleSidebar();
    });

    /* -------------------------------------------------------
     * 3. Fermer le sidebar en cliquant en dehors (overlay ou fond)
     * ------------------------------------------------------ */
    $(document).on('click', function (e) {
      if (!isMobile()) return;

      var $sidebar  = $('.sidebar-offcanvas');
      var $brand    = $('.navbar-brand-wrapper');
      var $toggler  = $('[data-toggle="offcanvas"]');

      if (
        $sidebar.hasClass('active') &&
        !$sidebar.is(e.target) && $sidebar.has(e.target).length === 0 &&
        !$brand.is(e.target)   && $brand.has(e.target).length === 0 &&
        !$toggler.is(e.target) && $toggler.has(e.target).length === 0
      ) {
        closeSidebar();
      }
    });

    /* -------------------------------------------------------
     * 4. Curseur pointer sur le wrapper en mobile (ajout dynamique)
     * ------------------------------------------------------ */
    function updateBrandCursor() {
      if (isMobile()) {
        $('.navbar-brand-wrapper').css('cursor', 'pointer');
      } else {
        $('.navbar-brand-wrapper').css('cursor', '');
        closeSidebar(); // assure que l'état est propre si on redimensionne en desktop
      }
    }
    updateBrandCursor();
    $(window).on('resize', updateBrandCursor);

  });
})(jQuery);