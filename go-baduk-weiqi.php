<?php
/**
 * Plugin Name: Go, Baduk, Weiqi
 * Plugin URI: http://guzumi.de/wgo-plugin
 * Description: A plugin for displaying SGF files using the <a href=http://wgo.waltheri.net>wgo.js library</a> and getting player data from the European Go Database. It adds a new shortcode <strong>[wgo]</strong> to the editor which can be used to embed the given SGF.
 * Version: 0.4
 * Author: Christian Mocek
 * Author URI: http://github.com/klangfarbe
 * License: MIT
 * Text Domain: igo-lang
 * Domain Path: /languages
 */

require_once( __DIR__ . '/egd.php' );
require_once( __DIR__ . '/sgf.php' );
require_once( __DIR__ . '/tablesorter.php' );


/**
 * Activation hook
 */
register_activation_hook( __FILE__, function () {
	add_option( 'igo_background', 'wood1.jpg' );
	add_option( 'igo_line_width', 1 );
	add_option( 'igo_default_width', '100%' );
	add_option( 'igo_max_width', '900px' );
	add_option( 'igo_stone_handler', 'Normal' );
	add_option( 'igo_i18n', 'en' );
} );


/**
 * Deactivation hook
 */
register_deactivation_hook( __FILE__, function () {
	delete_option( 'igo_background' );
	delete_option( 'igo_line_width' );
	delete_option( 'igo_default_width' );
	delete_option( 'igo_max_width' );
	delete_option( 'igo_stone_handler' );
	delete_option( 'igo_i18n' );
} );


/**
 * Link to settings in plugin menu
 */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
	$url = get_admin_url() . '/themes.php?page=igo_settings';
	$settings_link = '<a href="' . $url . '">' . __( 'Settings', 'igo-lang' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
} );


/**
 * Load plugin textdomain
 */
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'igo-lang', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
} );


/**
 * Admin page
 */
require_once __DIR__ . '/classes/settings.php';

add_action( 'admin_init', function () {
	\Wgo\Settings::get_instance()->admin_init();
} );

add_action( 'admin_menu', function () {
	\Wgo\Settings::get_instance()->admin_menu();
} );