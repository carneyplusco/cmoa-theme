<?php

namespace Roots\Sage\Setup;

/**
 * Custom post types / taxonomies
*/
function create_post_types() {
  register_post_type('exhibition',
    array(
      'labels' => array(
        'name' => __('Exhibitions'),
        'singular_name' => __('Exhibition'),
        'add_new' => __('Add New' ),
        'add_new_item' => __('Add New Exhibition'),
        'edit' => __('Edit'),
        'edit_item' => __('Edit Exhibition'),
        'new_item' => __('New Exhibition'),
        'view' => __('View Exhibition'),
        'view_item' => __('View Exhibition'),
        'search_items' => __('Search Exhibitions'),
        'all_items' => __('All Exhibitions'),
        'not_found' => __('No exhibitions found.'),
        'not_found_in_trash' => __('No exhibitions found in Trash')
      ),
      'public' => true,
      'hierarchical' => true,
      'supports' => array(
        'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt'
      ),
      'map_meta_cap' => true,
      'capabilities' => array(
        'read_posts' => 'read_exhibitions',
        'read_private_posts' => 'read_private_exhibitions',
        'edit_posts' => 'edit_exhibitions',
        'edit_others_posts' => 'edit_others_exhibitions',
        'edit_private_posts' => 'edit_private_exhibitions',
        'edit_published_posts' => 'edit_exhibitions',
        'publish_posts' => 'publish_exhibitions',
        'create_posts' => 'edit_exhibitions',
        'delete_posts' => 'delete_exhibitions',
        'delete_published_posts' => 'delete_exhibitions',
        'delete_others_posts' => 'delete_others_exhibitions',
        'delete_private_posts' => 'delete_private_exhibitions',
      ),
      'rewrite' => array('slug' => 'exhibition', 'with_front' => false),
      'menu_icon' => 'dashicons-tickets-alt'
    )
  );

  register_post_type('publication',
    array(
      'labels' => array(
        'name' => __('Publications'),
        'singular_name' => __('Publication'),
        'add_new' => __('Add New' ),
        'add_new_item' => __('Add New Publication'),
        'edit' => __('Edit'),
        'edit_item' => __('Edit Publication'),
        'new_item' => __('New Publication'),
        'view' => __('View Publication'),
        'view_item' => __('View Publication'),
        'search_items' => __('Search Publications'),
        'all_items' => __('All Publications'),
        'not_found' => __('No publications found.'),
        'not_found_in_trash' => __('No publications found in Trash')
      ),
      'public' => true,
      'hierarchical' => false,
      //'has_archive' => true,
      'supports' => array(
        'title', 'editor', 'thumbnail', 'excerpt'
      ),
      'map_meta_cap' => true,
      'capabilities' => array(
        'read_posts' => 'read_publications',
        'read_private_posts' => 'read_private_publications',
        'edit_posts' => 'edit_publications',
        'edit_others_posts' => 'edit_others_publications',
        'edit_private_posts' => 'edit_private_publications',
        'edit_published_posts' => 'edit_publications',
        'publish_posts' => 'publish_publications',
        'create_posts' => 'edit_publications',
        'delete_posts' => 'delete_publications',
        'delete_published_posts' => 'delete_publications',
        'delete_others_posts' => 'delete_others_publications',
        'delete_private_posts' => 'delete_private_publications',
      ),
      'rewrite' => array('slug' => 'publications', 'with_front' => false),
      'menu_icon' => 'dashicons-awards'
    )
  );
}
add_action('init', __NAMESPACE__ . '\\create_post_types');


function feature_columns($columns) {
  return ['item_title' => 'Item Title', 'date' => 'Date'];
}

function feature_columns_content($column_name, $post_ID) {
  $item_post = get_field('item', $post_ID);
  $post_types = ['ai1ec_event' => 'Event'];
  if($column_name == 'item_title') {
    $type = isset($post_types[$item_post->post_type]) ? $post_types[$item_post->post_type] : ucwords($item_post->post_type);
    echo "$type: $item_post->post_title";
  }
}
add_filter('manage_home_feature_posts_columns', __NAMESPACE__ . '\\feature_columns', 10);
add_action('manage_home_feature_posts_custom_column', __NAMESPACE__ . '\\feature_columns_content', 10, 2);
add_filter('manage_program_feature_posts_columns', __NAMESPACE__ . '\\feature_columns', 10);
add_action('manage_program_feature_posts_custom_column', __NAMESPACE__ . '\\feature_columns_content', 10, 2);


