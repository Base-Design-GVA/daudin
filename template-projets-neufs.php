<?php
/**
 * Template Name: Projets Neufs
 * Description: Liste des projets neufs
 */

$context = Timber::get_context();
$context['page'] = new TimberPost();

$args = array(
    'post_type' => 'projet-neuf',
    'posts_per_page' => -1,
    'post_status' => 'publish',
);

$context['projets'] = \Timber\Timber::get_posts($args);

if (!empty($context['projets'])) {
    $filters = array(
        'quartiers' => array(),
    );

    foreach ($context['projets'] as $projet) {
        $quartier = trim((string) $projet->meta('acf_annonce_quartier'));
        if (!empty($quartier)) {
            $filters['quartiers'][] = $quartier;
        }
    }

    $filters['quartiers'] = array_values(array_unique($filters['quartiers']));
    sort($filters['quartiers']);

    $context['filters'] = $filters;
}

Timber::render(array('template-projets-neufs.twig'), $context);
