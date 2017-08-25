<?php
use Roots\Sage\Assets;
use Roots\Sage\Extras;

global $ai1ec_registry;
$term = get_queried_object();
$search = $ai1ec_registry->get('model.search');
$taxonomy = $ai1ec_registry->get('model.taxonomy');
$hero = $taxonomy->get_category_field($term->term_id, 'image');
$hero_id = Extras\get_image_from_url($hero);

$filters['cat_ids'][] = $term->term_id;

$cal = new CMOA_Calendar();
$upcoming_events = $cal->all_upcoming_events($filters);
?>

<section class="hero" role="banner">
  <img src="<?= $hero ?>" />
</section>

<div class="container-full hero-credit">
  <p><?= get_post($hero_id)->post_excerpt ?></p>
</div>

<section class="section-primary">
  <div class="container">
    <article class="events-list <?= count($upcoming_events) ? "l-long" : "l-middle" ?>">
      <h1><?= $term->name ?></h1>
      <?= category_description() ?>
      <?php if(have_rows('sponsor_logos', $term)): ?>
        <hr />
        <h4><?= $term->name ?> is sponsored by:</h4>
        <ul class="sponsor-list">
          <?php while (have_rows('sponsor_logos', $term)) : the_row(); ?>
            <li>
              <a href="<?php the_sub_field('sponsor_url'); ?>">
                <?php $logo_image = get_sub_field('logo_image'); ?>
                <img src="<?= $logo_image['url']; ?>" alt="<?= $logo_image['alt'] ?>" />
              </a>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php endif; ?>
    </article>

    <aside class="sidebar upcoming-list">
      <?php if (count($upcoming_events)) : ?>
        <h2>Upcoming events</h2>
        <?php foreach ($upcoming_events as $event): ?>
          <?php
            $event_feature = ['item' => $event->get('post')];
            $feature_item = FeaturedItemFactory::build($event_feature);
            Assets\get_template_part('templates/content', 'ai1ec_event', ['feature' => $feature_item]);
          ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </aside>
  </div>
</section>
