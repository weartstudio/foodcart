<?php

use Timber\Site;

/**
 * Class StarterSite
 */
class StarterSite extends Site {


	public function __construct() {

		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );

		parent::__construct();
	}


	public function add_to_context( $context ) {

		$context['site']  = $this;

		$context['menuSecondary']  = Timber::get_menu('secondary_navigation');
		$context['menuPrimary']  = Timber::get_menu('primary_navigation');
		$context['menuFooter']  = Timber::get_menu('footer_navigation');

		return $context;
	}


	public function theme_supports() {

		// add theme support
		add_theme_support( 'title-tag' );
		add_theme_support( 'menus' );
		add_theme_support('woocommerce');

		// register nav menu
		register_nav_menus( array(
			'primary_navigation' => __('Primary Navigation', 'foodcart'),
			'secondary_navigation' => __('Secondary Navigation', 'foodcart'),
			'footer_navigation' => __('Footer Navigation', 'foodcart'),
		) );

		// remove gutenberg for pages
		remove_theme_support('block-templates');
		add_filter( 'use_block_editor_for_post_type', array($this,'disable_gutenberg_editor'), 10, 2 );

	}


	function disable_gutenberg_editor( $is_enabled, $post_type ) {

		if ( 'page' === $post_type ) {
			return false;
		}

		if ( 'post' === $post_type ) {
			return false;
		}

		return $is_enabled;

	}


}