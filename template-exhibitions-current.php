<?php
  use Roots\Sage\Extras;

  $now = new DateTime();

  $current_query = array(
    'post_type'      => 'exhibition',
    'post_status'    => 'publish',
    'orderby'        => array(
  		'end_date'     => 'ASC'
  	),
    'meta_query'     => array(
      'relation'   => 'AND',
      'start_date' => array(
        'key'      => 'start_date',
        'value'    => $now->format('Ymd'),
        'compare'  => '<='
      ),
      'end_date'   => array(
        'key'      => 'end_date',
        'value'    => $now->format('Ymd'),
        'compare'  => '>='
      ),
      array(
        'relation' => 'OR',
        'is_not_sticky'    => array(
          'key'        => 'is_sticky',
          'type'       => 'NUMERIC',
          'value'      => '0',
          'compare'    => '='
        ),
        'no_sticky'    => array(
          'key'        => 'is_sticky',
          'compare'    => 'NOT EXISTS'
        )
      )
    )
  );

  $sticky_query = array(
    'post_type'      => 'exhibition',
    'post_status'    => 'publish',
    'orderby'        => array(
  		'is_sticky'    => 'ASC',
  	),
    'meta_query'     => array(
      'is_sticky'    => array(
        'key'        => 'is_sticky',
        'type'       => 'NUMERIC',
        'value'      => '1',
        'compare'    => '='
      )
    )
  );

  $upcoming_query = array(
    'post_type'      => 'exhibition',
    'post_status'    => 'publish',
    'orderby'        => array(
      'end_date'     => 'ASC'
    ),
    'meta_query'     => array(
      'relation'     => 'OR',
      'has_start_date' => array(
        'key'        => 'start_date',
        'value'      => $now->format('Ymd'),
        'compare'    => '>'
      ),
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
      )
    )
  );

  $sticky_posts = new WP_Query($sticky_query);
  $current_exhibitions = new WP_Query($current_query);
  $upcoming_exhibitions = new WP_Query($upcoming_query);
?>

<section class="section-primary">
  <div class="container">
    <h1>Exhibitions</h1>
    <hr />
  </div>

  <div class="container">
    <article class="l-long exhibitions-list">
      <h2>Current</h2>
      <?php while ($sticky_posts->have_posts()) : $sticky_posts->the_post(); ?>
        <?php get_template_part('templates/content','exhibitions-list'); ?>
      <?php endwhile; wp_reset_postdata(); ?>

      <?php if ($current_exhibitions->have_posts()) : ?>
        <?php while ($current_exhibitions->have_posts()) : $current_exhibitions->the_post(); ?>
          <?php get_template_part('templates/content','exhibitions-list'); ?>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else: ?>
        <p>Sorry, no exhibitions matched your criteria.</p>
      <?php endif; ?>
    </article>

    <aside class="sidebar">
      <?php Extras\sidebar_nav($post) ?>
      <?php if($upcoming_exhibitions->have_posts()): ?>
        <div class="upcoming-list">
          <h2>Upcoming</h2>
          <?php while ($upcoming_exhibitions->have_posts()) : $upcoming_exhibitions->the_post(); ?>
            <?php get_template_part('templates/content','upcoming-exhibitions'); ?>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      <?php endif; ?>
    </aside>
  </div>

  <?php if(have_rows('bottom_callouts')): ?>
    <div class="container">
      <hr class="thick" />
      <div class="bottom-callouts">
        <?php while (have_rows('bottom_callouts')): the_row(); ?>
          <div class="bottom-callouts--block">
            <?php the_sub_field('content') ?>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  <?php endif; ?>
</section>
