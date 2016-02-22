<?php

$context = Timber::get_context();
$context['post'] = new TimberPost();
$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();
$context['description'] = ""; 

$templates = ['index'];

if ( is_404() ) {
  array_unshift( $templates, '404' );
} elseif ( is_home() ) {
  array_unshift( $templates, 'home' );
} elseif ( is_singular() ) {
  array_unshift( $templates, 'single' );
} elseif ( is_archive() ) {
  if ( is_day() ) {
    $context['title'] = 'Archive: '.get_the_date( 'D M Y' );
  } else if ( is_month() ) {
    $context['title'] = 'Archive: '.get_the_date( 'M Y' );
  } else if ( is_year() ) {
    $context['title'] = 'Archive: '.get_the_date( 'Y' );
  } else if ( is_tag() ) {
    $context['title'] = single_tag_title( '', false );
  } else if ( is_category() ) {
    $context['title'] = single_cat_title( '', false );
  } else if ( is_post_type_archive() ) {
    $context['title'] = post_type_archive_title( '', false );
  }
  array_unshift( $templates, 'archive' );
} elseif ( is_page() ) {
  array_unshift( $templates, 'page' );
}

$templates = array_map(function($template) {
  if (preg_match("/\.twig$/", $template) < 1 ) {
    $template .= ".twig";
  }
  return $template;
}, $templates);

Timber::render( $templates, $context );
