<?php
  $year = get_query_var('past') ? get_query_var('past') : '2016';

  $start = new DateTime($year."-01-01");
  $end = new DateTime($year."-12-31");

  $past_query = array(
    'post_type'      => 'exhibition',
    'post_status'    => 'publish',
    'meta_key'       => 'end_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => array(
      'relation'     => 'AND',
      array(
        'key'          => 'start_date',
        'value'        => $start->format('Ymd'),
        'compare'      => '>='
      ),
      array(
        'key'          => 'end_date',
        'value'        => $end->format('Ymd'),
        'compare'      => '<'
      )
    )
  );

  global $wpdb;
  $past_exhibitions = new WP_Query($past_query);
  $years_query = "SELECT ID, post_date, post_name, meta_value, DATE_FORMAT(STR_TO_DATE(meta_value, '%Y%m%d'), '%Y') formatted_date FROM wp_posts JOIN wp_postmeta ON(wp_postmeta.post_id = ID) WHERE post_type = 'exhibition' AND post_status = 'publish' AND meta_key = 'start_date' GROUP BY formatted_date ORDER BY formatted_date DESC";
  $exhibition_years = $wpdb->get_results($years_query, OBJECT);
?>

<section class="section-primary">
  <div class="container">
    <h1>Past Exhibitions</h1>
    <hr />
  </div>
  <div class="container">
    <article class="l-long exhibitions-list">
      <h2><?= $year ?>  Exhibitions</h2>
      <?php if ($past_exhibitions->have_posts()) : ?>
        <?php while ($past_exhibitions->have_posts()) : $past_exhibitions->the_post(); ?>
          <?php get_template_part('templates/content','exhibitions-list'); ?>
        <?php endwhile; ?>
      <?php else: ?>
        <p>Sorry, no exhibitions matched your criteria.</p>
      <?php endif; ?>
    </article>

    <aside class="sidebar upcoming-list">
      <h2>Previous Years</h2>
      <div class="quickview quickview-nav">
        <ul>
          <li>
            <a href="#">Select a Year</a>
            <div class="quickview-nav__expand"></div>
            <ul class="sub-menu">
              <?php foreach($exhibition_years as $ex_year): ?>
                <li><a href="/exhibitions/past/<?= $ex_year->formatted_date ?>"><?= $ex_year->formatted_date ?></a></li>
              <?php endforeach; ?>
            </ul>
          </li>
        </ul>
      </div>
    </aside>
  </div>
</section>
