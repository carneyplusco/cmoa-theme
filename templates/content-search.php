<article <?php post_class(); ?>>
  <header>
    <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <h4 class="level-6"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h4>
  </header>
  <div class="entry__summary">
    <?php the_excerpt(); ?>
  </div>
</article>
