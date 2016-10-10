/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

// const device = require('./vendors/device');
// const slick = require('slick-carousel');

(function($) {
    // The routing fires all common scripts, followed by the page specific scripts.
    // Add additional events for more control over timing e.g. a finalize event
    const createMap = function(target,lat, long) {
        var map = new google.maps.Map(target, {
          center: {lat: lat, lng: long},
          scrollwheel: false,
          zoom: 15
        });

        var marker = new google.maps.Marker({
          map: map,
          position: {lat: lat, lng: long},
        });
    };
    const UTIL = {
        fire: function(func, funcname, args) {
            var fire;
            var namespace = Sage;
            funcname = (funcname === undefined) ? 'init' : funcname;
            fire = func !== '';
            fire = fire && namespace[func];
            fire = fire && typeof namespace[func][funcname] === 'function';

            if (fire) {
                namespace[func][funcname](args);
            }
        },
        loadEvents: function() {
            // Fire common init JS
            UTIL.fire('common');

            // Fire page-specific init JS, and then finalize JS
            $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
                UTIL.fire(classnm);
                UTIL.fire(classnm, 'finalize');
            });

            // Fire common finalize JS
            UTIL.fire('common', 'finalize');
        },
        scrollTo(target) {
            $('html, body').animate({
              scrollTop: (target.offset().top + 100) - $('header.header').height()
            }, 1000);
            return false;
        }
    };

    // Use this variable to set up the common and page specific functions. If you
    // rename this variable, you will also need to rename the namespace below.

    let Sage = {
        // All pages
        'common': {
            init: function() {
                // JavaScript to be fired on all pages
            },
            finalize: function() {
                $('html.desktop .menu-item-has-children').hover(function(){
                    $(this).addClass('hover');
                }, function() {
                    $(this).removeClass('hover');
                });
                $('.acf-map').each(function() {
                    var lat = parseFloat($(this).attr('data-lat'));
                    var long = parseFloat($(this).attr('data-long'));
                    console.log('create map:', lat, long);
                    createMap(this, lat, long);
                });
                // JavaScript to be fired on all pages, after page specific JS is fired
                // $('.slider').slick({
                //     arrows: false,
                //     autoplay: true,
                //     autoplaySpeed: 4000,
                //     dots: true,
                //     draggable: false,
                //     pauseOnFocus: false,
                //     pauseOnHover: false
                // });
            }
        },
        // Home page
        'home': {
            init: function() {
                // JavaScript to be fired on the home page
                $('.hero__arrow').click(function() {
                    UTIL.scrollTo($('.grid-widget'));
                });
            },
            finalize: function() {
                // JavaScript to be fired on the home page, after the init JS
            }
        },
        // About us page, note the change from about-us to about_us.
        'about_us': {
            init: function() {
                // JavaScript to be fired on the about us page
            }
        }
    };

    // Load Events
    $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
