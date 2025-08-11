<?php

namespace DaudinTheme;

use DaudinTheme\Core\GutenbergBlock;
use DaudinTheme\Core\Login;
use DaudinTheme\Core\Config;
use DaudinTheme\Core\CPT;
use DaudinTheme\Core\Features;
use DaudinTheme\Core\ACF;
use DaudinTheme\Core\Ajax;
use DaudinTheme\Core\Plugins;
use DaudinTheme\Core\API;
use DaudinTheme\Core\Timber;
use DaudinTheme\Core\Gutemberg;
use DaudinTheme\Core\TimberConfig;
use DaudinTheme\Core\WoocommerceConfig;
use DaudinTheme\Core\WPRocketPurgeAll;

require_once(__DIR__ . '/vendor/autoload.php');
$timber = new \Timber\Timber();

class DaudinTheme {

  public function run() {

    include get_template_directory() . '/core/config.php';
    include get_template_directory() . '/core/cpt.php';
    include get_template_directory() . '/core/features.php';
    include get_template_directory() . '/core/acf.php';
    include get_template_directory() . '/core/ajax.php';
    include get_template_directory() . '/core/plugins.php';
    include get_template_directory() . '/core/api.php';
    include get_template_directory() . '/core/timber.php';
    include get_template_directory() . '/core/woocommerce.php';
    include get_template_directory() . '/core/gutenberg.php';
    include get_template_directory() . '/core/wp_rocket.php';
    include get_template_directory() . '/api/immomig-contact.php';

    ( new Config )->execute();
    ( new CPT )->execute();
    ( new Features )->execute();
    ( new ACF )->execute();
    ( new Ajax )->execute();
    ( new Plugins )->execute();
    ( new API )->execute();
    ( new TimberConfig )->execute();
    ( new WoocommerceConfig )->execute();
    ( new WPRocketPurgeAll )->execute();
    ( new GutenbergBlock )->execute();

  }
}

( new DaudinTheme )->run();