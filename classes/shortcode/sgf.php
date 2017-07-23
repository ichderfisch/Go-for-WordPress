<?php

/**
 * SGF shortcode
 *
 * Parameters:
 *    width
 *    maxwidth
 *    stones
 *    background
 *    limit="top,right,bottom,left"
 *    static
 *    float
 */

namespace Wgo\Shortcode;

class Sgf {

	public function __construct() {
		add_shortcode( 'sgf', array( $this, 'init' ) );
	}

	/**
	 * @param string $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public function init( $atts, $content = '' ) {
		$atts = shortcode_atts(
			array(
				'width'      => get_option( 'igo_default_width' ),
				'maxwidth'   => get_option( 'igo_max_width' ),
				'stones'     => get_option( 'igo_stone_handler' ),
				'background' => get_option( 'igo_background' ),
				'move'       => null,
				'static'     => null,
				'limit'      => null,
				'float'      => null,
			),
			$atts
		);

		// If a link is found, extract its URL
		$content = preg_replace( '@<a[^>]+href="([^"]+)"[^>]*>.*?</a>@', '$1', $content );
		$content = strip_tags( $content );
		$content = str_replace( array( "\r", "\r\n", "\n" ), '', $content );

		// When an URL is used, embed its content.
		// This way we can also use remote URLs.
		// The content is cached to avoid excessive remote requests.
		if ( preg_match( '@^https?://@', $content ) ) {
			$key = 'wgo_sgf_' . md5( $content );
			$sgf = get_transient( $key );
			if ( false === $sgf ) {
				$response = wp_remote_get( $content );
				if (
					( $response instanceof \WP_Error ) ||
					( 200 !== wp_remote_retrieve_response_code( $response ) )
				) {
					return $this->error_message( $content );
				} else {
					$sgf = wp_remote_retrieve_body( $response );
					// Define a high cache lifetime, because SGF files normally don't change.
					// If they do, the expiration time should be made configurable.
					set_transient( $key, $sgf, WEEK_IN_SECONDS );
				}
			}
			$content = $sgf;
		}

		$html = sprintf(
			'<div data-wgo-board="stoneHandler: WGo.Board.drawHandlers.%s, background: \'%s\'',
			esc_attr( $atts['stones'] ),
			esc_attr( plugins_url( 'img/' . $atts['background'], dirname( __DIR__ ) ) )
		);

		if ( null !== $atts['limit'] ) {
			$limit = explode( ',', esc_attr( $atts['limit'] ) );
			$html .= sprintf( ', section: {top: %u, right: %u, bottom: %u, left: %u}', $limit[0], $limit[1], $limit[2], $limit[3] );
		}

		$html .= '"';

		if ( null !== $atts['move'] ) {
			$html .= ' data-wgo-move="' . esc_attr( $atts['move'] ) . '"';
		}

		$html .= sprintf(
			' style="width: %s; max-width: %s;"',
			esc_attr( $atts['width'] ),
			esc_attr( $atts['maxwidth'] )
		);

		if ( 'left' === $atts['float'] ) {
			$class = ' class="wgo-player-floating-left"';
		} elseif ( 'right' === $atts['float'] ) {
			$class = ' class="wgo-player-floating-right"';
		} else {
			$class = '';
		}
		$html .= $class;

		if ( null !== $atts['static'] ) {
			$html .= ' data-wgo-diagram="';
		} else {
			$html .= ' data-wgo="';
		}

		$html .= esc_attr( $content );

		return $html . '"></div>';

	}

	/**
	 * @param string $file
	 *
	 * @return string
	 */
	public function error_message( $file = '' ) {
		return sprintf(
			'<div class="alert alert-warning" role="alert">%s</div>',
			__( 'Error! The SGF file could not be found: ' ) . esc_html( $file )
		);
	}

}
