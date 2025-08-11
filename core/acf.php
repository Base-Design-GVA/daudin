<?php

namespace DaudinTheme\Core;

class ACF {

  const ACF = "acf-pro/acf.php";

  public function execute() {
    $this->register_hooks();
  }

  protected function register_hooks() {
    add_filter('plugin_action_links', array($this, 'disallow_acf_deactivation'), 10, 4);
    add_filter('acf/settings/save_json', array($this, 'json_save_groups'));
    add_action('init', array($this, 'set_options_pages'));
    add_action( 'acf/init', array($this, 'acf_g_block') );
    //add_action( 'acf/init', 'my_acf_init' );

    // Remove WP Custom Fields for better performance
    add_filter('acf/settings/remove_wp_meta_box', '__return_true');
  }

  function disallow_acf_deactivation($actions, $plugin_file, $plugin_data, $context) {
    if (array_key_exists('deactivate', $actions) and $plugin_file == self::ACF) {
      unset( $actions['deactivate'] );
    }
    return $actions;
  }

  public function json_save_groups($path) {
    $path = get_stylesheet_directory() . '/acf-json';
    return $path;
  }

  public function set_options_pages() {
    // Check if ACF is available before using its functions
    //if (!function_exists('acf_add_options_page')) {
    //  return;
    //}
    
    acf_add_options_page(array(
      'page_title'    => 'Theme Options',
      'menu_title'    => 'Theme Options',
      'menu_slug'     => 'Daudin-options',
      'capability'    => 'edit_posts',
      'position'      => 3,
      'icon_url'      => 'dashicons-welcome-widgets-menus'
    ));

    acf_add_options_sub_page(array(
      'page_title'    => 'Header / Général',
      'menu_title'    => 'Header',
      'parent_slug'   => 'Daudin-options',
    ));

//    acf_add_options_sub_page(array(
//      'page_title'    => 'Textes',
//      'menu_title'    => 'Textes',
//      'parent_slug'   => 'Daudin-options',
//    ));

    acf_add_options_sub_page(array(
      'page_title'    => 'Annonces',
      'menu_title'    => 'Annonces',
      'parent_slug'   => 'Daudin-options',
    ));

    acf_add_options_sub_page(array(
      'page_title'    => 'Footer',
      'menu_title'    => 'Footer',
      'parent_slug'   => 'Daudin-options',
    ));
    acf_add_options_sub_page(array(
      'page_title'    => 'immomig',
      'menu_title'    => 'immomig',
      'parent_slug'   => 'Daudin-options',
    ));
  }

  public function acf_g_block() {
    // Bail out if function doesn’t exist.
    if ( ! function_exists( 'acf_register_block' ) ) {
        return;
    }

    // Register a new block.
    acf_register_block( array(
        'name'            => 'dernieres news',
        'title'           => __( 'Dernieres News', 'daudin' ),
        'description'     => __( 'A custom example block.', 'daudin' ),
        'render_template' => 'views/last-news.php',
        'category'        => 'formatting',
        'icon'            => 'admin-comments',
        'keywords'        => array( 'news', 'dernieres', 'last', 'last news' ),
    ) );
  }

}






