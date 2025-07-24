<?php

namespace DaudinTheme\Core;

class GutenbergBlock {

	public function execute() {
		$this->register_hooks();
	}

	protected function register_hooks() {
		add_action( 'acf/init', array($this, 'my_acf_init'));
	}

	public function my_acf_init() {

		if ( ! function_exists( 'acf_register_block' ) ) {
			return;
		}

//		// Register a new block.
//		acf_register_block( array(
//			'name'            => 'exemple_bloc',
//			'title'           => 'Bloc exemple',
//			'description'     => 'Bloc exemple',
//			'render_callback' => array($this, 'callback_example_acf_block_render'),
//			'category'        => 'formatting',
//			'icon'            => 'admin-comments',
//			'keywords'        => array('exemple'),
//			) );
//		}
		acf_register_block( array(
			'name'            => 'superposition_bloc',
			'title'           => 'Superposition Image Forme',
			'description'     => 'Superposition d\'une image et d\'une forme',
			'render_callback' => array($this, 'callback_superposition_acf_block_render'),
			'category'        => 'common',
			'mode'            => 'edit',
			'icon'            => 'format-image',
			'keywords'        => array('image', 'forme', 'animation'),
			) );

		acf_register_block( array(
			'name'            => 'brochures_bloc',
			'title'           => 'Brochures',
			'description'     => 'Brochures superposées',
			'render_callback' => array($this, 'callback_brochures_acf_block_render'),
			'category'        => 'common',
			'mode'            => 'edit',
			'icon'            => 'media-document',
			'keywords'        => array('brochures', 'document'),
			) );


		acf_register_block( array(
			'name'            => 'faq_nos_services_bloc',
			'title'           => 'FAQ - Nos services',
			'description'     => 'Bloc accordéon - 2 modes possibles',
			'render_callback' => array($this, 'callback_faq_nos_services_acf_block_render'),
			'category'        => 'common',
			'mode'            => 'edit',
			'icon'            => 'businessman',
			'keywords'        => array('brochures', 'document'),
			) );

    acf_register_block( array(
      'name'            => 'texte_forme_bloc',
      'title'           => 'Texte Forme',
      'description'     => 'Un texte et une forme prenant tout l\'écran avec une animation d\'arrivée',
      'render_callback' => array($this, 'callback_texte_forme_acf_block_render'),
      'category'        => 'common',
      'mode'            => 'edit',
      'icon'            => 'image-filter',
      'keywords'        => array('texte', 'forme'),
    ) );

    acf_register_block( array(
      'name'            => 'nos_equipes_bloc',
      'title'           => 'Nos Equipes / CA',
      'description'     => 'Bloc de présentation des équipes / ou de conseil d\'administration selon le mode',
      'render_callback' => array($this, 'callback_nos_equipes_acf_block_render'),
      'category'        => 'common',
      'mode'            => 'edit',
      'icon'            => 'admin-users',
      'keywords'        => array('equipe', 'ca'),
    ) );

    acf_register_block( array(
      'name'            => 'zoom_sur_bloc',
      'title'           => 'Zoom sur',
      'description'     => 'Bloc de lien vers des pages',
      'render_callback' => array($this, 'callback_zoom_sur_acf_block_render'),
      'category'        => 'common',
      'mode'            => 'edit',
      'icon'            => 'code-standards',
      'keywords'        => array('zoom', 'pages'),
    ) );

    acf_register_block( array(
      'name'            => 'menu_bouton_bloc',
      'title'           => 'Menu Bouton',
      'description'     => 'Affiche un menu sous forme de bouton',
      'render_callback' => array($this, 'callback_menu_bouton_acf_block_render'),
      'category'        => 'common',
      'mode'            => 'edit',
      'icon'            => 'menu-alt3',
      'keywords'        => array('menu', 'bouton'),
    ) );

    acf_register_block( array(
      'name'            => 'bloc_2_columns',
      'title'           => '2 colonnes',
      'description'     => '1 colonne image + 1 colonne texte',
      'render_callback' => array($this, 'callback_two_columns_acf_block_render'),
      'category'        => 'common',
      'mode'            => 'edit',
      'icon'            => 'menu-alt3',
      'keywords'        => array('colonnes', 'image'),
    ) );

	}



