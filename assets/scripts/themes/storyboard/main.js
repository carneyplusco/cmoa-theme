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

require('../../array_polyfill');


import SiteNavigation from '../../navigation';
import ShareButtons from '../../share_buttons';
import Search from '../../search';
import { hasTouch } from '../../events';

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
        SiteNavigation.init();
        ShareButtons.init();
        $('.print-page').click(() => window.print());
      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    'search': {
      init: function() {
        Search.highlightMatches();
      }
    },
    'single': {
      init: function() {
        $('.social-links__open').click(function(e) {
          e.preventDefault();
          $(this).toggleClass('active');
        });

        if(!hasTouch()) {
          $('.cmoa-footnotes__index a').qtip({
            position: {
              my: 'top right',
              at: 'bottom center'
            },
            style: {
              classes: 'qtip-light qtip-shadow cmoa-footnotes__tooltip'
            }
          });
        }
      }
    }
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
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
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
