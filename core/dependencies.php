<?php

namespace DaudinTheme\Core;

class Dependencies {

  public $tgma;

  public function execute() {

    // TGM Plugin Activation
    // From http://tgmpluginactivation.com/
    include get_template_directory().'/core/lib/tgm-plugin-activation.php';
    $this->tgmpa = new \TGM_Plugin_Activation();


    add_action('tgmpa_register', array($this, 'delipress_register_required_plugins'));
  }


  public function delipress_register_required_plugins() {

  	$plugins = array(

  		array(
  			'name'      => 'Yoast SEO',
  			'slug'      => 'wpseo',
        'required'  => true,
  		),

  		array(
  			'name'      => 'BuddyPress',
  			'slug'      => 'buddypress',
        'required'  => true,
  		),
  	);

  	$config = array(
  		'id'           => 'delipress',
  		'default_path' => '',
  		'menu'         => 'tgmpa-install-plugins',
  		'parent_slug'  => 'themes.php',
  		'capability'   => 'edit_theme_options',
  		'has_notices'  => true,
  		'dismissable'  => true,
  		'dismiss_msg'  => '',
  		'is_automatic' => true,
  		'message'      => '',
  	);

  	tgmpa($plugins, $config);
  }

}
