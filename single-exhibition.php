<?php use Roots\Sage\Assets; ?>

<section class="hero" role="banner">
  <?php the_post_thumbnail(); ?>
</section>

<div class="container-full hero-credit">
  <p><?= Assets\image_credits() ?></p>
</div>

<section class="section-primary">
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('templates/content', 'exhibition'); ?>
    <?php endwhile; ?>
  </div>
</section>
