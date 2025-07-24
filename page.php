<?php

$context = Timber::get_context();
$context['page'] = new TimberPost();

Timber::render(array($context['page']->post_name.'.twig', 'page.twig'), $context);
