<?php

add_filter('get_twig', function($twig) {

  $twig->addFunction(new Twig_SimpleFunction('asset_path', function($str) {
    return asset_path($str);
  }));

  return $twig;
});
