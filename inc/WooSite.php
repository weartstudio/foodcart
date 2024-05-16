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

		add_filter( 'timber/context', array($this,'cart_summary') );
		parent::__construct();
	}

	function remove_metabox_excerpt() {
		add_theme_support( 'woocommerce' );
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

 function cart_summary($context){
	$context['cart'] = [];
	foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
		$products_array = [];

		// General vars
		$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
		$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

		//
		if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {

				// URL
				$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
				$products_array['url'] = get_permalink($product_id);

				// Delete button
				$myarray['delete_permalink'] = esc_url(wc_get_cart_remove_url($cart_item_key));
				$products_array['delete_productid'] = esc_attr($product_id);
				$products_array['delete_sku'] = esc_attr($_product->get_sku());

				// Thumbnail
				$thumbnail = get_the_post_thumbnail_url($product_id);

				if (! $product_permalink) {
						$products_array['thumbnail'] = $thumbnail;
				} else {
						$products_array['thumbnail'] = $thumbnail;
						// printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
				}

				// Title
				if (! $product_permalink) {
						$products_array['title'] = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
				} else {
						$products_array['title'] = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
						// $products_array['title'] = apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key);
				}

				// Backorder notification
				if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
						$products_array['backorder'] = true;
				}

				// Price
				$products_array['price'] = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);

				// Quantity
				// if ($_product->is_sold_individually()) {
				// 		$product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1">', $cart_item_key);
				// } else {
				// 		$product_quantity = woocommerce_quantity_input(array(
				// 				'input_name'   => "cart[{$cart_item_key}][qty]",
				// 				'input_value'  => $cart_item['quantity'],
				// 				'max_value'    => $_product->get_max_purchase_quantity(),
				// 				'min_value'    => '0',
				// 				'product_name' => $_product->get_name(),
				// 		), $_product, false);
				// }
				$products_array['quantity'] = $cart_item['quantity'];

				// Total
				$products_array['total'] = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);

				// Merge with products
				$context['cart'][] = $products_array;
		}
	}
	$count = WC()->cart->get_cart_contents_count();
	$context['cart_count'] = $count > 0 ? $count . ' elem / ': '';
	$context['cart_total'] = WC()->cart->get_total();
	return $context;
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