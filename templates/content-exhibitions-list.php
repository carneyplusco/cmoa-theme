<?php
$timezone = new DateTimeZone('America/New_York');
$locations = wp_get_post_terms($post->ID, 'locations', ['fields' => 'names']);
$departments = wp_get_post_terms($post->ID, 'departments');
$color = get_field('chip_color', $departments[0]) ?: 'teal';
?>

<div class="exhibition">
  <a href="<?php the_permalink() ?>">
    <?php the_post_thumbnail(); ?>
  </a>
  <div class="intro">
    <div class="chip <?= $color ?>">
      <?php if(!get_field('has_start_and_end_date') && get_field('lead_message')): ?>
        <p><?php the_field('lead_message') ?></p>
      <?php elseif(get_field('start_date') && get_field('end_date')): ?>
        <?php
          $start_date = DateTime::createFromFormat("Ymd", get_field('start_date'), $timezone);
          $end_date = DateTime::createFromFormat("Ymd", get_field('end_date'), $timezone);
        ?>
        <p>
          <strong><?= $start_date->format('M j') ?>&ndash; <?= $end_date->format('M j') ?></strong>
          <?= $end_date->format('Y') ?>
        </p>
      <?php endif; ?>
    </div>
    <div class="details">
      <?php $categories = wp_get_post_terms($post, 'events_categories'); ?>
      <ul class="categories">
        <li>Exhibition</li>
      </ul>
      <?php if(count($locations)): ?>
        <ul class="tags">
          <li><?= implode(', ', $locations) ?></li>
        </ul>
      <?php endif; ?>
      <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
      <?php the_excerpt(); ?>
    </div>
  </div>
</div>
