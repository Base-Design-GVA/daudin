<?php

namespace DaudinTheme\Core;

class Config
{

    public function execute()
    {
        $this->register_hooks();
        $this->clean_wp();
    }

    public function register_hooks()
    {

        if (defined('MAINTENANCE') and MAINTENANCE) {
            add_action('get_header', array($this, 'activate_maintenance'));
        }

        // Public Hooks

        add_action('after_setup_theme', array($this, 'theme_setup'));
        add_action('wp_enqueue_scripts', array($this, 'register_assets'));
        add_action('init', array($this, 'change_author_permalinks'));
        add_action('after_setup_theme', array($this, 'theme_add_woocommerce_support'));

        add_filter("gform_init_scripts_footer", array($this, "gform_init_scripts"));

        //add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
        //add_filter( 'excerpt_length', array( $this, 'set_excerpt_length' ) );
        //add_filter( 'excerpt_more', array( $this, 'set_excerpt_suffixe' ) );
        //add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_assets'), 1000 );


        // Admin Hooks

        add_action('admin_menu', array($this, 'remove_meta_boxes'));
        add_filter('sanitize_file_name', 'remove_accents');
        //add_action( 'admin_menu', array( $this, 'remove_menu_pages' ) );
        add_filter('upload_mimes', array($this, 'allow_mime_types'));
        add_action('admin_enqueue_scripts', array($this, 'admin_theme_style'));

        // add_action('init', array($this, 'wp_block_dashboard'));

    }

    /* Bloquer accès aux non-admins */
    public function wp_block_dashboard()
    {
        $file = basename($_SERVER['PHP_SELF']);
        if (!current_user_can('administrator') && !current_user_can('editor') && !current_user_can('contributor') && !current_user_can('author') && is_admin() && $file != 'admin-ajax.php') {
            wp_redirect(home_url());
            exit();
        }
    }

    public function gform_init_scripts()
    {
        return true;
    }


    public function clean_wp()
    {

        // Remove XML RPC
        add_filter('xmlrpc_enabled', '__return_false');

        // Welcome panel
        remove_action('welcome_panel', 'wp_welcome_panel');

        // Head useless stuff
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'start_post_rel_link', 10, 0);
        remove_action('wp_head', 'parent_post_rel_link', 10, 0);
        remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

