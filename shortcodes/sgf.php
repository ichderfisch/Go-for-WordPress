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

add_shortcode( 'sgf', function ( $atts, $content = '' ) {
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

	if ( ! ( strpos( $atts['background'], '#' ) === 0 ) ) {
		$atts['background'] = plugins_url( 'img/' . sanitize_text_field( $atts['background'] ), __DIR__ );
	}

	$out = sprintf(
		'<div data-wgo-board="stoneHandler: WGo.Board.drawHandlers.%s, background: \'%s\'',
		sanitize_text_field( $atts['stones'] ),
		$atts['background']
	);

	if ( $atts['limit'] !== null ) {
		$a = explode( ',', sanitize_text_field( $atts['limit'] ) );
		$out .= sprintf( ', section: {top: %u, right: %u, bottom: %u, left: %u}', $a[0], $a[1], $a[2], $a[3] );
	}
	$out .= '"';

	if ( $atts['move'] !== null ) {
		$out .= ' data-wgo-move="' . $atts['move'] . '""';
	}

	$out .= sprintf(
		' style="width: %s; max-width: %s;"',
		sanitize_text_field( $atts['width'] ),
		sanitize_text_field( $atts['maxwidth'] )
	);

	if ( $atts['float'] === 'left' ) {
		$class = ' class="wgo-player-floating-left"';
	} elseif ( $atts['float'] === 'right' ) {
		$class = ' class="wgo-player-floating-right"';
	} else {
		$class = '';
	}
	$out .= $class;

	if ( $atts['static'] !== null ) {
		$out .= ' data-wgo-diagram="';
	} else {
		$out .= ' data-wgo="';
	}

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
			$sgf = @file_get_contents( $content );
			// Define a high cache lifetime, because SGF files normally don't change.
			// If they do, the expiration time should be made configurable.
			set_transient( $key, $sgf, WEEK_IN_SECONDS );
		}
		$content = $sgf;
	}

	$out .= esc_attr( $content );

	return $out . '"></div>';
} );
