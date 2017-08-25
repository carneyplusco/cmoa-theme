<?php
use Roots\Sage\Assets;
use Roots\Sage\Extras;
?>

<section class="hero" role="banner">
  <?= Assets\hero_image($post); ?>
</section>

<div class="container-full hero-credit">
  <p><?= Assets\image_credits() ?></p>
</div>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'page'); ?>
<?php endwhile; ?>
