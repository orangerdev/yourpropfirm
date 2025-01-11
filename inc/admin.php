<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Load Carbon Fields
 * Hooked into after_setup_theme
 * Priority 10
 */
add_action( 'after_setup_theme', function () {
	\Carbon_Fields\Carbon_Fields::boot();
} );

/**
 * Create admin menu for yourpropfirm and its sub-menus
 * Hooked into admin_menu
 * Priority 10
 */
add_action( 'admin_menu', function () {
	add_menu_page(
		'Custom Products API',
		'Custom Products API',
		'manage_options',
		'yourpropfirm',
		'yourpropfirmListProducts',
		'dashicons-admin-home',
	);

	add_submenu_page(
		"yourpropfirm",
		"List Products",
		"List Products",
		"manage_options",
		"yourpropfirm",
		"yourpropfirmListProducts"
	);

	add_submenu_page(
		'yourpropfirm',
		'Create Product',
		'Create Product',
		'manage_options',
		'yourpropfirm-add-product',
		'yourpropfirmDisplayAddProductForm'
	);
} );


/**
 * Register fields for Configuration
 * Hooked into carbon_fields_register_fields
 * Priority 10
 */
add_action( 'carbon_fields_register_fields', function () {
	Container::make( 'theme_options', 'Configuration' )
		->set_page_parent( 'yourpropfirm' )
		->add_fields( [ 
			Field::make( 'text', 'yourpropfirm_api_url', 'API Endpoint URL' )
				->set_help_text( 'Enter the URL of the API endpoint' )
				->set_attribute( 'type', 'url' )
				->set_required( true ),
			Field::make( 'text', 'yourpropfirm_consumer_key', 'Consumer Key' )
				->set_help_text( 'Enter the consumer key' )
				->set_required( true ),
			Field::make( 'text', 'yourpropfirm_consumer_secret', 'Consumer Secret' )
				->set_help_text( 'Enter the consumer secret' )
				->set_required( true ),

		] );
} );

