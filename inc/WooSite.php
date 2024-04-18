<?php

use Timber\Site;

/**
 * Class WooSite
 */
class WooSite extends Site {


	public function __construct() {

		add_action( 'add_meta_boxes_product', array($this,'remove_metabox_excerpt'), 9999 );
		add_filter('woocommerce_product_data_tabs', array($this,'remove_tab_product_admin'), 10, 1);
		add_filter( 'product_type_options', array($this,'remove_options_product_admin') );
		add_filter( 'product_type_selector', array($this,'remove_product_types') );
		parent::__construct();
	}

	function remove_metabox_excerpt() {

		// e.g. remove short description
		remove_meta_box( 'postexcerpt', 'product', 'normal' );
		remove_meta_box( 'tagsdiv-product_tag', 'product', 'side' );
		remove_meta_box( 'commentsdiv' , 'product' , 'normal' );
	}

	function remove_tab_product_admin($tabs){
		//unset($tabs['general']);
			unset($tabs['inventory']);
			unset($tabs['shipping']);
		 unset($tabs['advanced']);
		 unset($tabs['linked_product']);
		 unset($tabs['attribute']);
		 unset($tabs['variations']);
		 return($tabs);
 }

 function remove_options_product_admin($options){
		// remove "Virtual" checkbox
		if( isset( $options[ 'virtual' ] ) ) {
			unset( $options[ 'virtual' ] );
		}
		// remove "Downloadable" checkbox
		if( isset( $options[ 'downloadable' ] ) ) {
			unset( $options[ 'downloadable' ] );
		}
		return $options;
	}

	function remove_product_types( $types ){
    unset( $types['grouped'] );
    unset( $types['external'] );
    unset( $types['variable'] );

    return $types;
	}

}