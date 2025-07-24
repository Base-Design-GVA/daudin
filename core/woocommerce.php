<?php

namespace DaudinTheme\Core;

use Timber;
use TimberPost;
use Timber\Site;

class WoocommerceConfig extends Site {
	
	public function execute() {
		
		if ( defined( 'WC_PLUGIN_FILE' ) ) {
			$this->alter_woocommerce();
		}
		
	}
	
	private function alter_woocommerce() {
		
		// add_filter( 'loop_shop_per_page', array($this, 'new_loop_shop_per_page'), 90, 1 );
		// add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		// add_filter( 'woocommerce_pagination_args', array($this, 'woocommerce_pagination_args'), 90, 1 );
		// add_filter( 'woocommerce_get_catalog_ordering_args', array($this, 'default_catalog_ordering_args'), 90 );
		// add_filter( 'pre_get_posts', array($this, 'woocommerce_pre_get_posts'), 90, 1 );
		
		add_filter('timber/twig', array($this, 'add_to_twig'));
		
	}
	
	public function add_to_twig( $args ) {
		$twig->addFunction(new \Twig_Function('get_product_data_as_post', array($this, 'get_product_data_as_post')));
		$twig->addFunction(new \Twig_Function('get_product_data_as_product', array($this, 'get_product_data_as_product')));
		$twig->addFunction(new \Twig_Function('build_product_image', array($this, 'build_product_image')));
	}
	
	public function get_product_data_as_post( $product_id ) {
		return get_post( $product_id );
	}
	
	public function get_product_data_as_product( $product_id ) {
		return wc_get_product( $product_id );
	}
	
	public function build_product_image( $product_id, $specific_class = '' ) {
		return '<img src="'.get_the_post_thumbnail_url( $product_id ).'" class="'. $specific_class .'" />';
	}
	
	public function default_catalog_ordering_args( $args ) {
		$args['orderby'] = 'date';
		$args['order'] = 'asc'; 
		return $args;
	}
	
	public function woocommerce_pagination_args( $args ) {
		
		$args['prev_text']  = '<i class="icon-left-open"></i>';
		$args['next_text']  = '<i class="icon-right-open"></i>';
		$args['end_size']   = '1';
		$args['mid_size']   = '1';
		
		return $args;
		
	}
	
	public function new_loop_shop_per_page( $cols ) {
		$cols = get_option("posts_per_page");
		return $cols;
	}
	
	public function woocommerce_pre_get_posts( $query ) {
		
		// alter pre_get_posts for woocommerce
		
	}
	
	
}
