<?php

namespace DaudinTheme\Core;

class Features {

  public function execute() {
    $this->register_hooks();
  }

  protected function register_hooks() {
    //add_action('pre_get_posts', array($this, 'pre_get_posts'));
    //add_action('init', array($this, 'add_routes'));
    //add_action('save_post', array($this, 'save_post', 10, 3));
  }

  public function pre_get_posts($wp_query) {

    // alter pre_get_posts hook

  }

  public function add_routes() {
    global $wp_rewrite;

    // # filtres
    // add_rewrite_tag('%filtre%','([^&]+)');

    // # filter home
    // $wp_rewrite->add_rule('filtre-([^/]+)$','index.php?filtre=$matches[1]','top');

    // # filter cats
    // $wp_rewrite->add_rule('categorie/(.+?)/filtre-([^/]+)$','index.php?category_name=$matches[1]&filtre=$matches[2]','top');

    // # filter author
    // $wp_rewrite->add_rule('author/(.+?)/filtre-([^/]+)$','index.php?author_name=$matches[1]&filtre=$matches[2]','top');

    //$wp_rewrite->flush_rules();
  }

  public function save_post($post_id, $post, $update) {

    // alter save_post hook

  }

}
