<?php

$context = Timber::get_context();
$context['post'] = new TimberPost();
$templates = ['index'];

if ( is_home() ) {
  array_unshift( $templates, 'home' );
}

$templates = array_map(function($template) {
  if (preg_match("/\.twig$/", $template) < 1 ) {
    $template .= ".twig";
  }
  return $template;
}, $templates);

Timber::render( $templates, $context );
