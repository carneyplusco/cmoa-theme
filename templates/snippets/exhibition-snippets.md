The following snippets should help in unwrapping exhibition post properties:

<!-- Start date -->
<?php $start = get_field('start_date'); ?>
<?php $start = ($start == NULL) ? "Unknown" : DateTime::createFromFormat('Ymd', $start)->format('m-d-Y'); ?>
<p>Start date: <?= $start ?></p>

<!-- End date -->
<?php $end = get_field('end_date'); ?>
<?php $end = ($end == NULL) ? "Unknown" : DateTime::createFromFormat('Ymd', $end)->format('m-d-Y'); ?>
<p>End date: <?= $end ?></p>

<!-- Location -->
<?php $locations = wp_get_post_terms(get_the_ID(), 'locations', array('fields' => 'names')); ?>
<p>Location:</p>
<ul>
  <?php foreach ($locations as $l) : ?>
    <li><?= $l ?></li>
  <?php endforeach; ?>
</ul>

<!-- Curator  -->
<?php $curators = wp_get_post_terms(get_the_ID(), 'curators', array('fields' => 'names')); ?>
<p>Curator:</p>
<ul>
  <?php foreach ($curators as $c) : ?>
    <li><?= $c ?></li>
  <?php endforeach; ?>
</ul>

<!-- Departments -->
<?php $departments = wp_get_post_terms(get_the_ID(), 'departments', array('fields' => 'names')); ?>
<p>Departments:</p>
<ul>
  <?php foreach ($departments as $d) : ?>
    <li><?= $d ?></li>
  <?php endforeach; ?>
</ul>

<!-- Series -->
<?php $series = wp_get_post_terms(get_the_ID(), 'series', array('fields' => 'names')); ?>
<p>Series:</p>
<ul>
  <?php foreach ($series as $s) : ?>
    <li><?= $s ?></li>
  <?php endforeach; ?>
</ul>

<!-- Associated Artist(s) -->
<?php $artists = wp_get_post_terms(get_the_ID(), 'associated_artists', array('fields' => 'names')); ?>
<p>Associated artists:</p>
<ul>
  <?php foreach ($artists as $a) : ?>
    <li><?= $a ?></li>
  <?php endforeach; ?>
</ul>

<!-- Descriptive Text  -->
<p>Descriptive text: <?php the_content(); ?></p>

<!-- Related Programs -->
<!-- WIP -->
<p>Related programs: <?php the_field(''); ?></p>

<!-- Checklist -->
<?php $checklists = get_field('checklists'); ?>
<p>Checklist:</p>
<?php foreach ($checklists as $c) : ?>
  <p>File: <a href='<?= $c['file']['url']?>'><?= $c['file']['filename'] ?></a></p>
  <p>Notes: <?= $c['notes'] ?></p>
<?php endforeach; ?>

<!-- Gallery texts/brochures -->
<?php $gallery_texts = get_field('gallery_texts'); ?>
<p>Gallery texts:</p>
<ul>
  <?php foreach ($gallery_texts as $g) : ?>
    <li>
      <ul>
        <li>Link: <a href='<?= $g['file']['url'] ?>'><?= $g['title'] ?></a></li>
        <li>Description: <?= $g['description'] ?></li>
      </ul>
    </li>
  <?php endforeach; ?>
</ul>

<!-- Installation views -->
<?php $installations = get_field('installation_views'); ?>
<p>Installation views:</p>
<ul>
  <?php foreach ($installations as $i) : ?>
    <li>
      <ul>
        <li>Title: <?= $i['title'] ?></li>
        <li>Description: <?= $i['description'] ?></li>
        <li><img src='<?= $i['image']['url'] ?>'></li>
      </ul>
    </li>
  <?php endforeach; ?>
</ul>

<!-- Multimedia -->
<?php $multimedias = get_field('multimedias'); ?>
<p>Multimedia:</p>
<ul>
  <?php foreach ($multimedias as $m) : ?>
    <li>
      <ul>
        <li>Link: <a href='<?= $m['url'] ?>'><?= $m['title'] ?></a></li>
        <li>Description: <?= $m['description'] ?></li>
      </ul>
    </li>
  <?php endforeach; ?>
</ul>

<!-- Blog content -->
<!-- WIP -->
<p>Blog content: <?php the_field(''); ?></p>
