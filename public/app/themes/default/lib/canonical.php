<?php

add_action('wp_head', function() {
  global $wp_the_query;
  if ($id = $wp_the_query->get_queried_object_id()) {
    $link = get_permalink($id);
    echo "<link rel=\"canonical\" href=\"$link\">\n";
  }
});
