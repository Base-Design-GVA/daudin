<?php

$context = Timber::get_context();

$context['post'] = new TimberPost();

if (post_password_required($post->ID)) {
    $template = 'password.twig';
} else {
    if (is_singular("rdr_annnonce")) {
        $template = 'single-annonce.twig';

        //on ajoute le contact et la page quartier au context (si non vides)
        if( isset($post->custom['acf_annonce_email_agent']) && !empty($post->custom['acf_annonce_email_agent']) ) {
            $args = array(
                'post_type' => 'equipe',
                'meta_query' => array(
                    array(
                        'key' => 'acf_email',
                        "value" => $post->custom['acf_annonce_email_agent'],
                        'compare' => 'LIKE'
                    )
                )
            );

            $result = \Timber\Timber::get_posts($args);
            if (!empty($result)) {
                $context['contact'] = $result[0];
            }
        }

        if( isset($post->custom['acf_annonce_quartier']) && !empty($post->custom['acf_annonce_quartier']) ) {
            $args = array(
                'post_type' => 'page',
                'meta_query' => array(
                    array(
                        'key' => 'acf_quartier_correspondant',
                        "value" => $post->custom['acf_annonce_quartier'],
                        'compare' => 'LIKE'
                    )
                )
            );

            $result = \Timber\Timber::get_posts($args);

            if (!empty($result)) {
                $context['quartier'] = $result[0];
            }
        }

    } elseif (is_singular("projet-neuf")) {
        $listingPages = get_posts(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'meta_key' => '_wp_page_template',
            'meta_value' => 'template-projets-neufs.php',
        ));

        $redirectUrl = home_url('/');
        if (!empty($listingPages)) {
            $redirectUrl = get_permalink($listingPages[0]->ID);
        }

        wp_safe_redirect($redirectUrl, 302);
        exit;
    } else {
        //$template = 'single.twig';
        //$template = 'page.twig';

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post__not_in' => array( $post->ID )
        );

        $lastPosts = \Timber\Timber::get_posts( $args );

        $context['lastPosts'] = $lastPosts;

        $template = 'single-article.twig';
    }
}

Timber::render($template, $context);
