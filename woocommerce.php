<?php

$context = Timber::context();
$context['sidebar'] = Timber::get_widgets('shop-sidebar');


if (is_singular('product')) {


	$context['post'] = Timber::get_post();
	$product = wc_get_product($context['post']->ID);
	$context['product'] = $product;

	// Get related products
	$related_limit = wc_get_loop_prop('columns');
	$related_ids = wc_get_related_products($context['post']->id, $related_limit);
	$context['related_products'] = Timber::get_posts($related_ids);

	// Restore the context and loop back to the main query loop.
	wp_reset_postdata();

	Timber::render('views/woo/single-product.twig', $context);


} else {

	$posts = Timber::get_posts();
	$context['products'] = $posts;

	if (is_product_category()) {
			$queried_object = get_queried_object();
			$term_id = $queried_object->term_id;
			$context['category'] = get_term($term_id, 'product_cat');
			$context['title'] = single_term_title('', false);
		}

	$foods = [];

	$args = array(
		// 'number'     => $number,
		'orderby'   => 'title',
		'order'     => 'ASC',
		// 'hide_empty' => $hide_empty,
		// 'include'   => $ids
	);

	$product_categories = get_terms( 'product_cat', $args );
	$count = count($product_categories);

	if ( $count > 0 ){
		foreach ( $product_categories as $product_category ) {

			$args = array(
			'posts_per_page' => -1,
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					// 'terms' => 'white-wines'
					'terms' => $product_category->slug
					)
				),
				'post_type' => 'product',
				'orderby' => 'title,'
			);

			$item = [];
			$item['cat'] = $product_category->name;
			$item['foods'] = Timber::get_posts($args);
			$foods[] = $item;

			// $products = new WP_Query( $args );

		}
	}

	$context['foods'] = $foods;


	Timber::render('views/woo/archive.twig', $context);
}

