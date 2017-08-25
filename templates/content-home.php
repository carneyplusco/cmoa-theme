<?php
use Roots\Sage\Assets;

$featured_items = get_field('featured_items');
$home_features = array_slice($featured_items, 1, 3);
?>

<section class="hero" role="banner">
  <?php if($featured_items): ?>
    <?php $top_feature_item = \FeaturedItemFactory::build($featured_items[0]); ?>
    <a href="<?= get_the_permalink($top_feature_item->ID) ?>">
      <?= get_the_post_thumbnail($top_feature_item->ID) ?>
    </a>
  <?php else: ?>
    <a href="/"><?= get_the_post_thumbnail() ?></a>
  <?php endif ?>
</section>

<section class="section-primary">
  <div class="container">

    <div itemscope itemtype="http://schema.org/Event" class="chip-callout-featured chip-callout">
      <aside class="chip-callout--info <?= $top_feature_item->chip->color ?>">
        <?= $top_feature_item->chip_text() ?>
      </aside>
      <div class="content">
        <div class="intro">
          <h4><?= $top_feature_item->type ?><em itemprop="location" class="location"><?= $top_feature_item->locations ?></em></h4>
          <h3 itemprop="name" class="alt"><a href="<?= get_the_permalink($top_feature_item->ID) ?>"><?= $top_feature_item->title ?></a></h3>
        </div>
        <div itemprop="description" class="description">
          <p>
            <?= $top_feature_item->lead ?>
          </p>
        </div>
      </div>
    </div>

    <section class="l-full">
      <?php foreach($home_features as $feature): ?>
        <?php $feature_item = \FeaturedItemFactory::build($feature); ?>
        <div itemscope itemtype="http://schema.org/Event" class="chip-callout">
          <div class="chip-callout--link">
            <aside class="chip-callout--info <?= $feature_item->chip->color ?>">
              <?= $feature_item->chip_text() ?>
            </aside>
            <div class="chip-callout--image">
              <a href="<?= get_the_permalink($feature_item->ID) ?>">
                <?= get_the_post_thumbnail($feature_item->ID, 'large_thumbnail') ?>
              </a>
            </div>
          </div>
          <div class="content">
            <h4><?= $feature_item->type ?> <em itemprop="location" class="location"><?= $feature_item->locations ?></em></h4>
            <h3 itemprop="name" class="alt"><a href="<?= get_the_permalink($feature_item->ID) ?>"><?= $feature_item->title ?></a></h3>
            <span itemprop="description">
              <?= wpautop($feature_item->lead) ?>
            </span>
          </div>
        </div>
      <?php endforeach; ?>
    </section>
  </div>
</section>

<section class="section-primary">
  <div class="container-full-sm">

    <?php $horizontal_callout_count = 0; ?>
    <?php while (have_rows('horizontal_callouts')) : the_row(); ?>
      <?php if($horizontal_callout_count < 1): ?>
        <?php get_template_part('templates/horizontal', 'callout'); ?>
      <?php endif; $horizontal_callout_count++; ?>
    <?php endwhile; ?>

    <section class="vertical-callout-group">
      <div class="calendar-widget vertical-callout teal">
        <div class="content">
          <h4>Event Calendar</h4>
          <div id="react-calendar"></div>
        </div>
      </div>

      <?php while (have_rows('vertical_callouts')): the_row(); ?>
        <div class="vertical-callout <?php the_sub_field('background_color') ?>">
          <div class="content">
            <?php if(get_sub_field('heading')): ?>
              <h4><a href="<?php the_sub_field('link_url') ?>"><?php the_sub_field('heading'); ?></a></h4>
              <hr />
            <?php endif; ?>
            <?php the_sub_field('text'); ?>
          </div>
          <?php
            $bg_image = get_sub_field('image');
            $img_src = wp_get_attachment_image_url($bg_image['ID'], 'large');
            $img_srcset = wp_get_attachment_image_srcset($bg_image['ID'], 'large');
          ?>
          <a class="bg-image" href="<?php the_sub_field('link_url') ?>" aria-label="Visit <?php the_sub_field('heading') ?>">
            <img src="<?= esc_url($img_src); ?>" srcset="<?= esc_attr($img_srcset) ?>" sizes="(max-width: 450px) 100vw, (max-width: 700px) 50vw, (max-width: 1200px) 33vw, 1200px" class="" alt="<?= $bg_image['alt'] ?>" />
          </a>
        </div>
      <?php endwhile; ?>
    </section>

    <?php $horizontal_callout_count = 0; ?>
    <?php while (have_rows('horizontal_callouts')) : the_row(); ?>
      <?php if($horizontal_callout_count >= 1): ?>
        <?php get_template_part('templates/horizontal', 'callout'); ?>
      <?php endif; $horizontal_callout_count++; ?>
    <?php endwhile; ?>
  </div>
</section>
