<?php

namespace DaudinTheme\Core;

class Plugins {

  public function execute() {
    $this->register_hooks();
  }

  protected function register_hooks() {
    add_filter('wpseo_metabox_prio', array($this, 'yoast_move_meta_box_bottom'));
    add_filter('manage_edit-post_columns', array($this, 'yoast_clean_columns'), 10, 1);
    add_filter('manage_edit-page_columns', array($this, 'yoast_clean_columns'), 10, 1);
    add_action('wp_before_admin_bar_render', array($this, 'yoast_clean_admin_bar'));

    //add_action('add_meta_boxes', array($this,'remove_monarch_meta_boxes'));
  }

  public function yoast_move_meta_box_bottom() {
    return 'low';
  }

  public function yoast_clean_columns($columns) {
    unset($columns['wpseo-readability']);
    unset($columns['wpseo-score']);

    return $columns;
  }

  public function yoast_clean_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wpseo-menu');
  }

  public function remove_monarch_meta_boxes() {
    if (is_admin()){

      // post
      remove_meta_box('et_monarch_settings', 'post', 'advanced');
      remove_meta_box('et_monarch_sharing_stats', 'post', 'advanced');

      // page
      remove_meta_box('et_monarch_settings', 'page', 'advanced');
      remove_meta_box('et_monarch_sharing_stats', 'page', 'advanced');
    }
  }
}
