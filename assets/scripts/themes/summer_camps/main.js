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
 import WeekPicker from './components/week_picker';
 import CalendarFilter from '../../components/calendar_filter';
 import Search from '../../search';

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
        let { week=-1, event_category, event_audience } = qs.parse(window.location.search.substr(1));
        if(!event_category) {
          event_category = parseInt($('#react-event-filter').data('category_id'));
        }
        if(!event_audience) {
          event_audience = parseInt($('#react-audience-filter').data('audience_id'));
        }
        ReactDOM.render(<WeekPicker startDate="2017-06-12" week={week} weekCount={11} />, document.getElementById('react-week-picker'));
        ReactDOM.render(<CalendarFilter term="categories" control_label="Sort by" default_label="All Camps" event_item={event_category} form_item="event_category" />, document.getElementById('react-event-filter'));
        ReactDOM.render(<CalendarFilter term="audiences" control_label="Sort by" default_label="All Ages" event_item={event_audience} form_item="event_audience" />, document.getElementById('react-audience-filter'));

        $('.clear-filter').click(function(e) {
          const input_name = $(this).data('input');
          const input_field = $(`[name=${input_name}]`);
          input_field.val(0).closest('form').submit();
        });
      }
    },
    'search': {
      init: function() {
        Search.highlightMatches();
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
