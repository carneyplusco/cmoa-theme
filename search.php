<section class="section-primary">
  <div class="container">
    <h2>Results for <?= filter_var(get_query_var('s'), FILTER_SANITIZE_STRING) ?></h2>
    <hr />
    <?php if (!have_posts()) : ?>
      <p class="alert alert-warning">
        <?php _e('Sorry, no results were found.', 'sage'); ?>
      </p>
      <?php get_search_form(); ?>
    <?php endif; ?>

    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('templates/content', 'search'); ?>
    <?php endwhile; ?>

    <?php the_posts_navigation(); ?>
  </div>
</section>
