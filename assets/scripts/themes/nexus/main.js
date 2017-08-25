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
 require('picturefill');

 import React from 'react';
 import ReactDOM from 'react-dom';
 import moment from 'moment';
 import qs from 'qs';

 import SiteNavigation from '../../navigation';
 import FormEvents from '../../forms';
 import EventFilter from '../../components/event_filter';

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
        SiteNavigation.init();
        FormEvents.init();
      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // Calendar page
    'calendar': {
      init: function() {
        let { start=moment().format('YYYY-MM-DD'), end=moment().add(30,'d').format('YYYY-MM-DD'), event_category } = qs.parse(window.location.search.substr(1));
        if(!event_category) {
          event_category = parseInt($('#react-event-filter').data('category_id'));
        }
        ReactDOM.render(<EventFilter event_category={event_category} />, document.getElementById('react-event-filter'));
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
