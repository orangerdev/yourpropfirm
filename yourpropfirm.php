<?php


/**
 * Plugin Name:       YourPropFirm - Ridwan Arifandi
 * Plugin URI:        https://ridwan-arifandi.com
 * Description:       Custom plugin for YourPropFirm sessment test
 * Version:           1.0.0
 * Author:            Ridwan Arifandi
 * Author URI:        https://ridwan-arifandi.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       yourpropfirm
 */

define( 'YOURPROPFIRM_VERSION', '1.0.0' );
define( 'YOURPROPFIRM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'YOURPROPFIRM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once( 'vendor/autoload.php' );
require_once( 'inc/admin.php' );
require_once( 'inc/products.php' );