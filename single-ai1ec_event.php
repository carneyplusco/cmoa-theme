<?php
use Roots\Sage\Assets;

global $ai1ec_registry;
$event = $ai1ec_registry->get('model.event', $post->ID);
$cmoa_event = new CMOA_Event($post);
$taxonomy = $ai1ec_registry->get('model.taxonomy');
$timezone = new DateTimeZone('America/New_York');
$categories = $taxonomy->get_post_categories($event->get('post_id'));
$tags = $taxonomy->get_post_tags($event->get('post_id'));
$locations = wp_get_post_terms($event->get('post_id'), 'locations', ['fields' => 'names']);

$cal = new CMOA_Calendar();

$related_events = $cal->related_events($cmoa_event);
$related_exhibitions = $cal->related_exhibitions($cmoa_event);
?>

<section class="hero" role="banner">
  <?php the_post_thumbnail(); ?>
</section>

<div class="container-full hero-credit">
  <p><?= Assets\image_credits() ?></p>
</div>

<section class="section-primary">
  <div class="container events-list">
    <article itemscope itemtype="http://schema.org/Event" class="page-content l-long">
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

      <h1 itemprop="name"><?php the_title(); ?></h1>

      <div class="time-location">
        <?= $cmoa_event->display_dates() ?>

        <?php if(count($locations) > 2): ?>
          <address itemscope itemtype="http://schema.org/Place">
            <strong itemprop="name">Various Locations</strong>
          </address>
        <?php elseif(count($locations)): ?>
          <address itemscope itemtype="http://schema.org/Place">
            <strong itemprop="name"><?= implode(', ', $locations) ?></strong>
          </address>
        <?php elseif($event->get('venue')): ?>
          <address itemscope itemtype="http://schema.org/Place">
            <strong itemprop="name"><?= $event->get('venue') ?></strong>
            <br />
            <strong itemprop="address"><?= $event->get('address') ?></strong>
          </address>
        <?php endif; ?>
      </div>

      <p class="ticket-information" itemscope itemtype="http://schema.org/Offer">
        <span itemprop="price"><?= $event->get('is_free') ? "Free Event" : $event->get('cost') ?></span>
      </p>

      <hr />

      <div class="description" itemprop="description">
        <?= wpautop(do_shortcode($post->post_content)) ?>
      </div>

      <?php if($event->get('ticket_url')): ?>
        <p itemscope itemtype="http://schema.org/Offer">
          <a itemprop="url" href="<?= $event->get('ticket_url') ?>" class="btn">Get Tickets / Register</a>
        </p>
      <?php endif; ?>

      <?php if(get_field('registration_information')): ?>
        <h3>Registration Information</h3>
        <?php the_field('registration_information') ?>
      <?php endif; ?>

      <?php if(get_field('sponsorship_details')): ?>
        <h3>Event Sponsors</h3>
        <?php the_field('sponsorship_details') ?>
      <?php endif; ?>
    </article>


    <aside class="sidebar">
      <div class="related">
        <h2>Programs</h2>
        <?php
          $programs = array_map(function($tag) {
            return get_field('related_programs', $tag);
          }, $tags);
          // Sometimes ACF returns NULL, so filter those out
          $programs = array_filter($programs, function($program) { return $program; });
        ?>
        <ul class="items">
          <?php if(count($programs)): ?>
            <?php foreach ($programs as $program): ?>
              <li>Learn more about <a href="<?= get_permalink($program) ?>">programs for <?= strtolower($program->post_title) ?></a></li>
            <?php endforeach; ?>
          <?php else: ?>
            <li>Learn more about <a href="/programs">all of our programs</a></li>
          <?php endif; ?>
        </ul>

        <?php if ($related_exhibitions || $related_events): ?>
          <hr />
          <h2>Related Events &amp; Exhibitions</h2>
          <ul class="items">
            <?php foreach ($related_exhibitions as $related_exhibition): ?>
              <li class="items__related -exhibition" itemscope itemtype="http://schema.org/ExhibitionEvent">
                <?php if(!get_field('has_start_and_end_date', $related_exhibition->ID) && get_field('lead_message', $related_exhibition->ID)): ?>
                  <time><?php the_field('lead_message', $related_exhibition->ID) ?></time>
                <?php elseif(get_field('start_date', $related_exhibition->ID) && get_field('end_date', $related_exhibition->ID)): ?>
                  <?php
                    $start_date = DateTime::createFromFormat('Ymd', get_field('start_date', $related_exhibition->ID), $timezone);
                    $end_date = DateTime::createFromFormat('Ymd', get_field('end_date', $related_exhibition->ID), $timezone);
                  ?>
                  <time>
                    <strong itemprop="startDate" content="<?= $start_date->format('c') ?>"><?= $start_date->format('M j') ?></strong><?= ($start_date->format('Y') != $end_date->format('Y')) ? ", ".$start_date->format('Y') : '' ?>&ndash;<strong itemprop="endDate" content="<?= $end_date->format('c') ?>"><?= $end_date->format('M j') ?></strong>, <?= $end_date->format('Y') ?>
                  </time>
                <?php endif; ?>
                <a itemprop="url" href="<?= get_the_permalink($related_exhibition->ID) ?>"><span itemprop="name"><?= $related_exhibition->post_title ?></span></a>
              </li>
            <?php endforeach; ?>

            <?php foreach ($related_events as $related_event): ?>
              <li class="items__related -event" itemscope itemtype="http://schema.org/Event">
                <?= $related_event->display_dates() ?>
                <a itemprop="url" href="<?= get_permalink($related_event->details->get('post_id')) ?>"><span itemprop="name"><?= $related_event->details->get('post')->post_title ?></span></a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
      <?php get_template_part('templates/sidebar','links'); ?>
    </aside>
  </div>
</section>
