<?php
/**
 * Template Name: Art
 */

  use Roots\Sage\Extras;

  $now = new DateTime();

  $current_query = array(
    'post_type'      => 'exhibition',
    'post_status'    => 'publish',
    'orderby'        => array(
      'end_date'     => 'ASC'
    ),
    'meta_query'     => array(
      'relation' => 'OR',
      array(
        'relation' => 'AND',
        'has_lead_message' => array(
          'key'        => 'lead_message',
          'compare'    => 'EXISTS'
        ),
        array(
          'key'        => 'has_start_and_end_date',
          'value'      => '1',
          'compare'    => '!='
        )
      ),
      array(
        'relation' => 'AND',
        array(
          'key'          => 'start_date',
          'value'        => $now->format('Ymd'),
          'compare'      => '<='
        ),
        array(
          'key'          => 'end_date',
          'value'        => $now->format('Ymd'),
          'compare'      => '>='
        )
      )
    )
  );
  $current_exhibitions = new WP_Query($current_query);
?>

<section class="hero">
</section>

<section class="section-primary">
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <?php the_content(); ?>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>

  <div class="container">
    <article class="l-long exhibitions-list">
      <?php while ($current_exhibitions->have_posts()) : $current_exhibitions->the_post(); ?>
        <?php get_template_part('templates/content','exhibitions-list'); ?>
      <?php endwhile; wp_reset_postdata(); ?>
    </article>

    <aside class="sidebar">
      <?php get_template_part('templates/sidebar','links'); ?>
    </aside>
  </div>
</section>
