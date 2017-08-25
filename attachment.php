<section class="section-primary">
  <div class="container">
    <article class="page-content l-long">
      <h1><?= the_title() ?></h1>
      <?php echo wp_get_attachment_image( get_the_ID(), 'large' ); ?>
    </article>
  </div>
</section>
