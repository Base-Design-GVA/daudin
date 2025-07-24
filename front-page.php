<?php

$context = Timber::get_context();
$context['post'] = new TimberPost();


// Custom Query
$args = array(
  'posts_per_page' => 4,
);
$context['custom_query'] = Timber::get_posts($args);

Timber::render('front-page.twig', $context);
