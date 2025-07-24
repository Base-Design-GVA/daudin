<?php

namespace DaudinTheme\Core;

class CPT {

  public function execute() {
    $this->register_hooks();
  }

  protected function register_hooks() {
    add_action('init', array($this, 'create_post_types'));
    add_action('admin_init',array($this,'cpt_add_role_caps'),999);
  }

  public function create_post_types() {
//
//    // Post Type
//    $labels = array(
//      'name' => 'Tutoriels',
//      'all_items' => 'Tous les tutoriels',
//      'singular_name' => 'Tutoriel',
//      'add_new_item' => 'Ajouter un tutoriel',
//      'edit_item' => 'Modifier le tutoriel',
//      'menu_name' => 'Tutoriels'
//    );
//
//    $args = array(
//      'labels' => $labels,
//      'public' => true,
//      'has_archive' => true,
//      'supports' => array('title', 'editor','thumbnail'),
//      'menu_position' => 20,
//      'rewrite' => array('slug' => 'tutoriels', 'with_front' => false),
//      'menu_icon' => 'dashicons-video-alt', // https://developer.wordpress.org/resource/dashicons/
//    );
//
//    register_post_type('tutoriels',$args);
//
    $labels = array(
      'name'                => _x( 'Annonces', 'Post Type General Name', 'rdrnd-plugin' ),
      'singular_name'       => _x( 'Annonce', 'Post Type Singular Name', 'rdrnd-plugin' ),
      'menu_name'           => __( 'Annonces', 'rdrnd-plugin' ),
      'parent_item_colon'   => __( 'Parent Item:', 'rdrnd-plugin' ),
      'all_items'           => __( 'Toutes les annonces', 'rdrnd-plugin' ),
      'view_item'           => __( 'Voir', 'rdrnd-plugin' ),
      'add_new_item'        => __( 'Ajouter une annonce', 'rdrnd-plugin' ),
      'add_new'             => __( 'Ajouter', 'rdrnd-plugin' ),
      'edit_item'           => __( 'Modifier', 'rdrnd-plugin' ),
      'update_item'         => __( 'Mise à jour', 'rdrnd-plugin' ),
      'search_items'        => __( 'Recherche', 'rdrnd-plugin' ),
      'not_found'           => __( 'Introuvable', 'rdrnd-plugin' ),
      'not_found_in_trash'  => __( 'Pas dans la corbeille', 'rdrnd-plugin' )
    );

//    $args = array(
//      'label'               => __( 'Annonces', 'rdrnd-plugin' ),
//      'description'         => __( 'Annonces', 'rdrnd-plugin' ),
//      'labels'              => $labels,
//      'supports'            => array( 'title' ),
//      'taxonomies'          => array(),
//      'hierarchical'        => false,
//      'public'              => true,
//      'show_ui'             => true,
//      'show_in_menu'        => true,
//      'show_in_nav_menus'   => true,
//      'show_in_rest' => true, // Important !
//      'show_in_admin_bar'   => true,
//      'menu_position'       => 5,
//      'menu_icon'           => 'dashicons-admin-multisite',
//      'can_export'          => true,
//      'has_archive'         => true,
//      'exclude_from_search' => false,
//      'publicly_queryable'  => true,
//      'capability_type'     => 'post',
//      'rewrite' => array('slug' => 'annonce', "with_front" => false),
//    );

//    $args = array(
//      'labels' => $labels,
//      'public' => true,
//      'has_archive' => "annonce",
//      'supports' => array('title', 'editor','thumbnail'),
//      'menu_position' => 20,
//      'rewrite' => array('slug' => 'annonce-coucou', 'with_front' => false),
//      'menu_icon' => 'dashicons-video-alt', // https://developer.wordpress.org/resource/dashicons/
//    );
//
//    register_post_type( 'rdr_annnonce', $args );

    // Post Type
//    $labels = array(
//      'name' => 'Annonces',
//      'all_items' => 'Tous les portraits',
//      'singular_name' => 'Annonce',
//      'add_new_item' => 'Ajouter un portrait',
//      'edit_item' => 'Modifier le portrait',
//      'menu_name' => 'Annonces'
//    );

    $args = array(
      'labels' => $labels,
      'public' => true,
      'has_archive' => true,
      'show_in_rest' => true, // Important !
      'supports' => array('title', 'editor','thumbnail'),
      'menu_position' => 20,
      'rewrite' => array('slug' => 'annonce', 'with_front' => false),
      'menu_icon' => 'dashicons-businesswoman', // https://developer.wordpress.org/resource/dashicons/
    );

    register_post_type('rdr_annnonce',$args);

    $labels = array(
      'name' => 'Equipe',
      'all_items' => 'Tous les membres',
      'singular_name' => 'Membre',
      'add_new_item' => 'Ajouter un membre',
      'edit_item' => 'Modifier le membre',
      'menu_name' => 'Equipe'
    );

    $args = array(
      'labels' => $labels,
      'public' => true,
      'has_archive' => false,
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'supports' => array('title', 'editor','thumbnail'),
      'menu_position' => 20,
      'rewrite' => array('slug' => 'tutoriels', 'with_front' => false),
      'menu_icon' => 'dashicons-admin-users', // https://developer.wordpress.org/resource/dashicons/
    );

    register_post_type('equipe',$args);

    // Taxonomy

    /*
     *  Categories Tutoriels
     */
//    $labels = array(
//        'name'              => _x( 'Catégories', 'taxonomy general name', 'textdomain' ),
//        'singular_name'     => _x( 'Catégorie', 'taxonomy singular name', 'textdomain' ),
//        'search_items'      => __( 'Rechercher une catégorie', 'textdomain' ),
//        'all_items'         => __( 'Toutes les catégories', 'textdomain' ),
//        'parent_item'       => __( 'Catégorie parente', 'textdomain' ),
//        'parent_item_colon' => __( 'Catégorie parente:', 'textdomain' ),
//        'edit_item'         => __( 'Modifier la catégorie', 'textdomain' ),
//        'update_item'       => __( 'Mettre à jour la catégorie', 'textdomain' ),
//        'add_new_item'      => __( 'Ajouter une catégorie', 'textdomain' ),
//        'new_item_name'     => __( 'Nouveau nom de catégorie', 'textdomain' ),
//        'menu_name'         => __( 'Catégories', 'textdomain' ),
//    );
//
//    $args = array(
//        'hierarchical'      => true,
//        'labels'            => $labels,
//        'show_ui'           => true,
//        'show_admin_column' => true,
//        'query_var'         => true,
//        'public'            => false,
//        'rewrite'           => array( 'slug' => 'categorie' ),
//    );
//
//    register_taxonomy( 'categorie', array( 'tutoriel' ), $args );

  }

  function cpt_add_role_caps()
  {
      // Add the roles you'd like to administer the custom post types
      $roles = array('editor', 'administrator');

      // Loop through each role and assign capabilities
      foreach ($roles as $the_role) {

          $role = get_role($the_role);

          $role->add_cap('read');

          // Tutoriels caps
          $role->add_cap('read_tutoriels');
          $role->add_cap('read_private_tutoriels');
          $role->add_cap('edit_tutoriels');
          $role->add_cap('edit_tutoriels');
          $role->add_cap('edit_others_tutoriels');
          $role->add_cap('edit_published_tutoriels');
          $role->add_cap('publish_tutoriels');
          $role->add_cap('delete_others_tutoriels');
          $role->add_cap('delete_private_tutoriels');
          $role->add_cap('delete_published_tutoriels');
      }
  }

}
