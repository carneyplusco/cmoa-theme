<?php use Roots\Sage\Extras; ?>

<section class="section-primary">
  <div class="container">
    <article class="page-content l-long">
      <?php the_content(); ?>
    </article>

    <aside class="sidebar">
      <?php Extras\sidebar_nav($post) ?>
      <?php get_template_part('templates/sidebar','links'); ?>
    </aside>
  </div>
</section>
