<?php

namespace DaudinTheme\Core;

class Ajax {

  public function execute() {
    $this->register_hooks();
  }

  protected function register_hooks() {
    // add_action('wp_ajax_daudin_theme_search', array($this, 'search'));
    // add_action('wp_ajax_nopriv_daudin_theme_search', array($this, 'search'));

    add_action( 'wp_ajax_nopriv_load_gravity_form', array($this, 'load_gravity_form') );
    add_action( 'wp_ajax_load_gravity_form', array($this, 'load_gravity_form') );
  }

  public function search() {
    $keyword = $_POST['keyword'];

    $args = array(
      's' => $keyword
    );

    $ajax_query = new WP_Query($args);

    if($ajax_query->have_posts()) : while($ajax_query->have_posts()) : $ajax_query->the_post();
      get_template_part('article');
    endwhile;
    endif;

    die();
  }

  public function load_gravity_form() {
    if( isset($_POST['form_id']) && !empty($_POST['form_id']) ) {
      echo str_replace("/wp-admin/admin-ajax.php", "", gravity_form($_POST['form_id'], false, false, false, '', true, 12, false));
    }
    die();
  }

}

/*
  Put in Front :

  $('body').on('change', '#s', function() {
    var keyword = $(this).val();

    jQuery.post(
      ajaxurl,
      {
          'action': 'Daudin_theme_search',
          'keyword': keyword
      },
      function(response){
          $('.somewhere').html(response);
      }
    );
  });
*/
