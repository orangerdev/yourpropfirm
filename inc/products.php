<?php

/**
 * Enqueue scripts and styles for yourpropfirm admin page
 * Hooked into admin_enqueue_scripts
 * Priority 10
 */
add_action( 'admin_enqueue_scripts', function () {
	$screen = get_current_screen();

	if ( $screen->id === 'toplevel_page_yourpropfirm' ) {

		wp_enqueue_script( 'datatable', '//cdn.datatables.net/2.2.1/js/dataTables.min.js', [ 'jquery' ], '2.2.1', true );

		wp_enqueue_style( 'datatable', '//cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css', [], '2.2.1', 'all' );

		wp_localize_script( 'datatable', 'yourpropfirm', [ 
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'yourpropfirm_get_products' )
		] );

	}

	if ( $screen->id === 'custom-products-api_page_yourpropfirm-add-product' ) {

		wp_localize_script( 'jquery', 'yourpropfirm', [ 
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'yourpropfirm_add_product' )
		] );

	}

} );

/**
 * Display list of products in yourpropfirm admin page
 * @return void
 */
function yourpropfirmListProducts() {
	require_once( YOURPROPFIRM_PLUGIN_DIR . 'view/product/list.php' );
}

/**
 * Display add product form in yourpropfirm admin page
 * @return void
 */
function yourpropfirmDisplayAddProductForm() {
	require_once( YOURPROPFIRM_PLUGIN_DIR . 'view/product/add.php' );
}

/**
 * AJAX handler to get products from API
 * Hooked into wp_ajax_yourpropfirm_get_products
 * Priority 10
 */
add_action( 'wp_ajax_yourpropfirm_get_products', function () {
	try {
		$data = $_GET;

		if ( ! wp_verify_nonce( $data['_wpnonce'], 'yourpropfirm_get_products' ) ) {
			throw new Exception( 'Invalid nonce' );
		}

		$api_url = carbon_get_theme_option( 'yourpropfirm_api_url' );
		$consumer_key = carbon_get_theme_option( 'yourpropfirm_consumer_key' );
		$consumer_secret = carbon_get_theme_option( 'yourpropfirm_consumer_secret' );

		if ( empty( $api_url ) || empty( $consumer_key ) || empty( $consumer_secret ) ) {
			throw new Exception( 'API URL, Consumer Key, and Consumer Secret must be filled' );
		}

		$api_url = add_query_arg( [ 
			'consumer_key' => $consumer_key,
			'consumer_secret' => $consumer_secret,
			'per_page' => 100
		], $api_url );

		$response = wp_remote_get( $api_url );

		if ( is_wp_error( $response ) ) {
			throw new Exception( $response->get_error_message() );
		}

		$body = wp_remote_retrieve_body( $response );

		if ( empty( $body ) ) {
			throw new Exception( 'Empty response' );
		}

		$products = json_decode( $body );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			throw new Exception( 'Invalid JSON response' );
		}

		wp_send_json( [ 'data' => $products ] );
	} catch (Exception $e) {
		wp_send_json_error( $e->getMessage() );
	}

	exit;
} );

add_action( 'wp_ajax_yourpropfirm_add_product', function () {
	try {
		$data = $_POST;

		if ( ! wp_verify_nonce( $data['_wpnonce'], 'yourpropfirm_add_product' ) ) {
			throw new Exception( 'Invalid nonce' );
		}

		if ( empty( $data['name'] ) || empty( $data['regular_price'] ) || empty( $data['stock_status'] ) || empty( $data['description'] ) ) {
			throw new Exception( 'All fields are required' );
		}

		if ( ! is_numeric( $data['regular_price'] ) ) {
			throw new Exception( 'Price must be a number' );
		}

		if ( ! in_array( $data['stock_status'], [ 'instock', 'outofstock' ] ) ) {
			throw new Exception( 'Invalid stock status' );
		}

		if ( strlen( $data['name'] ) < 5 ) {
			throw new Exception( 'Name must be at least 5 characters' );
		}

		if ( strlen( $data['description'] ) < 20 ) {
			throw new Exception( 'Description must be at least 20 characters' );
		}

		$api_url = carbon_get_theme_option( 'yourpropfirm_api_url' );
		$consumer_key = carbon_get_theme_option( 'yourpropfirm_consumer_key' );
		$consumer_secret = carbon_get_theme_option( 'yourpropfirm_consumer_secret' );

		if ( empty( $api_url ) || empty( $consumer_key ) || empty( $consumer_secret ) ) {
			throw new Exception( 'API URL, Consumer Key, and Consumer Secret must be filled' );
		}

		$response = wp_remote_post( $api_url, [ 
			'headers' => [ 
				'Authorization' => 'Basic ' . base64_encode( $consumer_key . ':' . $consumer_secret ),
				'Content-Type' => 'application/json'
			],
			'body' => json_encode( [ 
				...$data,
				'type' => 'simple'
			] )
		] );

		if ( is_wp_error( $response ) ) {
			throw new Exception( $response->get_error_message() );
		}

		$body = wp_remote_retrieve_body( $response );

		if ( empty( $body ) ) {
			throw new Exception( 'Empty response' );
		}

		$product = json_decode( $body );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			throw new Exception( 'Invalid JSON response' );
		}

		wp_send_json_success( $product );
	} catch (Exception $e) {
		wp_send_json_error( $e->getMessage() );
	}

	exit;
} );
