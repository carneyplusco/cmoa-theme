<?php if(get_row_layout() == 'custom_callout'): ?>
  <a href="<?php the_sub_field('link_url') ?>" class="horizontal-callout photo-overlay charcoal">
    <?php $bg = get_sub_field('background_image'); ?>
    <img src="<?= $bg['sizes']['large'] ?>" alt="<?= $bg['alt'] ?>" class="bg-image" />
    <div class="action">
      <h4><?php the_sub_field('title') ?></h4>
      <?php the_sub_field('lead_text') ?>
    </div>
  </a>

<?php elseif(get_row_layout() == 'sponsorship_callout'): ?>
  <div class="horizontal-callout sponsorship">
    <h4><?php the_sub_field( 'title' ) ?></h4>
    <?php the_sub_field( 'lead_text' ) ?>
    <div class="sponsorship-images">
      <?php while( has_sub_field( 'logos' )): $image = get_sub_field( 'logo' ); ?>
        <img src="<?= $image['sizes']['large_thumbnail'] ?>" alt="<?= $image['alt'] ?>" />
      <?php endwhile; ?>
    </div>
    <?php the_sub_field('text') ?>
  </div>

<?php elseif(get_row_layout() == 'storyboard_callout'): ?>
  <div class="horizontal-callout storyboard">
    <?php $bg = get_sub_field('thumbnail_image'); ?>
    <div class="action">
      <img src="<?= $bg['sizes']['large_square'] ?>" alt="<?= $bg['alt'] ?>" class="bg-image"/>

      <div class="callout">
        <h2 class="level-4">Featured Post from Storyboard</h2>
        <a href="http://blog.cmoa.org" class="btn">Visit the blog</a>
      </div>
    </div>
    <div class="post_lead">
      <h2 class="level-4">Storyboard</h2>
      <h3 class="level-2"><a href="<?php the_sub_field('link_url') ?>"><?php the_sub_field('title') ?></a></h3>
      <p>
        <?php the_sub_field('lead_text') ?>
        <a href="<?php the_sub_field('link_url') ?>" class="storyboard__more-link"><?php the_sub_field('link_text') ?> <span class="screen-reader-text">"<?php the_sub_field('title') ?>" on Storyboard</span></a>
      </p>
    </div>
  </div>

<?php elseif(get_row_layout() == 'text_callout'): ?>
  <div class="horizontal-callout text">
    <div class="content">
      <h4><?php the_sub_field('title') ?></h4>
      <blockquote><?php the_sub_field('lead_text') ?></blockquote>
    </div>
    <?php if(get_sub_field('button_text') && get_sub_field('link_url')): ?>
      <div class="action">
        <a href="<?php the_sub_field('link_url') ?>" class="btn btn-block"><?php the_sub_field('button_text') ?></a>
      </div>
    <?php endif; ?>
  </div>

<?php endif; ?>
