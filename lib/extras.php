<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Setup\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

/**
 * Use | as separator
 */
function custom_separator($separator) {
  return "|";
}
add_filter('document_title_separator', __NAMESPACE__ . '\\custom_separator');

function get_toplevel_post($post_id) {
  $parent_id = get_post($post_id)->post_parent;
  if($parent_id == 0){
    return $post_id;
  }
  else {
    return get_toplevel_post($parent_id);
  }
}

function tab_bar($post) {
  $tab_bar = get_field('tab_bar', $post->ID);
  if($tab_bar) {
    wp_nav_menu(['container' => '', 'menu_class' => 'tabs--links', 'menu' => $tab_bar]);
  }
}

function sidebar_nav($post) {
  $tab_bar = get_field('tab_bar', $post->ID);
  if($tab_bar) {
    wp_nav_menu(['menu' => $tab_bar, 'container_class' => 'quickview quickview-nav', 'container_id' => 'quickview-sidebar-nav', 'menu_class' => '']);
  }
}

function get_image_from_url($image_url) {
	global $wpdb;
  $path = pathinfo($image_url);
  preg_match('/.+\/(\d{4})\/(\d{2})\/(.+)/', $image_url, $image_parts);
  list($full_url, $year, $month, $img) = $image_parts;
  if(isset($year, $month, $img)) {
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_value like '%s';", "%{$year}/{$month}/{$img}"));
    return isset($attachment[0]) ? $attachment[0] : '';
  }
  else {
    return '';
  }
}

function share_tools($networks, $url, $title, $summary, $image) {
  $links = [];
  foreach ($networks as $network) {
    switch ($network) {
      case 'facebook':
        $links[] = '<a href="#" class="sbg-button sbg-button-facebook" data-sbg-width="600" data-sbg-height="368" data-sbg-network="facebook" data-sbg-url="'.$url.'" data-sbg-title="'.$title.'" data-sbg-summary="'.$summary.'" data-sbg-image="'.$image.'"><i class="icon -facebook" aria-hidden="true"></i><span class="screen-reader-text">Share on Facebook</span></a>';
        break;
      case 'twitter':
        $links[] = '<a href="#" class="sbg-button sbg-button-twitter" data-sbg-network="twitter" data-sbg-text="'.$title.' - '.$url.'" data-sbg-via="cmoa" data-sbg-hashtags="" data-sbg-width="600" data-sbg-height="258"><i class="icon -twitter" aria-hidden="true"></i><span class="screen-reader-text">Share on Twitter</span></a>';
        break;
      case 'email':
        $links[] = '<a href="#" class="sbg-button sbg-button-email" data-sbg-network="email" data-sbg-subject="Sharing a Post from Carnegie Museum of Art" data-sbg-body="'.$title.' - '.$url.'"><i class="icon -envelop" aria-hidden="true"></i><span class="screen-reader-text">Share via Email</span></a>';
    }
  }
  $output = '<div class="social-links">';
    $output .= '<a href="#" class="social-links__open"><i class="icon -share" aria-hidden="true"></i> Share<span class="screen-reader-text">Share this post</span></a>';
    $output .= '<div class="social-links__services">';
      $output .= implode($links);
    $output .= '</div>';
  $output .= '</div>';
  return $output;
}

/**
* Add Open Graph options page
*/
if( function_exists('acf_add_options_page') ) {
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Open Graph Settings',
		'menu_title'	=> 'Open Graph',
		'parent_slug'	=> 'options-general.php',
	));
}

/**
* Set default open graph values
*/
function og_defaults($metas) {
  if(!isset($metas['og:image'])) {
    $metas['og:image'] = get_field('default_open_graph_image', 'option');
  }
  
  if(array_search('single-ai1ec_event', get_body_class())) {
    $metas = [];
    $metas['og:image'] = wp_get_attachment_image_src(get_post_thumbnail_id(), 'extra_large_thumbnail')[0];
  }

  return $metas;
}
add_filter('open_graph_protocol_metas',  __NAMESPACE__ . '\\og_defaults');
