<?php

namespace DaudinTheme\Core;

class API {

  public function execute() {
    $this->register_hooks();
  }

  protected function register_hooks() {
    add_filter('json_query_vars', array($this, 'add_filters'));
    add_filter( 'json_prepare_post', array($this, 'add_custom_fields'), 10, 3);
  }

  public function add_filters($filters) {
    $metaparts = array('meta_key', 'meta_value', 'meta_compare', 'date_query');
    $filters = array_merge($filters, $metaparts);
    return $filters;
  }

  public function add_custom_fields($data, $post, $context) {
    $metas = get_post_meta( $post['ID'] );

    $meta_to_keep = array(
      "id_de_la_video" => $metas['id_de_la_video'],
      "mise_en_avant" => $metas['mise_en_avant'],
      "creation_gm" => $metas['creation_gm'],
      "auteurs" => $metas['auteurs'],
    );

    $data['post_meta'] = $meta_to_keep;
    return $data;
  }
}
