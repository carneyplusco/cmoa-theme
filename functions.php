<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',        // Scripts and stylesheets
  'lib/extras.php',        // Custom functions
  'lib/setup.php',         // Theme setup
  'lib/titles.php',        // Page titles
  'lib/wrapper.php',       // Theme wrapper class
  'lib/customizer.php',    // Theme customizer
  'lib/api.php',           // Custom WP REST API endpoints
  'lib/shortcodes.php',    // Custom shortcodes
  'lib/query_args.php',    // Custom URL params
  'lib/post_types.php',    // Custom post types
  'lib/featured_item.php', // Featured item factory
  'lib/admin.php'          // Updates to WP-Admin
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

/**
 * Set max srcset image width to 3000px
 */
function remove_max_srcset_image_width( $max_width ) {
    $max_width = 3000;
    return $max_width;
}
add_filter( 'max_srcset_image_width', 'remove_max_srcset_image_width' );

/**
 * Adds extra image sizes for responsive images
 */
function image_size_chooser($sizes) {
  $add_sizes = [
    'small' => __('Small'),
    'large_thumbnail' => __('Large Thumbnail'),
    'extra_large_thumbnail' => __('Extra Large Thumbnail'),
    'large_square' => __('Large Square')
  ];
  $new_sizes = array_merge($sizes, $add_sizes);
  return $new_sizes;
}
add_filter('image_size_names_choose', 'image_size_chooser');
add_image_size('small', 512, 384, false);
add_image_size('large_thumbnail', 600, 400, true);
add_image_size('extra_large_thumbnail', 1200, 800, true);
add_image_size('large_square', 600, 600, true);

?>
