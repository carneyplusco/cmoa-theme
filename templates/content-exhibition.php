<?php
use Roots\Sage\Extras;

global $ai1ec_registry;
$locations = wp_get_post_terms($post->ID, 'locations', ['fields' => 'names']);
$frequency_pattern = '/^FREQ=([^;]+);/';
$until_pattern = '/UNTIL=([^;]+);/';

$cal = new CMOA_Calendar();
$more_events = array_map(function($event) {
  return $event->get('post_id');
}, $cal->all_upcoming_events());

$more_event_ids = array_unique($more_events);

// Filter all events by returning events that have an associated event that matches this exhibition's id
$related_events = array_filter($more_event_ids, function($event_id) {
  $event_ids = get_post_meta($event_id, 'exhibition', true) ?: [];
  return count($event_ids) && in_array(get_the_ID(), $event_ids);
});

$unique_events = [];
foreach($related_events as $event_id) {
  if(!isset($unique_events[$event_id])) {
    $event = $ai1ec_registry->get('model.event', $event_id);
    $unique_events[$event_id] = $event;
    preg_match($frequency_pattern, $event->get('recurrence_rules'), $matches);
    if(isset($matches[1])) {
      $unique_events[$event_id]->set('recurrence', $matches[1]);
    }

    preg_match($until_pattern, $event->get('recurrence_rules'), $until_matches);
    $unique_events[$event_id]->set('has_end_date', isset($until_matches[1]));
  }
}
?>

<article class="page-content l-long">
  <h1><?php the_title() ?></h1>
  <?php if(!get_field('has_start_and_end_date') && get_field('lead_message')): ?>
    <time><?php the_field('lead_message') ?></time>
  <?php elseif(get_field('start_date') && get_field('end_date')): ?>
    <?php
      $exhibition_start_date = new DateTime(get_field('start_date'));
      $exhibition_end_date = new DateTime(get_field('end_date'));
    ?>
    <time>
      <strong><?= $exhibition_start_date->format('M j') ?></strong><?= ($exhibition_start_date->format('Y') != $exhibition_end_date->format('Y')) ? ", ".$exhibition_start_date->format('Y') : '' ?>&ndash;<strong><?= $exhibition_end_date->format('M j') ?></strong>,
      <?= $exhibition_end_date->format('Y') ?>
    </time>
  <?php endif; ?>

  <?php if(count($locations)): ?>
    <address>
      <strong><?= implode(', ', $locations) ?></strong>
    </address>
  <?php endif; ?>
  <hr />
  <?php the_content() ?>

  <?php if(have_rows('exhibition_images')): ?>
    <hr class="short" />
    <h3>Exhibition Images</h3>
    <div class="exhibition-images">
      <?php while (have_rows('exhibition_images')): the_row(); ?>
        <?php $image = get_sub_field('image'); ?>
        <div class="item">
          <a href="<?= $image['sizes']['medium'] ?>" class="image">
            <img src="<?= $image['sizes']['medium'] ?>" alt="<?= $image['alt'] ?>" />
          </a>
          <a class="credit-link" tabindex="0">
            <i class="icon -info"></i>
          </a>
          <div class="credits"><?php the_sub_field('artwork_credit') ?></div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>

  <?php if(get_field('exhibition_credits')): ?>
    <hr class="short" />
    <h3>Exhibition Credits</h3>
    <?php the_field('exhibition_credits') ?>
  <?php endif; ?>
</article>

<aside class="sidebar">
  <?php
    $art = get_page_by_path('art'); // load the Art page sidebar nav on each exhibition page
    Extras\sidebar_nav($art);
  ?>
  <?php if($unique_events): ?>
    <div class="related">
      <h2>Related Events</h2>
      <ul class="items">
        <?php foreach ($unique_events as $related_event): ?>
          <?php
            $start_date = DateTime::createFromFormat('Y-m-d\TH:i:sO', $related_event->get('start'));
          ?>
          <li class="items__related -event" itemscope itemtype="http://schema.org/Event">
            <time itemprop="startDate" content="<?= $start_date->format('c') ?>">
              <?php if($related_event->get('recurrence') == "DAILY" && !$related_event->get('has_end_date')): ?>
                <strong>Daily at <?= $start_date->format('g:i a') ?></strong>
              <?php else: ?>
                <strong><?= $related_event->get('recurrence') ? "Starts " : "" ?><?= $start_date->format('M j') ?></strong>, <?= $start_date->format('Y') ?>
              <?php endif; ?>
            </time>
            <a itemprop="url" href="<?= get_permalink($related_event->get('post_id')) ?>"><span itemprop="name"><?= $related_event->get('post')->post_title ?></span></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <?php get_template_part('templates/sidebar','links'); ?>
</aside>
