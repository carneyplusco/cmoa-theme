<?php if(have_rows('sidebar_links')): ?>
  <?php while (have_rows('sidebar_links')): the_row(); ?>
    <div class="sidebar__links">
      <?php if(get_sub_field('heading')): ?>
        <h2><?php the_sub_field('heading'); ?></h2>
      <?php endif; ?>
      <?php the_sub_field('text'); ?>
    </div>
  <?php endwhile; ?>
<?php endif; ?>
