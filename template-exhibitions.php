<?php
/**
 * Template Name: Exhibitions
 */

  if(get_query_var('past')) {
    include(locate_template('template-exhibitions-past.php'));
  }
  else {
    include(locate_template('template-exhibitions-current.php'));
  }
?>
