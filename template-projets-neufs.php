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
        'types_biens' => array(),
        'etats' => array(),
        'quartiers' => array(),
    );
    $annonce_bandeaux = array();
    $annonce_bandeaux_couleurs = array();

    foreach ($context['projets'] as $projet) {
        $bandeau_group = get_field('acf_annonce_bandeau_groupe', $projet->ID);
        $annonce_bandeaux[$projet->ID] = '';
        $annonce_bandeaux_couleurs[$projet->ID] = 'vert';
        if (is_array($bandeau_group) && !empty($bandeau_group['acf_annonce_bandeau'])) {
            $annonce_bandeaux[$projet->ID] = (string) $bandeau_group['acf_annonce_bandeau'];
        }
        if (is_array($bandeau_group) && !empty($bandeau_group['acf_annonce_couleur_bandeau'])) {
            $annonce_bandeaux_couleurs[$projet->ID] = (string) $bandeau_group['acf_annonce_couleur_bandeau'];
        }

        $type_bien = trim((string) $projet->meta('acf_annonce_famille_de_bien'));
        if (!empty($type_bien)) {
            $type_field = get_field_object('acf_annonce_famille_de_bien', $projet->ID);
            if (!empty($type_field['choices'][$type_bien])) {
                $filters['types_biens'][$type_bien] = $type_field['choices'][$type_bien];
            }
        }

        $etat = trim((string) $projet->meta('acf_annonce_etat'));
        if (!empty($etat)) {
            $etat_field = get_field_object('acf_annonce_etat', $projet->ID);
            if (!empty($etat_field['choices'][$etat])) {
                $filters['etats'][$etat] = $etat_field['choices'][$etat];
            }
        }

        $quartier = trim((string) $projet->meta('acf_annonce_quartier'));
        if (!empty($quartier)) {
            $filters['quartiers'][] = $quartier;
        }
    }

    ksort($filters['types_biens']);
    ksort($filters['etats']);
    $filters['quartiers'] = array_values(array_unique($filters['quartiers']));
    sort($filters['quartiers']);

    $context['filters'] = $filters;
    $context['annonce_bandeaux'] = $annonce_bandeaux;
    $context['annonce_bandeaux_couleurs'] = $annonce_bandeaux_couleurs;
}

Timber::render(array('template-projets-neufs.twig'), $context);
