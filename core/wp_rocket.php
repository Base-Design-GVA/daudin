<?php

namespace DaudinTheme\Core;
use Timber\Site;

class WPRocketPurgeAll extends Site {

	public function execute() {
        if( defined('WP_ROCKET_VERSION')) {
        	$this->register_hooks();
		}
    }

    private function register_hooks() {
		// Define registered purge events
		$actions = array(
			'save_post',            // Save a post
			'deleted_post',         // Delete a post
			'trashed_post',         // Empty Trashed post
			'edit_post',            // Edit a post - includes leaving comments
			'delete_attachment',    // Delete an attachment - includes re-uploading
			'switch_theme',         // Change theme
			'edit_terms',           // Edit terms
			'deleted_term_taxonomy',// Deleted term
			'delete_term',          // Delete term
		);

		// Add the action for each event
		foreach ( $actions as $event ) {
			add_action( $event, array($this, 'purge_all'), 10 );
		}
	}

	public function purge_all() {

		if ( !defined('WP_ROCKET_VERSION') ) {
			return false;
		}

        rocket_clean_minify();
		return true;
	}

}