<?php use Roots\Sage\Assets; ?>

<div class="upcoming--item">
  <div class="dates <?= $feature->chip->color ?>">
    <?= $feature->chip_text() ?>
  </div>
  <a href="<?= get_permalink($feature->ID) ?>" class="item--image">
    <?php
      $thumbnail_id = get_post_thumbnail_id($feature->ID);
      $img_src = wp_get_attachment_image_url($thumbnail_id);
      $img_srcset = wp_get_attachment_image_srcset($thumbnail_id);
    ?>
    <img src="<?= esc_url($img_src); ?>" srcset="<?= esc_attr($img_srcset); ?>" sizes="(max-width: 450px) 100vw, (max-width: 700px) 100vw, (max-width: 1000px) 100vw, (max-width: 1200px) 33vw, 1024px" alt="<?= Assets\image_alt_text($thumbnail_id) ?>" />
  </a>
  <h2><a href="<?= get_permalink($feature->ID) ?>"><?= $feature->title ?></a></h2>
</div>
