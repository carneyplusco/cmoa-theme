<?php use Roots\Sage\Assets; ?>

<div class="container-full">
  <footer class="main-footer" id="main-footer" role="contentinfo">
    <div class="container-full">
      <section class="logo red">
        <?= Assets\inline_svg('images/cmoa.svg'); ?>
      </section>

      <section class="location">
        <?= do_shortcode('[static_data global="location"]') ?>
        <p><small>One of the four <a href="http://www.carnegiemuseums.org/">Carnegie Museums of Pittsburgh</a></small></p>
      </section>

      <section class="navigation">
        <?php
          if (has_nav_menu('footer_navigation')) :
            wp_nav_menu(['theme_location' => 'footer_navigation', 'container' => '', 'menu_class' => 'nav-footer']);
          endif;
        ?>
      </section>

      <section class="social">
        <ul class="links">
          <li>
            <a href="http://www.facebook.com/CarnegieMuseumofArt" aria-label="facebook" title="Facebook"><i class="icon -facebook" aria-hidden="true"></i></a>
          </li>
          <li>
            <a href="http://twitter.com/cmoa" aria-label="twitter" title="Twitter"><i class="icon -twitter" aria-hidden="true"></i></a>
          </li>
          <li>
            <a href="http://instagram.com/thecmoa" aria-label="instagram" title="Instagram"><i class="icon -instagram" aria-hidden="true"></i></a>
          </li>
          <li>
            <a href="http://vimeo.com/cmoa" aria-label="vimeo" title="Vimeo"><i class="icon -vimeo" aria-hidden="true"></i></a>
          </li>
        </ul>
        <p>
          <?= do_shortcode('[app_store_badge]') ?>
        </p>
      </section>
    </div>
    <p class="legal">
      <small>&copy; <?= date('Y') ?> Carnegie Museum of Art, Carnegie Institute<a href="/about/terms-of-use/">Terms of Use</a><a href="http://www.carnegiemuseums.org/interior.php?pageID=100">Privacy Policy</a></small>
    </p>
  </footer>
</div>

<script type="text/javascript">
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-1213892-1', 'auto');
  ga('send', 'pageview');

  ga('create', 'UA-65401320-1' , 'auto' , {'name': 'unified'});
  ga('unified.send', 'pageview');
</script>

<script type="text/javascript">
  /* <![CDATA[ */
  var google_conversion_id = 975549556;
  var google_custom_params = window.google_tag_params;
  var google_remarketing_only = true;
  /* ]]> */
  </script>
  <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
  <div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/975549556/?value=0&amp;guid=ON&amp;script=0"/>
  </div>
</noscript>
