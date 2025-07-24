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
