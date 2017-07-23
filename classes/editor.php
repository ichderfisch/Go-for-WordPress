<?php

namespace Wgo;

class Editor {

	public static $instance;

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
	}

	/**
	 * Action admin_head
	 */
	public function admin_head() {
		if ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) {
			if ( 'true' === get_user_option( 'rich_editing' ) ) {
				add_filter( 'mce_external_plugins', array( $this, 'add_plugin' ) );
				add_filter( 'mce_buttons', array( $this, 'register_buttons' ) );
			}
		}
	}

	public function add_plugin( array $plugins ) {
		$plugins['wgo_sgf'] = plugins_url( '../editor/' . 'sgf.js', __FILE__ );
		return $plugins;
	}

	public function register_buttons( array $buttons ) {
		array_push( $buttons, 'wgo_sgf' );
		return $buttons;
	}

}