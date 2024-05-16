<?php

// require timber
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/StarterSite.php';
require_once __DIR__ . '/inc/WooSite.php';

// Initialize Timber.
Timber\Timber::init();

// woo integrations
if ( class_exists( 'WooCommerce' ) ) {
	Timber\Integrations\WooCommerce\WooCommerce::init();
}

// init custom stuff
new StarterSite();
new WooSite();
