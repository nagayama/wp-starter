<?php

define("DIST_DIR", "dist");

$assets_data = null;

function asset_path($relpath) {
  global $assets_data;
  $assets_json_path = implode([get_template_directory(), DIST_DIR, "manifest.json"], "/");
  if( is_null($assets_data) ) {
    $assets_data = [];
    if ( file_exists($assets_json_path) ) {
      $assets_data = json_decode(file_get_contents($assets_json_path), true);
    }
  }

  $filename = basename($relpath);
  $dirname  = dirname($relpath);


  $asset_path = [get_template_directory_uri(), DIST_DIR];

  if( !empty($assets_data[$filename]) ) {
    $filename = $assets_data[$filename];
  }

  array_push($asset_path, $dirname, $filename);

  return implode($asset_path, "/");
}


add_action("wp_enqueue_scripts", function() {
  wp_enqueue_style( "mainstyle", asset_path("styles/main.css"), [], null );
  wp_enqueue_script( "_jquery", "https://code.jquery.com/jquery-1.11.2.min.js", [], null, true );
  wp_enqueue_script( "_mainjs", asset_path("scripts/main.js"), [], null, true );
});


