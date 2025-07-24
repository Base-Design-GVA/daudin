<?php

namespace DaudinTheme\Core;

use DOMDocument;
use DOMXPath;
use Timber\ImageHelper;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TimberConfig extends \TimberSite
{   
 

    public function execute()
    {
        $this->register_hooks();

        // Define Twig directories
        \Timber::$dirname = array('views', 'views/parts', 'views/ajax');
    }

    private function register_hooks()
    {
        add_filter('timber/context', array($this, 'add_to_context'));
        add_filter('timber/twig', array($this, 'add_to_twig'));


    }

    // Global context, available to all templates
    function add_to_context($context)
    {

        // WP Templates
        $context['wp']['template'] = array(
            'front_page' => is_front_page(),
            'blog' => is_home(),
        );

        // Menus
        $context['wp']['menus'] = array(
            "main" => new \Timber\Menu('main'),
            "footer" => new \Timber\Menu('footer'),
        );

        if (is_user_logged_in()) {
            $context['signin'] = true;
        }

        // Store field values fron option pages
        $acf_conf_header = array();
        //  example : 	$acf_conf_header['acf_conf_header_logo']			= get_field('acf_conf_header_logo', 'option');
        $acf_conf_header['acf_conf_header_title_name'] = get_field('acf_site_name', 'option');
        $acf_conf_header['acf_conf_header_principal_content'] = get_field('acf_header_principal', 'option');
        $acf_conf_header['acf_cursor_color'] = get_field('acf_cursor_color', 'option');
        $acf_conf_header['acf_click_cursor_color'] = get_field('acf_click_cursor_color', 'option');
        $acf_conf_header['acf_cursor_opacity'] = get_field('acf_cursor_opacity', 'option');
        $acf_conf_header['acf_open_on_load'] = get_field('acf_open_on_load', 'option');
        $acf_conf_header['acf_actus'] = get_field('news', 'option');

        $acf_conf_footer = array();
        $acf_conf_footer['acf_conf_menu_fs'] = get_field('acf_full_size_menu', 'option');
        $acf_conf_footer['acf_fixed_footer'] = get_field('acf_fixed_footer', 'option');
        $acf_conf_footer['acf_page_footer'] = get_field('acf_page_footer', 'option');

        $acf_conf_annonce = array();
        $acf_conf_annonce['acf_unit'] = get_field('acf_unit', 'option');
        $acf_conf_annonce['acf_texte_location'] = get_field('acf_texte_location', 'option');
        $acf_conf_annonce['acf_accent_color'] = get_field('acf_accent_color', 'option');
        $acf_conf_annonce['acf_label_plan_button'] = get_field('acf_label_plan_button', 'option');
        $acf_conf_annonce['acf_label_fiche_inscription'] = get_field('acf_label_fiche_inscription', 'option');
        $acf_conf_annonce['acf_label_demande_informations'] = get_field('acf_label_demande_informations', 'option');
        $acf_conf_annonce['acf_label_imprimer'] = get_field('acf_label_imprimer', 'option');
        $acf_conf_annonce['titre_avantage_du_bien'] = get_field('titre_avantage_du_bien', 'option');
        $acf_conf_annonce['acf_api_key'] = get_field('acf_api_key', 'option');
        $acf_conf_annonce['acf_retour_link'] = get_field('acf_retour_link', 'option');
        $acf_conf_annonce['acf_intitules'] = get_field('acf_intitules', 'option');
        $acf_conf_annonce['acf_surface_unit'] = get_field('acf_surface_unit', 'option');
        $acf_conf_annonce['acf_year_label'] = get_field('acf_year_label', 'option');
        $acf_conf_annonce['acf_suffix'] = get_field('acf_suffix', 'option');
        $acf_conf_annonce['acf_down_contact_title'] = get_field('acf_down_contact_title', 'option');
        $acf_conf_annonce['acf_down_quartier_title'] = get_field('acf_down_quartier_title', 'option');
        $acf_conf_annonce['acf_label_quartier_button'] = get_field('acf_label_quartier_button', 'option');
        $acf_conf_annonce['acf_undefined_price_label'] = get_field('acf_undefined_price_label', 'option');
        $acf_conf_annonce['acf_back_label'] = get_field('acf_back_label', 'option');
        $acf_conf_annonce['acf_map_style'] = get_field('acf_map_style', 'option');

        $acf_conf_annonce['acf_fiche_inscrip_agri']         = get_field('acf_fiche_inscrip_agri', 'option');
        $acf_conf_annonce['acf_fiche_inscrip_appt']         = get_field('acf_fiche_inscrip_appt', 'option');
        $acf_conf_annonce['acf_fiche_inscrip_garden']       = get_field('acf_fiche_inscrip_garden', 'option');
        $acf_conf_annonce['acf_fiche_inscrip_gastro']       = get_field('acf_fiche_inscrip_gastro', 'option');
        $acf_conf_annonce['acf_fiche_inscrip_house']        = get_field('acf_fiche_inscrip_house', 'option');
        $acf_conf_annonce['acf_fiche_inscrip_indus']        = get_field('acf_fiche_inscrip_indus', 'option');
        $acf_conf_annonce['acf_fiche_inscrip_park']         = get_field('acf_fiche_inscrip_park', 'option');
        $acf_conf_annonce['acf_fiche_inscrip_prop']         = get_field('acf_fiche_inscrip_prop', 'option');
        $acf_conf_annonce['acf_fiche_inscrip_secondary']    = get_field('acf_fiche_inscrip_secondary', 'option');

        $current_object = get_queried_object();

        $context['fields_header'] = $acf_conf_header;
        $context['fields_footer'] = $acf_conf_footer;
        $context['fields_annonce'] = $acf_conf_annonce;

        if (defined('ICL_LANGUAGE_CODE')) {
            $context['wpml_current_lang'] = ICL_LANGUAGE_CODE;
        }

        return $context;
    }

    // Improve Twig
    public function add_to_twig($twig)
    {
        $twig->addFilter(new TwigFilter('output_svg', array($this, 'output_svg')));;
        $twig->addFunction(new TwigFunction('debug', array($this, 'debug')));
        $twig->addFunction(new TwigFunction('get_etage_correspondance', array($this, 'get_etage_correspondance')));
        $twig->addFunction(new TwigFunction('clean_cut', array($this, 'clean_cut')));
        $twig->addFunction(new TwigFunction('sanitize_string', array($this, 'sanitize_string')));
        $twig->addFunction(new TwigFunction('build_acf_image', array($this, 'build_acf_image')));
        $twig->addFunction(new TwigFunction('build_acf_link', array($this, 'build_acf_link')));
        $twig->addFunction(new TwigFunction('build_custom_css', array($this, 'build_custom_css')));
        $twig->addFunction(new TwigFunction('build_theme_image', array($this, 'build_theme_image')));
        $twig->addFunction(new TwigFunction('load_nav_menu', array($this, 'load_nav_menu')));
        $twig->addFunction(new TwigFunction('breadcrumb', array($this, 'breadcrumb')));
        $twig->addFunction(new TwigFunction('wp_paginate', array($this, 'paginate')));
        $twig->addFunction(new TwigFunction('set_flexgrid', array($this, 'set_flexgrid')));
        $twig->addFunction(new TwigFunction('format_title', array($this, 'format_title')));
        $twig->addFunction(new TwigFunction('get_array_photos_annonce_from_string', array($this, 'get_array_photos_annonce_from_string')));
        $twig->addFunction(new TwigFunction('post_minutes', array($this, 'post_minutes')));
        $twig->addFunction(new TwigFunction('get_gravity_form', array($this, 'get_gravity_form')));
        $twig->addFunction(new TwigFunction('get_html_section_progression', array($this, 'get_html_section_progression')));
        $twig->addGlobal('custom_css', '');

        return $twig;
    }

    // SVG embedder Twig filter
    public function output_svg($svg_url)
    {
        return file_get_contents($svg_url);
    }

    public function debug($variable, $mode = false)
    {
        echo '<pre style="text-align: left; font-family: inherit">';
        if ($mode === true) {
            var_dump($variable);
        } else {
            print_r($variable);
        }
        echo '</pre>';
    }

    public function clean_cut($string, $length, $cutString = '...')
    {
        if (strlen($string) <= $length) {
            return $string;
        }
        $str = substr($string, 0, $length - strlen($cutString) + 1);
        return substr($str, 0, strrpos($str, ' ')) . $cutString;
    }

    public function sanitize_string($string)
    {
        // Replaces all spaces with hyphens.
        $string = str_replace(' ', '-', $string);
        // Removes special chars.
        $sanitazed_string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        return strtolower($sanitazed_string);
    }

    public function breadcrumb()
    {
        return bcn_display(true);
    }

    public function paginate()
    {
        if (is_archive() || is_home() || is_search()) {
            if (function_exists('wp_paginate')) {
                return wp_paginate();
            }
        }
    }

    public function build_custom_css($css)
    {

        $the_id = 0;

        if (is_admin()) {

            global $post;
            $the_id = $post->ID;

        } else {
            $current_object = get_queried_object();

            if (isset($current_object->ID)) {
                $the_id = $current_object->ID;
            } else if (isset($current_object->term_id)) {
                $the_id = get_queried_object()->term_id;
            }
        }

        if (!file_exists(get_template_directory() . '/css/cache/css_' . $the_id . '.css')) {
            touch(get_template_directory() . '/css/cache/css_' . $the_id . '.css');
        }

        // gestion du reset fichier (10 secondes)
        if (time() - filemtime(get_template_directory() . '/css/cache/css_' . $the_id . '.css') > 10) {
            file_put_contents(get_template_directory() . '/css/cache/css_' . $the_id . '.css', '', LOCK_EX);
        }

        if (!empty($css) && !empty($the_id)) {
            if (file_exists(get_template_directory() . '/css/cache/css_' . $the_id . '.css')) {
                $current_css = file_get_contents(get_template_directory() . '/css/cache/css_' . $the_id . '.css');
            } else {
                $current_css = '';
                file_put_contents(get_template_directory() . '/css/cache/css_' . $the_id . '.css', $css, FILE_APPEND | LOCK_EX);
            }
            if ($current_css != $css) {
                file_put_contents(get_template_directory() . '/css/cache/css_' . $the_id . '.css', $css, FILE_APPEND | LOCK_EX);
            }
        }

    }

    public function build_theme_image($image)
    {
        if (file_exists(ABSPATH . $image)) {
            if (substr($image, -4) == '.svg') {
                return file_get_contents(ABSPATH . $image);
            } else {
                return '<img src="' . $image . '" />';
            }
        }
    }

    public function build_acf_image($acf_image, $mode = 'full', $w = '', $h = '', $specific_class = '', $url_only = false)
    {
        /**
         * $mode : '', full, thumbnail, medium, large
         **/

        $from_numeric = false;

        // si mode INT, on r�cup�re l'attachement
        if (is_numeric($acf_image)) {
            $from_numeric = true;
            $current_image = wp_get_attachment_metadata($acf_image);
            $current_image['mime_type'] = $current_image['sizes']['thumbnail']["mime-type"];
            $current_image['url'] = wp_get_attachment_url($acf_image);
            $current_image['alt'] = get_post_meta($acf_image, '_wp_attachment_image_alt', true);
            $acf_image = $current_image;
        }
        if (!is_array($acf_image)) {
            return;
        }
        if ($acf_image['mime_type'] == 'image/svg+xml') {
            return file_get_contents(ABSPATH . (str_replace(get_option('siteurl') . '/', '', $acf_image['url'])));
        } else {
            $url = $acf_image['url'];
            $alt = $acf_image['alt'];
            $width = $acf_image['width'];
            $height = $acf_image['height'];

            if ($mode == 'custom' && !empty($w) && is_numeric($w)) {
                // calcul auto de la hauteur si vide
                if (empty($h)) {
                    $h = round($height / ($width / $w));
                }
                if ($url_only === true) {
                    return ImageHelper::resize($url, $w, $h);
                }
                return '<img src="' . ImageHelper::resize($url, $w, $h) . '" class="' . $specific_class . '" alt="' . $alt . '" width="' . $w . '" ' . (!empty($h) && is_numeric($h) ? 'height="' . $h . '"' : '') . ' />';
            } else if ($mode == 'custom' && !empty($h) && is_numeric($h)) {
                // calcul auto de la largeur si vide
                if (empty($w)) {
                    $w = round($width / ($height / $h));
                }
                if ($url_only === true) {
                    return ImageHelper::resize($url, $w, $h);
                }
                return '<img src="' . ImageHelper::resize($url, $w, $h) . '" class="' . $specific_class . '" alt="' . $alt . '" width="' . $w . '" ' . (!empty($h) && is_numeric($h) ? 'height="' . $h . '"' : '') . ' />';
            } else if ($mode !== 'full' && isset($acf_image['sizes'][$mode])) {
                if ($from_numeric) {
                    $url = '/wp-content/uploads/' . $acf_image['sizes'][$mode]['file'];
                    $width = $acf_image['sizes'][$mode]['width'];
                    $height = $acf_image['sizes'][$mode]['height'];
                } else {
                    $url = $acf_image['sizes'][$mode];
                    $width = $acf_image['sizes'][$mode . '-width'];
                    $height = $acf_image['sizes'][$mode . '-height'];
                }
            }
            if ($url_only === true) {
                return $url;
            }
            return '<img src="' . $url . '" class="' . $specific_class . '" alt="' . $alt . '" width="' . $width . '" height="' . $height . '" />';
        }
    }

    public function build_acf_link($acf_link, $specific_text = '', $specific_class = '', $icon = '', $image = '', $reverse = false)
    {
        if (!empty($acf_link) && is_array($acf_link) && isset($acf_link['url']) && !empty($acf_link['url'])) {
            return '<a href="' . $acf_link['url'] . '" title="' . $acf_link['title'] . '" target="' . $acf_link['target'] . '" ' .
                (!empty($specific_class) ? 'class="' . trim(stripslashes(strip_tags($specific_class))) . '"' : '') . '>' .
                (!empty($icon) && $reverse === true ? '<i class="' . $icon . '"></i>' : '') .
                (!empty($image) && $reverse === true ? $image : '') .
                '<span>' . (!empty($specific_text) ? $specific_text : $acf_link['title']) . '</span>' .
                (!empty($icon) && $reverse === false ? '<i class="' . $icon . '"></i>' : '') .
                (!empty($image) && $reverse === false ? $image : '') . '</a>';
        }

        return '';
    }

    public function load_nav_menu($menu, $class = '')
    {
        $container_class = $class . "_container";
        return wp_nav_menu(array('menu' => $menu, 'menu_class' => $class, 'container_class' => $container_class, 'after' => '<span class="toggle"></span>'));
    }

    public function set_flexgrid($xs = '', $sm = '', $md = '', $lg = '')
    {
        $class = "";
        if (!empty($xs)) $class .= "col-xs-" . $xs;
        if (!empty($sm)) $class .= " col-sm-" . $sm;
        if (!empty($md)) $class .= " col-md-" . $md;
        if (!empty($lg)) $class .= " col-lg-" . $lg;
        return $class;
    }

    public function format_title($titre, $additional_class = '')
    {

        if (isset($titre['type_de_titre'])) {
            if (strlen($titre['type_de_titre']) == 2) {
                return '<' . $titre['type_de_titre'] . ' class="' . $additional_class . '">' . nl2br(strip_tags($titre['label'])) . '</' . $titre['type_de_titre'] . '>';
            } else {
                return '<p class="' . $titre['type_de_titre'] . ' ' . $additional_class . '">' . nl2br(strip_tags($titre['label'])) . '</p>';
            }
        } else {
            return '<p class="' . $additional_class . '">' . nl2br(strip_tags($titre)) . '</p>';
        }

    }

    public function post_minutes($post_id)
    {

        $content = get_post_field('post_content', $post_id);
        $nb_minutes = 250;
        $minutes = ceil(str_word_count($content) / $nb_minutes);
        if ($minutes == 0) {
            $minutes = 1;
        }
        return $minutes;

    }

    public function get_html_section_progression($page_content)
    {
        @$doc = new DOMDocument();
        @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $page_content);

        $xpath = new DomXPath($doc);
        $classname = 'section-progression';

        $nodeList = $xpath->query("//*[contains(@class, '$classname')]");

        $html_result = '';
        for ($i = 0; $i < $nodeList->count(); $i++) {
            $titre = $nodeList->item($i)->getAttribute("id");
            $html_result = $html_result . '<div class="element-progression" data-ancre="' . $titre . '"><a href="#' . $titre . '">' . str_replace("-", " ", $titre) . '</a></div>';
        }


        return $html_result;
    }

    public function get_gravity_form($id = 1)
    {
        gravity_form($id, false, false, false, '', true, 12);
    }

    public function get_array_photos_annonce_from_string($illustrations_url_string, $prefix = "")
    {
        $array = explode("|", $illustrations_url_string);
        //on ajoute le préfixe
        foreach ($array as $key => $url) {
            $pathArray = explode("/", $url);
            $pathArray[count($pathArray) - 1] = $prefix . $pathArray[count($pathArray) - 1];
            $array[$key] = implode("/", $pathArray);
        }

        return $array;
    }

    public function get_etage_correspondance($numero_etage)
    {
        $correspondances = get_field('acf_correspondance_etage', 'option');

        foreach ($correspondances as $correspondance) {
            if (intval($correspondance['numero']) === intval($numero_etage)) {
                return $correspondance['intitule'];

            }
        }

        return $numero_etage;

    }
}

