<?php
/**
 * Template Name: Louer
 * Description: Pour la page à louer
 */

function getMinMaxArray($array)
{
    if (gettype($array[0]) == "string") {
        foreach ($array as $key => $value) {
            $array[$key] = floatval($value);
        }
    }
    return array(
        'min' => min($array),
        'max' => max($array),
    );
}

$context = Timber::get_context();
$context['page'] = new TimberPost();

$args = array(
    'post_type' => 'rdr_annnonce',
    'meta_key' => 'acf_annonce_type_deal',
    'meta_value' => $context['page']->acf_block_mode,
    'posts_per_page' => -1
);

$context['annonces'] = \Timber\Timber::get_posts($args);

if (!empty($context['annonces'])) {
    $filters['types_biens'] = [];
    $filters['quartiers'] = [];
    $filters['prix'] = [];
    $filters['surface'] = [];
    $filters['nbr_piece'] = [];
    $annonce_bandeaux = [];
    $annonce_bandeaux_couleurs = [];

//on regarde les différentes valeurs pour pouvoir construire les filtres

    foreach ($context['annonces'] as $annonce) {
        $bandeau_group = get_field('acf_annonce_bandeau_groupe', $annonce->ID);
        $annonce_bandeaux[$annonce->ID] = '';
        $annonce_bandeaux_couleurs[$annonce->ID] = 'vert';
        if (is_array($bandeau_group) && !empty($bandeau_group['acf_annonce_bandeau'])) {
            $annonce_bandeaux[$annonce->ID] = (string) $bandeau_group['acf_annonce_bandeau'];
        }
        if (is_array($bandeau_group) && !empty($bandeau_group['acf_annonce_couleur_bandeau'])) {
            $annonce_bandeaux_couleurs[$annonce->ID] = (string) $bandeau_group['acf_annonce_couleur_bandeau'];
        }

        $acf_annonce_famille_de_bien = get_field_object('acf_annonce_famille_de_bien', $annonce->ID);

        $filters['types_biens'][$annonce->meta("acf_annonce_famille_de_bien")] = $acf_annonce_famille_de_bien['choices'][$annonce->meta("acf_annonce_famille_de_bien")];
        $filters['quartiers'][] = $annonce->meta("acf_annonce_quartier");
        $filters['prix'][] = round( ( floatval($annonce->meta("acf_annonce_prix")) - floatval($annonce->meta("acf_annonce_charges")) ) );
        $filters['surface'][] = $annonce->meta("acf_annonce_surface");
        $filters['nbr_piece'][] = $annonce->meta("acf_annonce_nb_pieces");
    }

    ksort($filters['types_biens']);

    $filters['quartiers'] = array_unique($filters['quartiers']);


// pour les filtres types de bien on ajoute une option "tout"

//pour les valeurs numériques on ne garde que min et max dans un tableau qui devient associatif
    $filters['prix'] = getMinMaxArray($filters['prix']);
    $filters['surface'] = getMinMaxArray($filters['surface']);
    $filters['nbr_piece'] = getMinMaxArray($filters['nbr_piece']);

//on ajoute les filtres au context
    $context['filters'] = $filters;
    $context['annonce_bandeaux'] = $annonce_bandeaux;
    $context['annonce_bandeaux_couleurs'] = $annonce_bandeaux_couleurs;
}


Timber::render(array('template-louer.twig'), $context);

