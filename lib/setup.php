<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

/**
 * Theme setup
 */
function setup() {
  // Enable features from Soil when plugin is activated
  // https://roots.io/plugins/soil/
  add_theme_support('soil-clean-up');
  add_theme_support('soil-nav-walker');
  add_theme_support('soil-nice-search');
  add_theme_support('soil-jquery-cdn');
  add_theme_support('soil-relative-urls');

  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('sage', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  add_theme_support('title-tag');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'sage'),
    'quickview_navigation' => __('Quickview Navigation', 'sage'),
    'footer_navigation' => __('Footer Navigation', 'sage'),
  ]);


  // Enable post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');

  // Enable post formats
  // http://codex.wordpress.org/Post_Formats
  add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

  // Enable HTML5 markup support
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

  // Use main stylesheet for visual editor
  // To add custom styles edit /assets/styles/layouts/_tinymce.scss
  add_editor_style(Assets\asset_path('styles/main.css'));
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Register sidebars
 */
function widgets_init() {
  register_sidebar([
    'name' => __('Primary', 'sage'),
    'id' => 'sidebar-primary',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget' => '</section>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ]);

  register_sidebar([
    'name' => __('Footer', 'sage'),
    'id' => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget' => '</section>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ]);

}
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  static $display;

  isset($display) || $display = !in_array(true, [
    // The sidebar will NOT be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags
    is_404(),
    is_front_page(),
    is_page_template('template-custom.php'),
  ]);

  return apply_filters('sage/display_sidebar', $display);
}

/**
 * Theme assets
 */
function assets() {
  wp_enqueue_style('sage/css', Assets\asset_path('styles/main.css'), false, null);

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_enqueue_script('webpack-common', Assets\asset_path('scripts/common.js'), [], null, true);
  wp_enqueue_script('sage/js', Assets\asset_path('scripts/cmoa.js'), ['jquery'], null, true);

  wp_dequeue_style('ai1ec_style');
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);

/**
 * Theme extras: Featherlight
 */
function featherlight() {
  if (is_singular('exhibition')) {
    wp_enqueue_style('featherlight', Assets\asset_path('bower_components/featherlight/release/featherlight.min.css'), false, null);
    wp_enqueue_style('featherlight/gallery', Assets\asset_path('bower_components/featherlight/release/featherlight.gallery.min.css'), false, null);

    wp_enqueue_script('featherlight', Assets\asset_path('bower_components/featherlight/release/featherlight.min.js'), ['jquery'], null, true);
    wp_enqueue_script('featherlight/gallery', Assets\asset_path('bower_components/featherlight/release/featherlight.gallery.min.js'), ['featherlight'], null, true);
  }
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\featherlight', 100);

/**
 * Update CSS within in Admin
 */
function admin_style() {
  wp_enqueue_style('admin-styles', Assets\asset_path('styles/admin.css'));
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\admin_style');

/**
 * Enables the Excerpt meta box in Page edit screen.
 */
function add_excerpt_support_for_pages() {
	add_post_type_support('page', 'excerpt');
}
add_action('init', __NAMESPACE__ . '\\add_excerpt_support_for_pages');

/**
 * Change the base url for All-in-One Calendar events to 'calendar'
 */
function calendar_base_url() {
  global $ai1ec_registry;
  $ai1ec_settings = $ai1ec_registry->get('model.settings');
  $ai1ec_settings->register(
    'calendar_base_url_for_permalinks',
    'calendar',
    'string',
    NULL
  );
}
//add_action('after_setup_theme', __NAMESPACE__ . '\\calendar_base_url');

/**
 * Add rewrite rule for calendar categories
 */
function custom_calendar_categories() {
  add_rewrite_rule('^calendar/category/([^/]*)/?', 'index.php?pagename=calendar&event_category=$matches[1]', 'top');
  add_rewrite_rule('^calendar/tag/([^/]*)/?', 'index.php?pagename=calendar&event_tag=$matches[1]', 'top');
  add_rewrite_rule('^calendar/([^/]*)/([^/]*)?', 'index.php?ai1ec_event=$matches[2]', 'top');
  add_rewrite_rule('^calendar/([^/]*)/?', 'index.php?events_categories=$matches[1]', 'top');
}
add_action('init', __NAMESPACE__ . '\\custom_calendar_categories');

function custom_exhibition_urls() {
  add_rewrite_endpoint('past', EP_ALL);
}
add_action('init', __NAMESPACE__ . '\\custom_exhibition_urls');

/**
 * WIP: trying to disable the redirects from the All-in-One Calendar plugin
 */
function disable_calendar_redirects() {
  global $ai1ec_registry;
  $action = $ai1ec_registry->get('event.callback.action', 'request.redirect', 'handle_categories_and_tags');
  remove_action('send_headers', array( $action, 'run' ), 10);
}
//add_action('send_headers', __NAMESPACE__ . '\\disable_calendar_redirects', 10);

/**
 * Change admin menu item labels/icons/etc
 */
function change_admin_menu_labels() {
  global $menu;
  foreach ($menu as &$menu_item) {
    if($menu_item[0] == "Events") {
      $menu_item[0] = "Calendar";
      $menu_item[6] = "dashicons-calendar";
    }
  }
  return $menu;
}
add_action('admin_menu', __NAMESPACE__ . '\\change_admin_menu_labels');

/**
 * Prevent TinyMCE from stripping out html
 */
function schema_TinyMCE_init($in)
{
    /**
     *   Edit extended_valid_elements as needed. For syntax, see
     *   http://www.tinymce.com/wiki.php/Configuration:valid_elements
     *
     *   NOTE: Adding an element to extended_valid_elements will cause TinyMCE to ignore
     *   default attributes for that element.
     *   Eg. a[title] would remove href unless included in new rule: a[title|href]
     */
    if(!empty($in['extended_valid_elements'])) {
      $in['extended_valid_elements'] .= ',';
    }
    else {
      $in['extended_valid_elements'] = '';
    }

    $in['extended_valid_elements'] .= '@[id|class|style|title|itemscope|itemtype|itemprop|datetime|rel|aria-label|aria-hidden],div,dl,ul,ol,dt,dd,li,span,a|rev|charset|href|lang|tabindex|accesskey|type|name|href|target|title|class|onfocus|onblur]';

    return $in;
}
add_filter('tiny_mce_before_init', __NAMESPACE__ . '\\schema_TinyMCE_init' );

/**
 * Adjust search query to include all sites on network
 */
function search_all_sites( $query ) {
  if ($query->is_search() && $query->is_main_query()) {
    $query->query_vars['sites'] = 'all'; // search all elasticsearch indices
  }
}
add_action('pre_get_posts', __NAMESPACE__ . '\\search_all_sites');

?>