        // Remove Emojis
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
    }


    /*  ===============  */
    /*  = Main config =  */
    /*  ===============  */

    public function theme_setup()
    {

        // Text Domain
        load_theme_textdomain('daudin_theme', get_template_directory() . '/languages');

        //  Thumbnails
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(800, 600, false);
        add_image_size('fullwidth', 1920, 0, false);

        //  Page Title
        add_theme_support('title-tag');

        // Menus
        register_nav_menus(array(
            'main' => 'Menu Principal',
            'footer' => 'Pied de page',
        ));

        // Editor custom styles
        add_theme_support('editor-styles');
        add_editor_style(array('css/editor-style.css'));

        // Enable HTML5
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
        ));

        // RSS
        add_theme_support('automatic-feed-links');

        // Gutenberg - Wide blocks
        add_theme_support('align-wide');

        // Remove admin topbar (and html margin-top 32px)
        add_theme_support('admin-bar', array('callback' => '__return_false'));

    }


    public function register_assets()
    {

        wp_deregister_script('jquery');

        wp_enqueue_script('jquery', get_template_directory_uri() . '/js/lib/jquery-3.6.0.min.js', false, '3.3.1', false);
        // wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick/slick.min.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_script( 'jquery_slider', get_template_directory_uri() . '/js/jquery-ui.min.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_script( 'jquery_touch_punch', get_template_directory_uri() . '/js/jquery.touch.punch.min.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_script('barba', get_template_directory_uri() . '/js/lib/barba.umd.js', array('jquery'), '1.0', true);
        wp_enqueue_script('gsap-scroll-trigger', get_template_directory_uri() . '/js/lib/ScrollTrigger.min.js', array('jquery'), '1.0', true);
        
        wp_enqueue_script('gsap', get_template_directory_uri() . '/js/lib/gsap.js', array('jquery'), '1.0', true);
        wp_enqueue_script('gsap-scroll-to', get_template_directory_uri() . '/js/lib/ScrollToPlugin.js', array('jquery'), '1.0', true);
        
        wp_enqueue_script('aos', get_template_directory_uri() . '/js/lib/aos.js', array('jquery'), '1.0', true);
        // wp_enqueue_script( 'isotope', get_template_directory_uri() . '/js/lib/isotope.pkgd.min.js', array('jquery'), '1.0', true );
        wp_enqueue_script('swiper', get_template_directory_uri() . '/js/lib/swiper-bundle.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('cookie-js', get_template_directory_uri() . '/js/lib/cookie.min.js', array('jquery'), '1.0', true);
        //	wp_enqueue_script( 'list', get_template_directory_uri() . '/js/lib/list.min.js', array('jquery'), '1.0', true );
        wp_enqueue_script('google_js', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=' . get_field('acf_api_key', 'option'), '', '');
        wp_enqueue_script('daudin', get_template_directory_uri() . '/js/script.js', array('jquery'), '1.0', true);


        wp_enqueue_style('fontello', get_template_directory_uri() . '/fonts/fontello/css/fontello.css', array(), '1.0');
        // wp_enqueue_style( 'slick', get_template_directory_uri() . '/js/slick/slick.css', array(), '1.0' );
        // wp_enqueue_style( 'jquery_slider', get_template_directory_uri() . '/css/jquery-ui.min.css', array(), '1.0' );
        wp_enqueue_style('aos', get_template_directory_uri() . '/css/aos.css', array(), '1.0');
        wp_enqueue_style('swiper', get_template_directory_uri() . '/css/swiper-bundle.css', array(), '1.0');
        wp_enqueue_style('daudin', get_template_directory_uri() . '/css/main.css', array(), '1.0');

        wp_localize_script('daudin', 'ajaxurl', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));


        $the_id = 0;

        // gestion du custom_css propre (via fichier enqueue)
        $current_object = get_queried_object();
        if (isset($current_object->ID)) {
            $the_id = $current_object->ID;
        } else if (isset($current_object->term_id)) {
            $the_id = 'term_' . get_queried_object()->term_id;
        }

        if (!empty($the_id) && !file_exists(get_template_directory() . '/css/cache/css_' . $the_id . '.css')) {
            // file_put_contents( get_template_directory() . '/css/cache/css_'.$the_id.'.css', '');
        }

        if (!empty($the_id) && file_exists(get_template_directory_uri() . '/css/cache/css_' . $the_id . '.css')) {
            wp_enqueue_style('daudin_' . $the_id, get_template_directory_uri() . '/css/cache/css_' . $the_id . '.css', array(), '1.0');
        }

    }

    public function change_author_permalinks()
    {
        global $wp_rewrite;
        $wp_rewrite->author_base = __('auteur', 'daudin_theme');
    }

    public function register_sidebars()
    {
        register_sidebar(array(
            'name' => 'Blog',
            'before_widget' => '<div class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<p>',
            'after_title' => '</p>'
        ));
    }

    public function set_excerpt_length($length)
    {
        return 20;
    }

    public function set_excerpt_suffixe($more)
    {
        return '…';
    }

    public function dequeue_assets()
    {

        // Remove Gutenberg frontend styles
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');

        // remove WooCommerce stylesheet
        wp_dequeue_style('wc-block-style');
    }

    //Woocommerce compatible
    public function theme_add_woocommerce_support()
    {
        add_theme_support('woocommerce');
    }



    /*  ===================  */
    /*  = Admin Functions =  */
    /*  ===================  */

    public function remove_meta_boxes()
    {
        remove_meta_box('dashboard_primary', 'dashboard', 'normal'); // WP News
    }

    public function remove_menu_pages()
    {
        $current_user = wp_get_current_user();

        if ($current_user->ID != 1) {

            remove_menu_page('tools.php');
            remove_menu_page('edit-comments.php');

            remove_submenu_page('themes.php', 'widgets.php');
            remove_submenu_page('themes.php', 'theme-editor.php');

            remove_menu_page('users.php');

            remove_menu_page('wpcf7'); // Contact form 7
            remove_menu_page('gf_edit_forms'); // gravity forms
            remove_menu_page('wpseo_dashboard'); // SEO by Yoast

            remove_menu_page('edit.php?post_type=acf'); // Advanced Custom Fields
        }
    }

    public function allow_mime_types($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    public function admin_theme_style()
    {
        wp_enqueue_style('custom-admin', get_template_directory_uri() . '/css/admin.css');
    }

    /*  ====================  */
    /*  = Global Functions =  */
    /*  ====================  */

    public function activate_maintenance()
    {
        if (!current_user_can('edit_themes') || !is_user_logged_in()) {
            wp_die('Site en maintenance.');
        }
    }
}
