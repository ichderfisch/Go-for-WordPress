<?php

namespace Wgo;

class Settings {

	private static $instance;

	private $title = 'Go, Baduk, Weiqi';
	private $settings_section_id = 'igo_settings_section';
	private $slug = 'igo_settings';
	private $loc = 'igo-lang';

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
	}


	/**
	 * Action admin_init
	 */
	public function admin_init() {
		$settings = array(
			'igo_stone_handler' => 'Stone design',
			'igo_background' => 'Background image',
			'igo_line_width' => 'Line width',
			'igo_default_width' => 'Default Kifu width',
			'igo_max_width' => 'Maximum Kifu width',
			'igo_i18n' => 'Language',
		);

		add_settings_section(
			$this->settings_section_id,    // ID
			'',                            // Title
			create_function( null, null ), // Callback
			$this->slug                    // Page
		);

		foreach ( $settings as $key => $label ) {
			register_setting( $this->slug, $key );

			add_settings_field(
				$key,                             // ID
				__( $label, $this->loc ),         // Field label
				array( $this, 'render_' . $key ), // Field renderer callback
				$this->slug,                      // Page
				$this->settings_section_id,       // Section
				array()                           // Callback arguments
			);
		}
	}

	public function render_igo_stone_handler() {
		$options = array(
			'NORMAL' => __( 'Normal', $this->loc ),
			'PAINTED' => __( 'Painted', $this->loc ),
			'GLOW' => __( 'Glow', $this->loc ),
			'MONO' => __( 'Monochrome', $this->loc ),
		);
		$currentValue = get_option( 'igo_stone_handler' );
		print( '<select name="igo_stone_handler">' );
		foreach ( $options as $key => $label ) {
			$selected = selected( $key, $currentValue, false );
			printf( '<option value="%s" %s>%s</option>', $key, $selected, $label );
		}
		print( '</select>' );
	}

	public function render_igo_background() {
		$currentValue = get_option( 'igo_background' );
		for ( $i = 1; $i < 7; $i += 1 ) {
			print( '<div style="display: inline-block; text-align: center; margin-right: 1em;">' );
			$file = sprintf( 'wood%s.jpg', $i );
			$img = plugins_url( '../img/' . $file, __FILE__ );
			printf( '<img src="%s" alt="" style="border: 1px solid gray;"><br>', $img );
			$checked = ( $currentValue === $file ) ? 'checked' : '';
			printf( '<input type="radio" name="igo_background" value="%s" %s>', $file, $checked );
			print( '</div>' );
		}
	}

	public function render_igo_line_width() {
		printf( '<input type="number" name="igo_line_width" value="%s">', get_option( 'igo_line_width' ) );
	}

	public function render_igo_default_width() {
		printf( '<input type="text" name="igo_default_width" value="%s">', get_option( 'igo_default_width' ) );
	}

	public function render_igo_max_width() {
		printf( '<input type="text" name="igo_max_width" value="%s">', get_option( 'igo_max_width' ) );
	}

	public function render_igo_i18n() {
		$options = array(
			'en' => __( 'English (Default)', $this->loc ),
			'de' => __( 'German', $this->loc ),
			'fr' => __( 'French', $this->loc ),
			'it' => __( 'Italian', $this->loc ),
			'cs' => __( 'Czech', $this->loc ),
			'zh' => __( 'Chinese (Simplified)', $this->loc ),
		);
		$currentValue = get_option( 'igo_i18n' );
		print( '<select name="igo_i18n">' );
		foreach ( $options as $key => $label ) {
			$selected = selected( $key, $currentValue, false );
			printf( '<option value="%s" %s>%s</option>', $key, $selected, $label );
		}
		print( '</select>' );
	}


	/**
	 * Action admin_menu
	 */
	public function admin_menu() {
		add_submenu_page(
			'themes.php',                      // Parent slug
			$this->title,                      // Page title
			$this->title,                      // Menu title
			'administrator',                   // Capability
			$this->slug,                       // Menu slug
			array( $this, 'display_settings' ) // Callback
		);
	}

	public function display_settings() {
		print( '<form class="wrap" method="post" action="options.php">' );
		printf( '<h2>%s</h2>', __( 'Kifu Layout', $this->loc ) );
		settings_fields( $this->slug );
		do_settings_sections( $this->slug );
		submit_button();
		print( '</form>' );
	}

}