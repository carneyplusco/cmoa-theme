require('waypoints/lib/jquery.waypoints');
var moment = require('moment');
var store = require('store2');

var SiteNavigation = (function($) {

  // main navigation
  ////////////////////////////////////////////////////////////////////////////////
  var quickviewState = false;
  var quickviewBtn;
  var quickviewTarget

  function closeQuickview() {
    $('.quickview-container, .quickview-btn').removeClass('is-active');
    if(quickviewBtn && quickviewState) {
      quickviewBtn.addClass('is-deactivated');
      $('.quickview-btn').attr("aria-expanded","false");
      setTimeout(function () {
        $('.quickview').removeClass('is-active');
      }, 600);
      quickviewState = false;
      ga('send', 'event', quickviewTarget, 'close', 'navigation');
      ga('unified.send', 'event', quickviewTarget, 'close', 'navigation');
    }
  }

  function openQuickview() {
    window.scrollTo(0,0);
    $('.quickview-container').addClass('is-active');
    $('.quickview-btn').removeClass('is-deactivated');
    quickviewBtn.attr("aria-expanded","true");
    $('.quickview').not( $(quickviewTarget) ).removeClass('is-active');
    $(quickviewTarget).addClass('is-active');
    quickviewState = quickviewTarget;
    ga('send', 'event', quickviewTarget, 'open', 'navigation');
    ga('unified.send', 'event', quickviewTarget, 'open', 'navigation');

  }

  // tabs navigation
  ////////////////////////////////////////////////////////////////////////////////
  var tabs = $('.tabs');
  var tabLinks = $('.tabs--links');
  var tabOverflow = $('.tabs--overflow');
  var tabOverflowLinks = $('.tabs--dropdown');

  var numOfItems = 0;
  var totalSpace = 0;
  var breakWidths = [];

  // Get initial state
  tabLinks.children().outerWidth(function(i,w) {
    totalSpace += w;
    numOfItems += 1;
    breakWidths.push(totalSpace);
  });

  var availableSpace, numOfVisibleItems, requiredSpace;

  function checkTabs() {
    // Get instant state
    availableSpace = tabs.width() - 75;
    numOfVisibleItems = tabLinks.children().length;
    requiredSpace = breakWidths[numOfVisibleItems -1];

    // There is not enought space
    if (requiredSpace > availableSpace) {
      tabLinks.children().last().prependTo(tabOverflowLinks);
      numOfVisibleItems -= 1;
      checkTabs();
      // There is more than enough space
    } else if (availableSpace > breakWidths[numOfVisibleItems]) {
      tabOverflowLinks.children().first().appendTo(tabLinks);
      numOfVisibleItems += 1;
    }
    // Update the button accordingly
    tabOverflow.attr('count', numOfItems - numOfVisibleItems);
    if (numOfVisibleItems === numOfItems) {
      tabOverflow.removeClass('is-active');
    } else tabOverflow.addClass('is-active');

    // accessibility
    $(".tabs a").attr("role", "tab");
    $(".tabs a").attr("aria-selected", "false" );
    $(".tabs .current-menu-item a").attr("aria-selected", "true" );
  }

  function formatHours(data) {
    var today = moment().format('dddd');
    var day = data.days.find(d => d.day == today);
    var text = !!day.opens ? `<p class="times">Open Today: <span class="hours"><time itemprop="opens" content="${day.opens_ISO}">${day.opens}</time>-<time itemprop="closes" content="${day.closes_ISO}" >${day.closes}</time></span></p>` : '<p class="times">Closed Today</p>';
    $('.open-times').html(text);
  }

  return {
    init: function() {
      // $(".nav-global").attr("tabindex", "0")

      // toggle quickview
      $('.quickview-btn').click(function(e) {
        quickviewBtn = $(this);
        quickviewTarget = $(this).attr('href');
        e.preventDefault();
        $('.quickview-btn').not(quickviewBtn).removeClass('is-active');
        quickviewBtn.toggleClass('is-active');
        // open:
        if (quickviewState == false || quickviewState != quickviewTarget ) {
          openQuickview();
        }
        // close:
        else {
          closeQuickview();
        }
      });

      // close when clicking away
      $('.quickview-overlay').click(function() {
        closeQuickview();
      });

      // mobile menu functions
      $('#quickview-nav .sub-menu, #quickview-sidebar-nav .sub-menu:first').before('<div class="quickview-nav__expand"></div>');
      $('.quickview-nav__expand').click(function() {
        var menuBtn = $(this);
        var menuTree = $(this).next();
        $('.quickview-nav__expand').not(menuBtn).removeClass('is-active');
        menuBtn.toggleClass('is-active');
      });


      if ( $('.tabs').length ) {
        // Window listeners
        $(window).resize(function() {
          checkTabs();
        });

        checkTabs();
      }

      // scroll functions
      ////////////////////////////////////////////////////////////////////////////////
      var headerHeight = $('.header-main').outerHeight();

      var fixNav = new Waypoint({
        element: $('.nav-container'),
        handler: function(direction) {
          if (direction == 'down' && quickviewState == false){
            $('.header-main').addClass('is-fixed');
            $('.quickview-container + *').css({'padding-top':headerHeight});
          }
          else {
            $('.header-main').removeClass('is-fixed');
            $('.quickview-container + *').css({'padding-top':'0'});

          }
        },
        offset: '-220px'
      })

      var animateNav = new Waypoint({
        element: $('.nav-container'),
        handler: function(direction) {
          if (direction == 'up'){
            $('.header-main').removeClass('is-visible');
          }
          else {
            $('.header-main').addClass('is-visible');
          }
        },
        offset: '-250px'
      })
    },

    // get today's hours
    getHours: function() {
      var cmoa_data = store.namespace('cmoa_data');
      if(store.session.has('cmoa_data.hours')) {
        formatHours(store.session('cmoa_data.hours'))
      }
      else {
        $.get('/wp-json/data/v1/hours').then(data => {
          cmoa_data.session('hours', data);
          formatHours(data);
        });
      }
    }
  }

})(jQuery);

export default SiteNavigation;