function exhibition_taxonomies() {
  register_taxonomy(
		'departments',
		'exhibition',
		array(
			'labels' => array(
        'name' => __('Departments / Subjects'),
        'singular_name' => __('Department / Subject'),
        'search_items' => __('Search Departments / Subjects'),
        'all_items' => __('All Depts. / Subjects'),
        'parent_item' => __('Parent Department / Subject'),
        'parent_item_colon' => __('Parent Department / Subject:'),
        'edit_item' => __('Edit Department / Subject'),
        'update_item' => __('Update Department / Subject'),
        'add_new_item' => __('Add New Department / Subject'),
        'new_item_name' => __('New Department / Subject Name'),
        'menu_name' => __('Depts. / Subjects'),
        'not_found' => __('No departments / subjects found.'),
        'not_found_in_trash' => __('No departments / subjects found in Trash')
      ),
      'hierarchical' => true,
			'rewrite' => array('slug' => 'department', 'with_front' => false)
		)
	);

  register_taxonomy(
		'series',
		'exhibition',
		array(
			'labels' => array(
        'name' => __('Series'),
        'singular_name' => __('Series'),
        'search_items' => __('Search Series'),
        'all_items' => __('All Series'),
        'parent_item' => __('Parent Series'),
        'parent_item_colon' => __('Parent Series:'),
        'edit_item' => __('Edit Series'),
        'update_item' => __('Update Series'),
        'add_new_item' => __('Add New Series'),
        'new_item_name' => __('New Series Name'),
        'menu_name' => __('Series'),
        'not_found' => __('No series found.'),
        'not_found_in_trash' => __('No series found in Trash')
      ),
      'hierarchical' => true,
			'rewrite' => array('slug' => 'series', 'with_front' => false)
		)
	);

  register_taxonomy(
    'locations',
    ['exhibition', 'ai1ec_event'],
    array(
      'labels' => array(
        'name' => __('Locations'),
        'singular_name' => __('Location'),
        'search_items' => __('Search Locations'),
        'all_items' => __('All Locations'),
        'parent_item' => __('Parent Location'),
        'parent_item_colon' => __('Parent Location:'),
        'edit_item' => __('Edit Location'),
        'update_item' => __('Update Location'),
        'add_new_item' => __('Add New Location'),
        'new_item_name' => __('New Location Name'),
        'menu_name' => __('Locations'),
        'not_found' => __('No locations found.'),
        'not_found_in_trash' => __('No locations found in Trash')
      ),
      'hierarchical' => true,
      'rewrite' => array('slug' => 'location', 'with_front' => false)
    )
  );

  register_taxonomy(
    'curators',
    'exhibition',
    array(
      'labels' => array(
        'name' => __('Curators'),
        'singular_name' => __('Curator'),
        'search_items' => __('Search Curators'),
        'all_items' => __('All Curators'),
        'parent_item' => __('Parent Curator'),
        'parent_item_colon' => __('Parent Curator:'),
        'edit_item' => __('Edit Curator'),
        'update_item' => __('Update Curator'),
        'add_new_item' => __('Add New Curator'),
        'new_item_name' => __('New Curator Name'),
        'menu_name' => __('Curators'),
        'not_found' => __('No curators found.'),
        'not_found_in_trash' => __('No curators found in Trash')
      ),
      'hierarchical' => false,
      'rewrite' => array('slug' => 'curator', 'with_front' => false)
    )
  );

  register_taxonomy(
    'associated_artists',
    'exhibition',
    array(
      'labels' => array(
        'name' => __('Associated Artists'),
        'singular_name' => __('Associated Artist'),
        'search_items' => __('Search Associated Artists'),
        'all_items' => __('All Associated Artists'),
        'parent_item' => __('Parent Associated Artist'),
        'parent_item_colon' => __('Parent Associated Artist:'),
        'edit_item' => __('Edit Associated Artist'),
        'update_item' => __('Update Associated Artist'),
        'add_new_item' => __('Add New Associated Artist'),
        'new_item_name' => __('New Associated Artist Name'),
        'menu_name' => __('Associated Artists'),
        'not_found' => __('No associated artists found.'),
        'not_found_in_trash' => __('No associated artists found in Trash')
      ),
      'hierarchical' => false,
      'rewrite' => array('slug' => 'associated-artists', 'with_front' => false)
    )
  );
}
add_action('init', __NAMESPACE__ . '\\exhibition_taxonomies');

?>
