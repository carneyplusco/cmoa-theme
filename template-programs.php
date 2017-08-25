<?php
/**
 * Template Name: Program List
 */

  use Roots\Sage\Assets;
  use Roots\Sage\Extras;

  $features_query = array(
    'post_type'      => 'program_feature',
    'orderby'        => 'menu_order',
    'order'          => 'ASC'
  );

  $featured_items = get_field('featured_items');
?>

<section class="hero" role="banner">
  <?php the_post_thumbnail(); ?>
</section>

<div class="container-full hero-credit">
  <p><?= Assets\image_credits() ?></p>
</div>

<section class="section-primary">
  <div class="container">
    <article class="page-content l-long">

      <?php // Page content block
        while (have_posts()) : the_post(); ?>
        <h1><?php the_title() ?></h1>
        <?php the_content(); ?>
      <?php endwhile; ?>

      <div class="program-list">
        <?php while (have_rows('featured_programs')): the_row(); ?>
          <div class="program--item">
            <?php $program = get_sub_field('program'); ?>
            <div class="program--thumb">
              <a href="<?= get_the_permalink($program) ?>">
                <?= get_the_post_thumbnail($program, 'large_thumbnail') ?>
              </a>
            </div>
            <div class="program--content">
              <h3><a href="<?= get_the_permalink($program) ?>"><?= get_the_title($program) ?></a></h3>
              <?= get_the_excerpt($program) ?>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </article>

    <aside class="sidebar">
      <?php Extras\sidebar_nav($post) ?>
      <?php get_template_part('templates/sidebar','links'); ?>
    </aside>
    <aside class="l-short upcoming-list">
      <?php if($featured_items): ?>
        <h2>Featured Events</h2>
        <?php foreach($featured_items as $feature): ?>
          <?php
            $feature_item = FeaturedItemFactory::build($feature);
            Assets\get_template_part('templates/content', 'ai1ec_event', ['feature' => $feature_item]);
          ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </aside>
  </div>

  <!--
  <div class="container">
    <section class="horizontal-callout">
      <div class="content">
        <h4>Get In Touch With Us</h4>
        <blockquote>Questions about college, K-12 school, or family programs?</blockquote>
      </div>
      <div class="action">
        <a href="#" class="btn btn-block">Contact Us</a>
      </div>
    </section>
  </div>
  -->
</section>
