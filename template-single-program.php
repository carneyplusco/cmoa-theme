<?php
/**
 * Template Name: Program
 */

use Roots\Sage\Extras;
use Roots\Sage\Assets;

global $ai1ec_registry;
$cal = new CMOA_Calendar();
$categories = get_field('event_categories') ?: [];
$tags = get_field('event_tags');
$filters = [];
foreach($categories as $category) {
  $filters['cat_ids'][] = $category->term_id;
}
foreach($tags as $tag) {
  $filters['tag_ids'][] = $tag->term_id;
}

if(!empty($filters)) {
  $program_events = $cal->all_upcoming_events($filters);
}

$unique_events = [];
foreach($program_events as $event) {
  if(!isset($unique_events[$event->get('post_id')])) {
    $unique_events[$event->get('post_id')] = $ai1ec_registry->get('model.event', $event->get('post_id'));
  }
}
?>

<section class="hero container-full">
  <?php the_post_thumbnail(); ?>
</section>

<div class="container-full hero-credit">
  <p><?= Assets\image_credits() ?></p>
</div>

<section class="section-primary">
  <div class="container">
    <article class="page-content l-long">
      <?php while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; ?>
    </article>

    <aside class="sidebar">
      <?php Extras\sidebar_nav($post) ?>
    </aside>

    <aside class="sidebar upcoming-list">
      <?php if(count($unique_events)): ?>
        <div class="sidebar--events">
          <h2>Upcoming Events</h2>
          <?php foreach ($unique_events as $event): ?>
            <?php
              $event_feature = ['item' => $event->get('post')];
              $feature_item = FeaturedItemFactory::build($event_feature);
              Assets\get_template_part('templates/content', 'ai1ec_event', ['feature' => $feature_item]);
            ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php get_template_part('templates/sidebar','links'); ?>
    </aside>
  </div>
</section>
