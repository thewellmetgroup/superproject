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

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
        // JavaScript to be fired on all pages
      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
        if($('.news-listing').length) {

          var $grid = $('.news-listing').masonry({
            itemSelector: '.news-item',
            columnWidth: '.news-item',
            percentPosition: true,
            originLeft: true
          });
          // layout Masonry after each image loads
          $grid.imagesLoaded().progress( function() {
            $grid.masonry('layout');
          });
        }



       

        
        var old = 0;
        var numItems = $('#quotes blockquote').length;
        function getRan() {
          var news = old + 1;
          if (news <= numItems) {
              return news;
          }

          news = 1;
          old = 0;
          return news;
        }

        function show() {
          var news = getRan();
          $("#quotes blockquote").hide();
          $("#quotes blockquote:nth-child(" + news + ")").fadeIn("slow");
          old++;
        }

         if($('#quotes').length) {
          
          show();
          setInterval(show, 7000);
        }
        



      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
        //make the larger callouts change color on rollovers
        $('.callout').mouseenter(function() {
    		$(this).parent().css('color','#79b118');
    	}).mouseleave(function() {
    		$(this).parent().css('color','#31333d');
    	});
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // Grantees page
    'grantees': {
      init: function() {
        var $grid = $('.grantee-row').masonry({
      			itemSelector: '.grantee-item',
      			columnWidth: '.grantee-item',
      			percentPosition: true,
      			originLeft: true
		    });
    		// layout Masonry after each image loads
    		$grid.imagesLoaded().progress( function() {
    			$grid.masonry('layout');
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