		/**
		*  This is the callback that displays the block.
		*
		* @param   array  $block      The block settings and attributes.
		* @param   string $content    The block content (emtpy string).
		* @param   bool   $is_preview True during AJAX preview.
		*/
//		public function callback_example_acf_block_render( $block, $content = '', $is_preview = true ) {
//
//			$context = \Timber\Timber::get_context();
//
//			// Store block values.
//			$context['block'] = $block;
//
//			// Store field values.
//			$context['fields'] = get_fields();
//
//			// Store $is_preview value.
//			$context['is_preview'] = $is_preview;
//
//			// Render the block.
//			\Timber\Timber::render( 'block/example.twig', $context );
//		}

		public function callback_superposition_acf_block_render( $block, $content = '', $is_preview = true ) {

			$context = \Timber\Timber::get_context();

			// Store block values.
			$context['block'] = $block;

			// Store field values.
			$context['fields'] = get_fields();

			// Store $is_preview value.
			$context['is_preview'] = $is_preview;

			// Render the block.
			\Timber\Timber::render( 'block/superposition.twig', $context );
		}

		public function callback_brochures_acf_block_render( $block, $content = '', $is_preview = true ) {

			$context = \Timber\Timber::get_context();

			// Store block values.
			$context['block'] = $block;

			// Store field values.
			$context['fields'] = get_fields();

			// Store $is_preview value.
			$context['is_preview'] = $is_preview;

			// Render the block.
			\Timber\Timber::render( 'block/brochures.twig', $context );
		}

		public function callback_faq_nos_services_acf_block_render( $block, $content = '', $is_preview = true ) {

			$context = \Timber\Timber::get_context();

			// Store block values.
			$context['block'] = $block;

			// Store field values.
			$context['fields'] = get_fields();

			// Store $is_preview value.
			$context['is_preview'] = $is_preview;

			// Render the block.
			\Timber\Timber::render( 'block/faq_nos_services.twig', $context );
		}

		public function callback_texte_forme_acf_block_render( $block, $content = '', $is_preview = true ) {

			$context = \Timber\Timber::get_context();

			// Store block values.
			$context['block'] = $block;

			// Store field values.
			$context['fields'] = get_fields();

			// Store $is_preview value.
			$context['is_preview'] = $is_preview;

			// Render the block.
			\Timber\Timber::render( 'block/texte_forme.twig', $context );
		}

		public function callback_nos_equipes_acf_block_render( $block, $content = '', $is_preview = true ) {

			$context = \Timber\Timber::get_context();

			// Store block values.
			$context['block'] = $block;

			// Store field values.
			$context['fields'] = get_fields();

			// Store $is_preview value.
			$context['is_preview'] = $is_preview;

			// Render the block.
			\Timber\Timber::render( 'block/nos_equipes.twig', $context );
		}

		public function callback_menu_bouton_acf_block_render( $block, $content = '', $is_preview = true ) {

			$context = \Timber\Timber::get_context();

			// Store block values.
			$context['block'] = $block;

			// Store field values.
			$context['fields'] = get_fields();

			// Store $is_preview value.
			$context['is_preview'] = $is_preview;

			// Render the block.
			\Timber\Timber::render( 'block/menu_bouton.twig', $context );
		}

		public function callback_zoom_sur_acf_block_render( $block, $content = '', $is_preview = true ) {

			$context = \Timber\Timber::get_context();

			// Store block values.
			$context['block'] = $block;

			// Store field values.
			$context['fields'] = get_fields();

			// Store $is_preview value.
			$context['is_preview'] = $is_preview;

			// Render the block.
			\Timber\Timber::render( 'block/zoom_sur.twig', $context );
		}

		public function callback_two_columns_acf_block_render( $block, $content = '', $is_preview = true ) {

			$context = \Timber\Timber::get_context();

			// Store block values.
			$context['block'] = $block;

			// Store field values.
			$context['fields'] = get_fields();

			// Store $is_preview value.
			$context['is_preview'] = $is_preview;

			// Render the block.
			\Timber\Timber::render( 'block/two_columns.twig', $context );
		}

	}
