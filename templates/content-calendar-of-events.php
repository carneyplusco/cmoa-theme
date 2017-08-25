<?php
global $ai1ec_registry;
$taxonomy = $ai1ec_registry->get('model.taxonomy');
$timezone = new DateTimeZone('America/New_York');
?>

<!-- Events -->
<article class="l-long events-list">
  <h2>Events</h2>
  <?php if(count($events) > 0): ?>
    <?php foreach($events as $event): ?>
      <?php
        $cmoa_event = new CMOA_Event($event->get('post'));
        $categories = $taxonomy->get_post_categories($event->get('post_id'));
        $tags = $taxonomy->get_post_tags($event->get('post_id'));
        $color = get_field('chip_color', $categories[0]) ?: 'cadet';
      ?>
      <div class="item" itemscope itemtype="http://schema.org/Event">
        <!-- Event left side -->
        <div class="chip <?= $color ?>">
          <time itemprop="startDate" content="<?= $cmoa_event->start_date_iso() ?>">
            <?php if($cmoa_event->recurrence() == "DAILY" && !$cmoa_event->has_no_end_time()): ?>
              <strong>Daily at <?= $cmoa_event->start_time() ?></strong>
            <?php else: ?>
              <strong><?= $cmoa_event->recurrence() ? "Starts " : "" ?><?= $cmoa_event->start_date() ?></strong> <span class="item__year"><?= $cmoa_event->start_year() ?></span>
            <?php endif; ?>
          </time>
          <?= get_the_post_thumbnail($event->get('post_id'), 'thumbnail') ?>
        </div>
        <!-- Event right side -->
        <div class="details">
          <ul class="categories">
            <?php foreach ($categories as $category): ?>
              <li><a href="/calendar/category/<?= $category->slug ?>"><?= $category->name ?></a></li>
            <?php endforeach; ?>
          </ul>
          <ul class="tags">
            <?php foreach ($tags as $tag): ?>
              <li><a href="/calendar/tag/<?= $tag->slug ?>"><?= $tag->name ?></a></li>
            <?php endforeach; ?>
          </ul>
          <h2 itemprop="name"><a itemprop="url" href="<?= get_permalink($event->get('post_id')) ?>"><?= $event->get('post')->post_title ?></a></h2>
          <div class="description" itemprop="description">
            <?= wpautop(get_field('excerpt', $event->get('post_id'))) ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <hr>
    <?php if(get_query_var('event_category')): ?>
      <?php
        $categories = get_terms('events_categories');
        $selected_category = array_filter($categories, function($category) {
          return $category->term_id == get_query_var('event_category');
        });
        $selected_category = array_values($selected_category);
      ?>
      <p>Sorry, no events are scheduled for that category during that time. <a href="/calendar/?event_category=<?= $selected_category[0]->term_id ?>">See what's scheduled for <?= $selected_category[0]->name ?> in the next thirty days</a>.</p>
    <?php else: ?>
      <p>Sorry, no events are scheduled for that time. <a href="/calendar">See what's coming up in the next thirty days</a>.</p>
    <?php endif; ?>
  <?php endif; ?>
</article>

<!-- Exhibitions -->
<aside class="l-short upcoming-list">
  <?php if ($exhibitions->have_posts()) : ?>
    <h2>Exhibitions</h2>
    <?php while ($exhibitions->have_posts()) : $exhibitions->the_post(); ?>
      <?php get_template_part('templates/content','upcoming-exhibitions'); ?>
    <?php endwhile; wp_reset_postdata(); ?>
  <?php endif; ?>
  <?php get_template_part('templates/sidebar','links'); ?>
</aside>
