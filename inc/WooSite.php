<?php

use Timber\Site;

/**
 * Class WooSite
 */
class WooSite extends Site {


	public function __construct() {

		add_action( 'add_meta_boxes_product', array($this,'removeMetaboxExcerpt'), 9999 );

		parent::__construct();
	}

	function removeMetaboxExcerpt() {

		// e.g. remove short description
		remove_meta_box( 'postexcerpt', 'product', 'normal' );
		remove_meta_box( 'tagsdiv-product_tag', 'product', 'side' );

	}

}