<?php
/**
 * Template Name: Style Guide
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'style-guide'); ?>

<?php endwhile; ?>
