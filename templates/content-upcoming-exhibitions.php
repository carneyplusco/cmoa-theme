<?php
use Roots\Sage\Assets;

$timezone = new DateTimeZone('America/New_York');
$department = wp_get_post_terms($post->ID, 'departments');
$color = get_field('chip_color', $department[0]) ?: 'teal';
?>

<div class="upcoming--item" itemscope itemtype="http://schema.org/ExhibitionEvent">
  <?php if(!get_field('has_start_and_end_date') && get_field('lead_message')): ?>
    <time class="dates <?= $color ?>"><?php the_field('lead_message') ?></time>
  <?php elseif(get_field('start_date') && get_field('end_date')): ?>
    <?php
      $start_date = DateTime::createFromFormat("Ymd", get_field('start_date'), $timezone);
      $end_date = DateTime::createFromFormat("Ymd", get_field('end_date'), $timezone);
    ?>
    <time class="dates <?= $color ?>">
      <strong itemprop="startDate" content="<?= $start_date->format('c') ?>"><?= $start_date->format('M j') ?></strong><?= ($start_date->format('Y') != $end_date->format('Y')) ? ", ".$start_date->format('Y') : '' ?>&ndash;<strong itemprop="endDate" content="<?= $end_date->format('c') ?>"><?= $end_date->format('M j') ?></strong>, <?= $end_date->format('Y') ?>
    </time>
  <?php endif; ?>
  <a itemprop="url" href="<?php the_permalink() ?>" class="item--image">
    <?php
      $thumbnail_id = get_post_thumbnail_id();
      $img_src = wp_get_attachment_image_url($thumbnail_id);
      $img_srcset = wp_get_attachment_image_srcset($thumbnail_id);
    ?>
    <img src="<?= esc_url($img_src); ?>" srcset="<?= esc_attr($img_srcset); ?>" sizes="(max-width: 450px) 100vw, (max-width: 700px) 100vw, (max-width: 1000px) 100vw, (max-width: 1200px) 33vw, 1024px" alt="<?= Assets\image_alt_text($thumbnail_id) ?>" />
  </a>
  <h2 itemprop="name"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
</div>
