<?php use Roots\Sage\Assets; ?>

<header class="header-main">
  <!-- home nav -->
  <nav class="nav-home" role='navigation' aria-label="Home">
    <span class="logo-l">
      <?= Assets\inline_svg('images/logo.svg'); ?>
      <a href="/" title="Carnegie Museum of Art: Home">
        Carnegie Museum of Art
      </a>
    </span>
    <a href="/" title="Carnegie Museum of Art: Home" class="logo-m">
      <?= Assets\inline_svg('images/wordmark2.svg'); ?>
    </a>
    <a href="/" title="Carnegie Museum of Art: Home" class="logo-s">
      <?= Assets\inline_svg('images/wordmark1.svg'); ?>
    </a>
  </nav>

    <!-- global nav primary-->
    <nav class="nav-container container-full" role='navigation' aria-label="Global navigation">

      <!-- persistant global nav -->
      <ul class="nav-global-persistant"  >
        <li>
          <button  class="nav-icon nav-icon-search quickview-btn" href="#search" aria-label="Search navigation trigger" title="Search">
            <i class="icon -search" aria-hidden="true"></i>
          </button>
        </li>
        <li>
          <button class="nav-icon nav-icon-hamburger quickview-btn" href="#quickview-nav" aria-label="Menu" Title="Menu" role="button" aria-label="Mobile navigation trigger">
            <i class="icon -hamburger" aria-hidden="true"></i>
          </button>
        </li>
      </ul>

      <!-- global nav -->
      <?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu(['theme_location' => 'primary_navigation', 'container' => '', 'menu_class' => 'nav-global']);
        endif;
      ?>

      <!-- visit callout -->
      <a href="#visit" class="quickview-btn nav-callout" title="Plan Your Visit" aria-label="Plan Your Visit navigation trigger">
        <div >
          <span>Plan Your Visit</span>
          <span itemprop="hoursAvailable" itemtype="http://schema.org/OpeningHoursSpecification" class="open-times"></span>
        </div>
        <span class="nav-icon nav-icon-visit">
          <i class="icon -visit" aria-hidden="true"></i>
        </span>
      </a>

    </nav>

  </nav>
</header>

<!-- quickview tray -->
<header class="quickview-container">
  <div class="quickview quickview-visit" id="visit">
    <figure itemprop="hoursAvailable" itemtype="http://schema.org/OpeningHoursSpecification" class="quickview-visit--details">
      <figcaption>Hours</figcaption>
      <?php
        $static = new CMOA_Static_Data();
        $museum_hours = $static->format_json($static->get_static_data('hours'));
      ?>
      <?php foreach ($museum_hours->days as $weekday): ?>
        <div class="quickview-visit--day <?= $weekday->day == date('l') ? "is-active" : "" ?>">
          <h3 itemprop="dayOfWeek" ><?= $weekday->day ?></h3>
          <span class="hours">
            <?php if (isset($weekday->opens)): ?>
              <time itemprop="opens" content="<?= $weekday->opens_ISO ?>"><?= $weekday->opens ?></time>&ndash;<time itemprop="closes" content="<?= $weekday->closes_ISO ?>" ><?= $weekday->closes ?></time>
            <?php else: ?>
              <?= $weekday->message ?>
            <?php endif; ?>
          </span>
        </div>
      <?php endforeach; ?>
    </figure>

    <figure class="quickview-visit--details">
      <figcaption>Admission</figcaption>
      <?php
        $static = new CMOA_Static_Data();
        $museum_admissions = $static->format_json($static->get_static_data('admissions'));
      ?>
      <?php foreach ($museum_admissions as $admission): ?>
        <div class="quickview-visit--admission">
          <h3><?= $admission->type ?>:</h3>
          <span class="price"><?= $admission->cost ?></span>
        </div>
      <?php endforeach; ?>
      <div class="quickview-visit--admission">
        <h3 class="featured-price">50% off regular admission
          weekdays after 3pm</h3>
      </div>
    </figure>

    <figure class="quickview-visit--callouts">
      <figcaption>What's going on?</figcaption>
      <?php $home = get_page_by_path('home', OBJECT); ?>
      <?php while (have_rows('plan_your_visit_items', $home)) : the_row(); ?>
        <aside>
          <?php if(get_row_layout() == 'existing_item'): ?>
            <?php
              $item = get_sub_field('featured_item');
              $feature = ['item' => $item];
              $feature_item = FeaturedItemFactory::build($feature);
            ?>
            <a href="<?= get_the_permalink($item) ?>"><?= get_the_post_thumbnail($item, 'large_thumbnail') ?></a>
            <h4><?= $feature_item->type ?></h4>
            <h5><a href="<?= get_the_permalink($item) ?>"><?= $feature_item->title ?></a></h5>
          <?php elseif(get_row_layout() == 'custom_item'): ?>
            <?php
              $link = get_sub_field('link_url');
              $image = get_sub_field('image');
            ?>
            <a href="<?= $link ?>"><img src="<?= $image['sizes']['large_thumbnail'] ?>" alt="<?= $image['alt'] ?>" /></a>
            <h4><?php the_sub_field('sub_title') ?></h4>
            <h5><a href="<?= $link ?>"><?php the_sub_field('title') ?></a></h5>
          <?php endif; ?>
        </aside>
      <?php endwhile; ?>
    </figure>
  </div>

  <div class="quickview quickview-nav" id="quickview-nav">
    <?php
      if (has_nav_menu('quickview_navigation')) :
        wp_nav_menu(['theme_location' => 'quickview_navigation', 'container' => '', 'menu_class' => '']);
      endif;
    ?>
  </div>

  <div class="quickview" id="search" >
    <form class="form-search" action="/" method="get" role="search">
      <legend class="screen-reader-text">Search form</legend>
      <fieldset>
        <input type="text" id="search-field" name="s" value="">
        <label for="search-field"> Search</label>
      </fieldset>
      <button type="submit" class="btn" title="Start search"><i class="icon -search"></i></button>
    </form>
  </div>
</header>

<div class="quickview-overlay"></div>
