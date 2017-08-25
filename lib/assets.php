<?php

namespace Roots\Sage\Assets;

/**
 * Get paths for assets
 */
class JsonManifest {
  private $manifest;

  public function __construct($manifest_path) {
    if (file_exists($manifest_path)) {
      $this->manifest = json_decode(@file_get_contents($manifest_path), true);
    } else {
      $this->manifest = [];
    }
  }

  public function get() {
    return $this->manifest;
  }

  public function getPath($key = '', $default = null) {
    $collection = $this->manifest;
    if (is_null($key)) {
      return $collection;
    }
    if (isset($collection[$key])) {
      return $collection[$key];
    }
    foreach (explode('.', $key) as $segment) {
      if (!isset($collection[$segment])) {
        return $default;
      } else {
        $collection = $collection[$segment];
      }
    }
    return $collection;
  }
}

function asset_path($filename) {
  $dist_path = get_template_directory_uri() . '/dist/';
  $directory = dirname($filename) . '/';
  $file = basename($filename);
  static $manifest;

  if (empty($manifest)) {
    $manifest_path = get_template_directory() . '/dist/' . 'rev-manifest.json';
    $manifest = new JsonManifest($manifest_path);
  }

  if (array_key_exists($filename, $manifest->get())) {
    return $dist_path . $manifest->get()[$filename];
  } else {
    return $dist_path . $directory . $file;
  }
}

function inline_svg($filename) {
  return file_get_contents(asset_path($filename));
}

function get_template_part($slug, $name, $vars) {
  extract($vars);
  $template = $slug.'-'.$name.'.php';
  include(locate_template($template));
}

function hero_image($post) {
  $thumb = get_the_post_thumbnail($post);
  if(!empty($thumb)) {
    return $thumb;
  }
  elseif($post->post_parent == 0) {
    return "";
  }
  else {
    $parent = get_post($post->post_parent);
    return hero_image($parent);
  }
}

function image_credits($post) {
  $thumbnail_id = get_post_thumbnail_id($post);
  $image = get_post($thumbnail_id);
  return $image->post_excerpt;
}

function image_alt_text($attachment_id) {
  return get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
}

?>
