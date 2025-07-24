<?php
/**
 * Template Name: home-page
 * Description: The Daudin Home Page
 */


$context = Timber::get_context();
$context['page'] = new TimberPost();

Timber::render(array('page.twig'), $context);
