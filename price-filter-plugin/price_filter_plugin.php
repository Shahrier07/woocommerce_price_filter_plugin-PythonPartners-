<?php
/*
Plugin Name: Woocommerce Product filter Plugin(Python Partners)
Plugin URI: localhost
Description: Filter by Discount of the product
Version: 1.0.0
Contributors: zgordon
Author: Zac Gordon
Author URI: https://zacgordon.com
License: GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wpplugin
Domain Path:  /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

define( 'WPPLUGIN_URL', plugin_dir_url( __FILE__ ) );

include( plugin_dir_path( __FILE__ ) . 'includes/wpplugin-styles.php');

include( plugin_dir_path( __FILE__ ) . 'includes/wpplugin-menus.php');

?>
