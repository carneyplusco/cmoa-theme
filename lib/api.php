<?php
namespace CMOA\API;

/**
 * Get exhibitions data between start and end times then return them as JSON objects
 *
 * @param array $args Arguments from the API call containing start and end dates in Ymd format.
 * @return array|null JSON array of objects or empty array if no exhibitions
 */

function fetch_exhibitions($args) {
  $now = new \DateTime();
  $start = (\DateTime::createFromFormat('Ymd', $args['start'])) ?: $now;
  $end = (\DateTime::createFromFormat('Ymd', $args['end'])) ?: $now;
  $exhibitions = get_exhibitions_in_range($start->format('Ymd'), $end->format('Ymd'));
  $data = get_exhibition_data($exhibitions);
  return $data;
}

function get_exhibitions_in_range($start, $end) {
  $query = array(
    'post_type'     => 'exhibition',
    'post_status'   => 'publish',
    'meta_key'      => 'end_date',
    'orderby'       => 'meta_value',
    'order'         => 'ASC',
    'meta_query'    => array(
      'relation' => 'OR',
      // Exhibitions that start between $start and $end
      array(
        'key'     => 'start_date',
        'value'   => array($start, $end),
        'type'    => 'numeric',
        'compare' => 'BETWEEN'
      ),
      // Exhibitions that end between $start and $end
      array(
        'key'     => 'end_date',
        'value'   => array($start, $end),
        'type'    => 'numeric',
        'compare' => 'BETWEEN'
      ),
      // Exhibitions that are running through $start and $end
      array(
        'relation'  => 'AND',
        array(
          array(
            'key'     => 'start_date',
            'value'   => $start,
            'compare' => '<'
          ),
          array(
            'key'     => 'end_date',
            'value'   => $end,
            'compare' => '>'
          )
        )
      )
    )
  );

  $exhibitions = new \WP_Query($query);
  return $exhibitions;
}

function get_exhibition_data($exhibitions) {
  $out = [];

  if ( $exhibitions->have_posts() ) : while ( $exhibitions->have_posts() ) : $exhibitions->the_post();
    $e = [];

    // WP_Post content
    $e['order'] =  get_post_field('menu_order', get_the_ID());
    $e['title'] = get_the_title();
    $e['content'] = get_the_content();
    $e['featured_image'] = get_the_post_thumbnail();

    // Taxonomies
    $locations = wp_get_post_terms(get_the_ID(), 'locations', array('fields' => 'names'));
    $e['locations'] = $locations;

    $curators = wp_get_post_terms(get_the_ID(), 'curators', array('fields' => 'names'));
    $e['curators'] = $curators;

    $departments = wp_get_post_terms(get_the_ID(), 'departments', array('fields' => 'names'));
    $e['departments'] = $departments;

    $series = wp_get_post_terms(get_the_ID(), 'series', array('fields' => 'names'));
    $e['series'] = $series;

    $artists = wp_get_post_terms(get_the_ID(), 'associated_artists', array('fields' => 'names'));
    $e['artists'] = $artists;

    // ACF content
    $e['start_date'] = get_field('start_date');
    $e['end_date'] = get_field('end_date');

    $checklists = get_field('checklists') ?: [];
    $e['checklist'] = [];
    foreach ($checklists as $c) {
      $e['checklist']['filename'] = $c['file']['filename'];
      $e['checklist']['url'] = $c['file']['url'];
      $e['checklist']['notes'] = $c['notes'];
    }

    $gallery_texts = get_field('gallery_texts') ?: [];
    $e['gallery_texts'] = [];
    foreach ($gallery_texts as $g) {
      $text = [];
      $text['title'] = $g['title'];
      $text['url'] = $g['file']['url'];
      $text['description'] = $g['description'];
      $e['gallery_texts'][] = $text;
    }

    $installations = get_field('installation_views') ?: [];
    $e['installations'] = [];
    foreach ($installations as $i) {
      $installation = [];
      $installation['title'] = $i['title'];
      $installation['description'] = $i['description'];
      $installation['image'] = $i['image']['url'];
      $e['installations'][] = $installation;
    }

    $multimedias = get_field('multimedias') ?: [];
    $e['multimedias'] = [];
    foreach ($multimedias as $m) {
      $multimedia = [];
      $multimedia['url'] = $m['url'];
      $multimedia['title'] = $m['title'];
      $multimedia['description'] = $m['description'];
      $e['multimedias'][] = $multimedia;
    }

    $e['meta'] = get_post_meta(get_the_ID());

    $out[] = $e;
  endwhile;
  endif;

  return $out;
}

add_action('rest_api_init', function () {
  register_rest_route('exhibitions/v1', '/start/(?P<start>\d+)/end/(?P<end>\d+)', array(
    'methods' => 'GET',
    'callback' => __NAMESPACE__.'\\fetch_exhibitions'
  ));
});

?>
