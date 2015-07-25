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
			'width' => get_option( 'igo_default_width' ),
			'maxwidth' => get_option( 'igo_max_width' ),
			'stones' => get_option( 'igo_stone_handler' ),
			'background' => get_option( 'igo_background' ),
			'move' => null,
			'static' => null,
			'limit' => null,
			'float' => null,
		),
		$atts
	);

	if ( !( strpos( $atts['background'], '#' ) === 0 ) ) {
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
	$content = preg_replace( '@<w?br[^>]*/?>@', '', $content );
	$out .= str_replace( array( "\r", "\r\n", "\n" ), '', $content ) . '""></div>';

	return $out;
} );