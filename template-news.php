<?php
/**
 * Template Name: News
 * Description: For displaying the list of News
 */

$context = Timber::get_context();

$args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
);

$posts = Timber::get_posts( $args );

$context['page'] = new Timber\Post();
$context['posts'] = $posts;


Timber::render( 'news.twig', $context );
