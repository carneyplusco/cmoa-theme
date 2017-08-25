<?php
/**
 * Template Name: Publications
 */

  use Roots\Sage\Extras;
  use Roots\Sage\Assets;

  $publications_query = array(
    'post_type'      => 'publication',
    'post_status'    => 'publish',
    'meta_key'       => 'publication_date',
    'orderby'        => 'meta_value',
    'order'          => 'DESC'
  );

  $publications = new WP_Query($publications_query);
?>

<section class="hero" role="banner">
  <?php the_post_thumbnail(); ?>
</section>

<div class="container-full hero-credit">
  <p><?= Assets\image_credits() ?></p>
</div>

<section class="section-primary">
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <?php the_content(); ?>
    <?php endwhile; ?>
  </div>

  <div class="container">
    <article class="l-long page-content publication-list">
      <?php while ($publications->have_posts()) : $publications->the_post(); ?>
        <div class="publication--item">
          <h2><?php the_title() ?></h2>
          <?php the_content() ?>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </article>

    <aside class="sidebar">
      <?php Extras\sidebar_nav($post) ?>
      <?php get_template_part('templates/sidebar','links'); ?>
    </aside>
  </div>
</section>
