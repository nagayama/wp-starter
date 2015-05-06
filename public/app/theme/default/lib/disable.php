<?


// remove unnecessary header tags
add_action('init', function() {
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
  remove_action('wp_head', 'start_post_rel_link', 10, 0 );
  remove_action('wp_head', 'feed_links_extra',3);
  remove_action('wp_head', 'index_rel_link' );
  remove_action('wp_head', 'parent_post_rel_link', 10, 0 );
  remove_action('wp_head', 'rel_canonical');
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'wp_shortlink_wp_head');
});

// remove generator
add_filter('the_generator', '__return_false');

// remove xmlrpc
add_filter('xmlrpc_enabled', '__return_false');

// remove scripts from header
add_action("wp_enqueue_scripts", function() {
  remove_action('wp_head', 'wp_print_scripts');
  remove_action('wp_head', 'wp_print_head_scripts');
  remove_action('wp_head', 'wp_enqueue_scripts');
});

// remove ping back
add_filter('xmlrpc_methods', function ($methods) {
  unset($methods['pingback.ping']);
  return $methods;
});

// remove ping back
add_filter('wp_headers', function ($headers) {
  if (isset($headers['X-Pingback'])) {
    unset($headers['X-Pingback']);
  }
  return $headers;
});

// remove ping back
add_filter('rewrite_rules_array', function ($rules) {
  foreach ($rules as $rule => $rewrite) {
    if (preg_match('/trackback\/\?\$$/i', $rule)) {
      unset($rules[$rule]);
    }
  }
  return $rules;
});

// remove ping back
add_filter('bloginfo_url', function() {
  return null;
});

// remove ping back
add_action('xmlrpc_call', function ($action) {
  if ($action === 'pingback.ping') {
    wp_die('Pingbacks are not supported', 'Not Allowed!', array('response' => 403));
  }
});

// remove author pages
add_action( 'template_redirect', function () {
  if ( is_author() ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
  }
});

